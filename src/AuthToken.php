<?php

namespace Lara;

class AuthToken
{
    private $token;
    private $refreshToken;

    public function __construct($token, $refreshToken = null)
    {
        $this->token = $token;
        $this->refreshToken = $refreshToken;
    }

    public function getToken()
    {
        return $this->token;
    }

    public function getRefreshToken()
    {
        return $this->refreshToken;
    }

    public function setToken($token)
    {
        $this->token = $token;
    }

    public function setRefreshToken($refreshToken)
    {
        $this->refreshToken = $refreshToken;
    }

    /**
     * Clear the access token (used when token expires and needs refresh)
     * @return void
     */
    public function clearToken()
    {
        $this->token = null;
    }

    /**
     * Create AuthToken from API response
     * @param array $response API response containing token in body and refresh_token in headers
     * @return AuthToken
     */
    public static function fromResponse($response)
    {
        $token = isset($response['body']['token']) ? $response['body']['token'] : null;
        $refreshToken = isset($response['headers']['x-lara-refresh-token']) ? $response['headers']['x-lara-refresh-token'] : null;

        return new AuthToken($token, $refreshToken);
    }
}
