<?php

namespace Lara\Internal;

use CURLFile;
use Lara\AccessKey;
use Lara\AuthToken;
use Lara\LaraApiException;
use Lara\LaraException;
use Lara\LaraTimeoutException;
use Lara\Version;

class HttpClient
{
    private $baseUrl;
    private $accessKey;
    private $authToken;
    private $extraHeaders = [];
    private $curl;

    /**
     * HttpClient constructor
     * @param string $baseUrl Base API URL
     * @param AccessKey|AuthToken|null $auth Authentication method
     * @throws LaraException
     */
    public function __construct($baseUrl, $auth = null)
    {
        while (substr($baseUrl, -1) == '/')
            $baseUrl = substr($baseUrl, 0, -1);

        $this->baseUrl = $baseUrl;

        if ($auth instanceof AccessKey) {
            $this->accessKey = $auth;
        } elseif ($auth instanceof AuthToken) {
            $this->authToken = $auth;
        } else {
            throw new LaraException('No authentication method provided');
        }

        $this->curl = curl_init();
    }

    public function __destruct()
    {
        curl_close($this->curl);
    }

    /**
     * @param $name string header name
     * @param $value string header value
     * @return void
     */
    public function setExtraHeader($name, $value)
    {
        $this->extraHeaders[$name] = $value;
    }

    /**
     * @param $name string header name
     * @return void
     */
    public function resetExtraHeader($name)
    {
        unset($this->extraHeaders[$name]);
    }

    /**
     * @param $path string
     * @param $params array|null
     * @param $headers array|null
     * @return mixed
     * @throws LaraException
     */
    public function get($path, $params = null, $headers = null)
    {
        $queryString = $this->buildQueryString($params);
        return $this->authenticatedRequest('GET', $path . $queryString, null, null, $headers);
    }

    /**
     * @param $path string
     * @param $params array|null
     * @param $headers array|null
     * @return resource
     * @throws LaraException
     */
    public function getStream($path, $params = null, $headers = null)
    {
        $queryString = $this->buildQueryString($params);
        return $this->authenticatedRequest('GET', $path . $queryString, null, null, $headers, false, true);
    }

    /**
     * @param $path string
     * @param $body array|null
     * @param $files array|null
     * @param $headers array|null
     * @return mixed
     * @throws LaraException
     */
    public function post($path, $body = null, $files = null, $headers = null)
    {
        return $this->authenticatedRequest('POST', $path, $body, $files, $headers);
    }

    /**
     * @param $path string
     * @param $body array|null
     * @param $files array|null
     * @param $headers array|null
     * @param $callback callable|null Callback function for each chunk (chunk) => void
     * @return mixed Last result
     * @throws LaraException
     */
    public function postStream($path, $body = null, $files = null, $headers = null, $callback = null)
    {
        return $this->authenticatedStreamRequest('POST', $path, $body, $files, $headers, $callback);
    }

    /**
     * @param $path string
     * @param $body array|null
     * @param $files array|null
     * @param $headers array|null
     * @return mixed
     * @throws LaraException
     */
    public function put($path, $body = null, $files = null, $headers = null)
    {
        return $this->authenticatedRequest('PUT', $path, $body, $files, $headers);
    }

    /**
     * @param $path string
     * @param $body array|null
     * @param $headers array|null
     * @return mixed
     * @throws LaraException
     */
    public function delete($path, $body = null, $headers = null)
    {
        return $this->authenticatedRequest('DELETE', $path, $body, null, $headers);
    }

    /**
     * Build query string from parameters array
     * @param array|null $params Query parameters
     * @return string Query string with leading '?' or empty string
     */
    private function buildQueryString($params)
    {
        if (!$params || !is_array($params)) {
            return '';
        }

        $queryString = http_build_query(array_filter($params, function ($el) {
            return isset($el);
        }));

        return $queryString ? '?' . $queryString : '';
    }

    /**
     * Parse a JSON line into a chunk with status and data
     * @param string $line JSON line to parse
     * @return array|null Parsed chunk with 'status' and 'data' keys, or null if invalid
     */
    private function parseStreamChunk($line)
    {
        $line = trim($line);
        if ($line === '') {
            return null;
        }

        $parsed = json_decode($line, true);
        if ($parsed === null) {
            return null;
        }

        $status = isset($parsed['status']) ? $parsed['status'] : null;
        $data = isset($parsed['data']) ? $parsed['data'] : $parsed;
        $content = isset($data['content']) ? $data['content'] : $data;

        return ['status' => $status, 'data' => $content];
    }

    /**
     * Makes an authenticated streaming HTTP request with real-time callback execution
     * @param string $method HTTP method
     * @param string $path Request path
     * @param array|null $body Request body data
     * @param array|null $files Files to upload
     * @param array|null $headers Additional headers
     * @param callable|null $callback Callback for each chunk (called immediately as chunks arrive)
     * @param bool $isRetry Whether this is a retry after token refresh
     * @return mixed Last result
     * @throws LaraException
     */
    private function authenticatedStreamRequest($method, $path, $body = null, $files = null, $headers = null, $callback = null, $isRetry = false)
    {
        $token = $this->authenticate();

        if ($path[0] !== '/') {
            $path = '/' . $path;
        }

        $url = $this->baseUrl . $path;

        $requestHeaders = [
            "X-Lara-Date" => gmdate("D, d M Y H:i:s") . " GMT",
            "X-Lara-SDK-Name" => "lara-php",
            "X-Lara-SDK-Version" => Version::get()
        ];

        if ($this->extraHeaders) {
            $requestHeaders = array_merge($this->extraHeaders, $requestHeaders);
        }

        if ($headers) {
            $requestHeaders = array_merge($headers, $requestHeaders);
        }

        $requestHeaders['Authorization'] = 'Bearer ' . $token;

        if ($body) {
            $body = array_filter($body, function ($el) {
                return isset($el);
            });
            if (empty($body)) {
                $body = null;
            }
        }

        $requestBody = null;

        if ($files && is_array($files)) {
            $files = array_filter($files);
            if (!empty($files)) {
                $requestBody = $body !== null ? $body : [];
                $requestHeaders["Content-Type"] = "multipart/form-data";

                foreach ($files as $key => $filePath) {
                    if (!file_exists($filePath)) {
                        throw new LaraApiException(400, 'FileNotFound', "File $filePath not found");
                    }
                    $requestBody[$key] = new CURLFile($filePath);
                }
            } else {
                $files = null;
            }
        }

        if (!$files) {
            if (!empty($body)) {
                $requestHeaders["Content-Type"] = "application/json";
                $requestBody = json_encode($body);
            }
        }

        curl_reset($this->curl);

        $buffer = '';
        $lastResult = null;
        $hadError = false;
        $errorChunk = null;
        $self = $this;

        // Real-time write function: processes and calls callback immediately
        $writeFunction = function($ch, $data) use (&$buffer, &$lastResult, &$hadError, &$errorChunk, $callback, $self) {
            // If we already encountered an error, abort the transfer immediately
            if ($hadError) {
                return 0; // Returning 0 aborts the CURL transfer
            }

            $buffer .= $data;
            $lines = explode("\n", $buffer);
            $buffer = array_pop($lines); // Keep incomplete line in buffer

            foreach ($lines as $line) {
                $chunk = $self->parseStreamChunk($line);
                if ($chunk === null) {
                    continue;
                }

                $chunkStatus = $chunk['status'];

                // Skip chunks without a status (invalid format)
                if ($chunkStatus === null) {
                    continue;
                }

                // Check for errors (401 or any non-2xx)
                if ($chunkStatus < 200 || $chunkStatus >= 300) {
                    $hadError = true;
                    $errorChunk = $chunk;
                    // Abort transfer immediately on error
                    return 0;
                }

                // Success chunk - update lastResult and call callback immediately
                $lastResult = $chunk['data'];
                if ($callback !== null) {
                    call_user_func($callback, $lastResult);
                }
            }

            return strlen($data);
        };

        $curlOptions = [
            CURLOPT_URL => $url,
            CURLOPT_RETURNTRANSFER => false,
            CURLOPT_WRITEFUNCTION => $writeFunction,
            CURLOPT_HTTPHEADER => array_map(function ($key, $value) {
                return ($value === '' || $value === null) ? "$key;" : "$key: $value";
            }, array_keys($requestHeaders), $requestHeaders),
        ];

        $httpMethod = strtoupper($method);

        if ($httpMethod !== 'GET') {
            $curlOptions[CURLOPT_CUSTOMREQUEST] = $httpMethod;
        }

        if ($requestBody !== null) {
            $curlOptions[CURLOPT_POSTFIELDS] = $requestBody;
        }

        curl_setopt_array($this->curl, $curlOptions);

        $result = curl_exec($this->curl);

        // If we aborted due to an error chunk, handle it before checking curl errors
        if ($hadError && $errorChunk) {
            // Handle 401 - token expired, refresh and retry
            if (!$isRetry && $errorChunk['status'] === 401) {
                $this->authToken->clearToken();
                return $this->authenticatedStreamRequest($method, $path, $body, $files, $headers, $callback, true);
            }
            $this->throwApiException($errorChunk['status'], $errorChunk['data']);
        }

        if ($result === false) {
            $curl_errno = curl_errno($this->curl);
            throw (
                $curl_errno == 28
                    ? new LaraTimeoutException("Connection timed out ($curl_errno)", 500)
                    : new LaraException("Unable to contact server ($curl_errno)", 500)
            );
        }

        // Process remaining buffer (only reached if no error during streaming)
        if ($buffer !== '') {
            $chunk = $this->parseStreamChunk($buffer);
            if ($chunk !== null) {
                $chunkStatus = $chunk['status'];

                // Skip chunks without a status (invalid format)
                if ($chunkStatus !== null) {
                    // Check for errors in final buffer chunk
                    if ($chunkStatus < 200 || $chunkStatus >= 300) {
                        // Handle 401 - token expired, refresh and retry
                        if (!$isRetry && $chunkStatus === 401) {
                            $this->authToken->clearToken();
                            return $this->authenticatedStreamRequest($method, $path, $body, $files, $headers, $callback, true);
                        }
                        $this->throwApiException($chunkStatus, $chunk['data']);
                    }

                    $lastResult = $chunk['data'];
                    if ($callback !== null) {
                        call_user_func($callback, $lastResult);
                    }
                }
            }
        }

        // Fallback: check HTTP status code for non-streamed errors
        $statusCode = curl_getinfo($this->curl, CURLINFO_HTTP_CODE);
        if ($statusCode < 200 || $statusCode >= 300) {
            $this->throwApiException($statusCode, null);
        }

        if ($lastResult === null) {
            throw new LaraException("No translation result received");
        }

        return $lastResult;
    }

    /**
     * Makes an authenticated HTTP request
     * @param string $method HTTP method (GET, POST, PUT, DELETE)
     * @param string $path Request path
     * @param array|null $body Request body data
     * @param array|null $files Files to upload (path to file)
     * @param array|null $headers Additional headers
     * @param bool $isRetry Whether this is a retry after token refresh
     * @param bool $returnStream Whether to return a stream instead of parsed response
     * @return mixed Response data or stream resource
     * @throws LaraException
     * @throws LaraTimeoutException
     * @throws LaraApiException
     */
    private function authenticatedRequest($method, $path, $body = null, $files = null, $headers = null, $isRetry = false, $returnStream = false)
    {
        $token = $this->authenticate();

        if ($path[0] !== '/') {
            $path = '/' . $path;
        }

        $url = $this->baseUrl . $path;

        $requestHeaders = [
            "X-Lara-Date" => gmdate("D, d M Y H:i:s") . " GMT",
            "X-Lara-SDK-Name" => "lara-php",
            "X-Lara-SDK-Version" => Version::get()
        ];

        if ($this->extraHeaders) {
            $requestHeaders = array_merge($this->extraHeaders, $requestHeaders);
        }

        if ($headers) {
            $requestHeaders = array_merge($headers, $requestHeaders);
        }

        $requestHeaders['Authorization'] = 'Bearer ' . $token;

        if ($body) {
            $body = array_filter($body, function ($el) {
                return isset($el);
            });
            if (empty($body)) {
                $body = null;
            }
        }

        $requestBody = null;

        if ($files && is_array($files)) {
            $files = array_filter($files);
            if (!empty($files)) {
                $requestBody = $body !== null ? $body : [];
                foreach ($files as $key => $filePath) {
                    if (!file_exists($filePath)) {
                        throw new LaraApiException(400, 'FileNotFound', "File $filePath not found");
                    }
                    $mimeType = mime_content_type($filePath) ?: 'application/octet-stream';
                    $requestBody[$key] = new CURLFile(realpath($filePath), $mimeType, basename($filePath));
                }
            } else {
                $files = null;
            }
        }

        if (!$files) {
            if (!empty($body)) {
                $requestHeaders["Content-Type"] = "application/json";
                $requestBody = json_encode($body);
            }
        }

        curl_reset($this->curl);

        $stream = null;
        if ($returnStream) {
            $stream = fopen('php://temp', 'w+');
        }

        $curlOptions = [
            CURLOPT_URL => $url,
            CURLOPT_RETURNTRANSFER => $returnStream ? false : 1,
            CURLOPT_HTTPHEADER => array_map(function ($key, $value) {
                return ($value === '' || $value === null) ? "$key;" : "$key: $value";
            }, array_keys($requestHeaders), $requestHeaders),
        ];

        if ($returnStream) {
            $curlOptions[CURLOPT_FILE] = $stream;
        }

        $httpMethod = strtoupper($method);

        if ($httpMethod !== 'GET') {
            $curlOptions[CURLOPT_CUSTOMREQUEST] = $httpMethod;
        }

        if ($requestBody !== null) {
            $curlOptions[CURLOPT_POSTFIELDS] = $requestBody;
        }

        curl_setopt_array($this->curl, $curlOptions);

        $result = curl_exec($this->curl);

        if ($result === false) {
            if ($returnStream && $stream) {
                fclose($stream);
            }
            $curl_errno = curl_errno($this->curl);
            throw (
                $curl_errno == 28
                    ? new LaraTimeoutException("Connection timed out ($curl_errno)", 500)
                    : new LaraException("Unable to contact server ($curl_errno)", 500)
            );
        }

        $statusCode = curl_getinfo($this->curl, CURLINFO_HTTP_CODE);

        if ($returnStream) {
            rewind($stream);
            if ($statusCode < 200 || $statusCode >= 300) {
                $errorBody = stream_get_contents($stream);
                fclose($stream);
                $this->throwApiException($statusCode, json_decode($errorBody, true));
            }
            return $stream;
        }

        $responseBody = json_decode($result, true);

        if (200 <= $statusCode && $statusCode < 300) {
            return isset($responseBody['content']) ? $responseBody['content'] : $responseBody;
        }

        if ($statusCode === 401 && !$isRetry) {
            $error = isset($responseBody) ? $responseBody : [];
            $errorMessage = isset($error['message']) ? $error['message'] : (isset($error['error']['message']) ? $error['error']['message'] : null);

            if ($errorMessage === 'jwt expired') {
                $this->authToken->clearToken();
                return $this->authenticatedRequest($method, $path, $body, $files, $headers, true, $returnStream);
            }
        }

        $this->throwApiException($statusCode, $responseBody);
    }

    /**
     * Throws a LaraApiException with extracted error information
     * @param int $statusCode The HTTP status code
     * @param array|null $responseBody Response body
     * @param string $defaultMessage Default error message if not found in response
     * @throws LaraApiException
     */
    private function throwApiException($statusCode, $responseBody, $defaultMessage = 'An unknown error occurred')
    {
        $error = isset($responseBody['error']) ? $responseBody['error'] : $responseBody;
        throw new LaraApiException(
            $statusCode,
            isset($error['type']) ? $error['type'] : 'UnknownError',
            isset($error['message']) ? $error['message'] : $defaultMessage
        );
    }

    /**
     * Authenticates and returns an access token
     * Tries authentication methods in order: existing token, refresh token, access key
     * @return string Access token
     * @throws LaraException
     */
    protected function authenticate()
    {
        if ($this->authToken !== null && $this->authToken->getToken() !== null) {
            return $this->authToken->getToken();
        }

        if ($this->authToken !== null && $this->authToken->getRefreshToken() !== null) {
            try {
                return $this->refreshToken();
            } catch (LaraException $e) {
                $this->authToken = null;
            }
        }

        if ($this->accessKey !== null) {
            return $this->authenticateWithAccessKey();
        }

        throw new LaraException('No authentication method available', 401);
    }

    /**
     * Refreshes the access token using the refresh token
     * @return string New access token
     * @throws LaraApiException
     * @throws LaraException
     * @throws LaraTimeoutException
     */
    private function refreshToken()
    {
        $headers = [
            'Authorization' => 'Bearer ' . $this->authToken->getRefreshToken(),
            'X-Lara-Date' => gmdate("D, d M Y H:i:s") . " GMT"
        ];

        $response = $this->rawRequest('POST', '/v2/auth/refresh', null, $headers);

        $token = isset($response['body']['token']) ? $response['body']['token'] : null;

        if (!$token) {
            throw new LaraApiException(500, 'ServerError', 'Missing token in authentication response');
        }

        $this->authToken = AuthToken::fromResponse($response);
        return $this->authToken->getToken();
    }

    /**
     * Authenticate using an access key with challenge-based authentication
     * @return string Access token
     * @throws LaraApiException
     * @throws LaraException
     * @throws LaraTimeoutException
     */
    private function authenticateWithAccessKey()
    {
        $method = 'POST';
        $path = '/v2/auth';
        $date = gmdate("D, d M Y H:i:s") . " GMT";
        $contentType = 'application/json';

        $body = ['id' => $this->accessKey->getId()];
        $bodyString = json_encode($body);
        $contentMD5 = base64_encode(md5($bodyString, true));

        $challenge = $this->computeSignature(
            $this->accessKey->getSecret(),
            $method,
            $path,
            $contentMD5,
            $contentType,
            $date
        );

        $headers = [
            'Authorization' => 'Lara:' . $challenge,
            'X-Lara-Date' => $date,
            'Content-Type' => $contentType,
            'Content-MD5' => $contentMD5
        ];

        if ($this->extraHeaders) {
            $headers = array_merge($this->extraHeaders, $headers);
        }

        $response = $this->rawRequest($method, $path, $bodyString, $headers);

        $token = isset($response['body']['token']) ? $response['body']['token'] : null;

        if (!$token) {
            throw new LaraApiException(500, 'ServerError', 'Missing token in authentication response');
        }

        $this->authToken = AuthToken::fromResponse($response);
        return $this->authToken->getToken();
    }

    /**
     * Compute HMAC-SHA256 signature for challenge-based authentication
     * @param string $secret The access key secret
     * @param string $method HTTP method (e.g., POST)
     * @param string $path Request path
     * @param string $contentMD5 MD5 hash of the body content (Base64)
     * @param string $contentType Content-Type header value
     * @param string $date Date header value
     * @return string Base64-encoded HMAC-SHA256 signature
     */
    private function computeSignature($secret, $method, $path, $contentMD5, $contentType, $date)
    {
        $challenge = implode("\n", [$method, $path, $contentMD5, $contentType, $date]);
        return base64_encode(hash_hmac('sha256', $challenge, $secret, true));
    }

    /**
     * Makes an unauthenticated HTTP request (used for authentication endpoints)
     * @param string $method HTTP method (GET, POST, etc.)
     * @param string $path Request path
     * @param string|null $body Request body
     * @param array $headers HTTP headers
     * @return array Response with 'body', 'headers', 'statusCode' keys
     * @throws LaraException
     * @throws LaraTimeoutException
     * @throws LaraApiException
     */
    private function rawRequest($method, $path, $body = null, $headers = [])
    {
        if ($path[0] !== '/') {
            $path = '/' . $path;
        }

        $url = $this->baseUrl . $path;

        curl_reset($this->curl);

        $responseHeaders = [];
        $headerCallback = function ($curl, $header) use (&$responseHeaders) {
            $len = strlen($header);
            $header = explode(':', $header, 2);
            if (count($header) < 2) {
                return $len;
            }

            $responseHeaders[strtolower(trim($header[0]))] = trim($header[1]);
            return $len;
        };

        $curlOptions = [
            CURLOPT_URL => $url,
            CURLOPT_RETURNTRANSFER => 1,
            CURLOPT_CUSTOMREQUEST => strtoupper($method),
            CURLOPT_HTTPHEADER => array_map(function ($key, $value) {
                return ($value === '' || $value === null) ? "$key;" : "$key: $value";
            }, array_keys($headers), $headers),
            CURLOPT_HEADERFUNCTION => $headerCallback
        ];

        if ($body !== null) {
            $curlOptions[CURLOPT_POSTFIELDS] = $body;
        }

        curl_setopt_array($this->curl, $curlOptions);

        $result = curl_exec($this->curl);

        if ($result === false) {
            $curl_errno = curl_errno($this->curl);
            throw $curl_errno == 28
                ? new LaraTimeoutException("Connection timed out ($curl_errno)", 500)
                : new LaraException("Unable to contact server ($curl_errno)", 500);
        }

        $statusCode = curl_getinfo($this->curl, CURLINFO_HTTP_CODE);
        $responseBody = json_decode($result, true);

        if ($statusCode < 200 || $statusCode >= 300) {
            $this->throwApiException($statusCode, $responseBody);
        }

        return [
            'body' => isset($responseBody['content']) ? $responseBody['content'] : $responseBody,
            'headers' => $responseHeaders,
            'statusCode' => $statusCode
        ];
    }
}
