<?php

namespace Lara;

class LaraCredentials
{
    private $accessKeyId;
    private $accessKeySecret;

    /**
     * LaraCredentials constructor.
     * @param $accessKeyId string
     * @param $accessKeySecret string
     */
    public function __construct($accessKeyId, $accessKeySecret)
    {
        $this->accessKeyId = $accessKeyId;
        $this->accessKeySecret = $accessKeySecret;
    }

    public function getAccessKeyId()
    {
        return $this->accessKeyId;
    }

    public function getAccessKeySecret()
    {
        return $this->accessKeySecret;
    }

}