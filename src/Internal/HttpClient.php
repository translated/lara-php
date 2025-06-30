<?php

namespace Lara\Internal;

use CURLFile;
use Lara\LaraApiException;
use Lara\LaraException;
use Lara\LaraTimeoutException;
use Lara\Version;

class HttpClient
{

    private $baseUrl;
    private $accessKeyId;
    private $accessKeySecret;
    private $extraHeaders = [];
    private $curl;

    public function __construct($baseUrl, $accessKeyId, $accessKeySecret)
    {
        while (substr($baseUrl, -1) == '/')
            $baseUrl = substr($baseUrl, 0, -1);

        $this->baseUrl = $baseUrl;
        $this->accessKeyId = $accessKeyId;
        $this->accessKeySecret = $accessKeySecret;
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
     * @param $path string
     * @param $params array
     * @return mixed
     * @throws LaraException
     */
    public function get($path, $params = null, $headers = null)
    {
        return $this->request('GET', $path, $params, null, $headers);
    }

    /**
     * @param $path string
     * @param $params array
     * @return mixed
     * @throws LaraException
     */
    public function delete($path, $params = null, $headers = null)
    {
        return $this->request('DELETE', $path, $params, null, $headers);
    }

    /**
     * @param $path string
     * @param $body array
     * @param $files array
     * @return mixed
     * @throws LaraException
     */
    public function post($path, $body = null, $files = null, $headers = null)
    {
        return $this->request('POST', $path, $body, $files, $headers);
    }

    /**
     * @param $path string
     * @param $body array
     * @param $files array
     * @return mixed
     * @throws LaraException
     */
    public function put($path, $body = null, $files = null, $headers = null)
    {
        return $this->request('PUT', $path, $body, $files, $headers);
    }

    /**
     * @param $method string
     * @param $path string
     * @param $body array
     * @param $files array
     * @return mixed
     * @throws LaraException
     */
    private function request($method, $path, $body = null, $files = null, $headers = null)
    {
        if ($path[0] != '/')
            $path = '/' . $path;

        $url = $this->baseUrl . $path;


        $_headers = [
            "X-HTTP-Method-Override" => $method,
            "X-Lara-Date" => gmdate("D, d M Y H:i:s") . " GMT",
            "X-Lara-SDK-Name" => "lara-php",
            "X-Lara-SDK-Version" => Version::get()
        ];

        if ($this->extraHeaders)
            $_headers = array_merge($_headers, $this->extraHeaders);

        if ($headers)
            $_headers = array_merge($_headers, $headers);

        if ($body) {
            $body = array_filter($body, function ($el) {
                return isset($el);
            });

            if (empty($body))
                $body = null;

            if ($body) {
                $jsonBody = json_encode($body);
                $_headers["Content-MD5"] = md5($jsonBody);
            }
        }

        $requestBody = [];

        if ($files) {
            $requestBody = $body ?: [];
            $_headers["Content-Type"] = "multipart/form-data";

            foreach (array_filter($files) as $key => $value) {
                if (!file_exists($value))
                    throw new LaraApiException(400, 'FileNotFound', "File $value not found");

                $requestBody[$key] = new CURLFile($value);
            }
        } else {
            $_headers["Content-Type"] = "application/json";
            if ($body) $requestBody = json_encode($body);
        }

        $_headers["Authorization"] = "Lara $this->accessKeyId:" . $this->sign($method, $path, $_headers);

        curl_reset($this->curl);
        curl_setopt_array($this->curl, [
            CURLOPT_URL => $url,
            CURLOPT_RETURNTRANSFER => 1,
            CURLOPT_HTTPHEADER => array_map(function ($key, $value) {
                return "$key: $value";
            }, array_keys($_headers), $_headers),
            CURLOPT_POST => 1,
            CURLOPT_POSTFIELDS => $requestBody
        ]);

        $result = curl_exec($this->curl);

        if ($result === false) {
            $curl_errno = curl_errno($this->curl);
            $timeout = $curl_errno == 28;

            if ($timeout)
                throw new LaraTimeoutException("Connection timed out ($curl_errno)", 500);
            else
                throw new LaraException("Unable to contact server ($curl_errno)", 500);
        }

        $statusCode = curl_getinfo($this->curl, CURLINFO_HTTP_CODE);
        $contentType = curl_getinfo($this->curl, CURLINFO_CONTENT_TYPE);
        $json = !str_starts_with($contentType, "text/csv") ? json_decode($result, true) : null;

        if (200 <= $statusCode && $statusCode < 300) {
            return str_starts_with($contentType, "text/csv")
                ? $result
                : ($json && isset($json['content']) ? $json['content'] : null);
        } else {
            $error = $json && isset($json['error']) ? $json['error'] : [];
            throw new LaraApiException(
                $statusCode,
                isset($error['type']) ? $error['type'] : 'UnknownError',
                isset($error['message']) ? $error['message'] : 'An unknown error occurred'
            );
        }
    }

    private function sign($method, $path, $headers)
    {
        $date = trim($headers["X-Lara-Date"]);
        $contentMD5 = trim(isset($headers["Content-MD5"]) ? $headers["Content-MD5"] : "");
        $contentType = trim(isset($headers["Content-Type"]) ? $headers["Content-Type"] : "");
        $httpMethod = strtoupper(trim(isset($headers["X-HTTP-Method-Override"]) ? $headers["X-HTTP-Method-Override"] : $method));

        $challenge = "$httpMethod\n$path\n$contentMD5\n$contentType\n$date";
        return base64_encode(hash_hmac('sha256', $challenge, $this->accessKeySecret, true));
    }

}
