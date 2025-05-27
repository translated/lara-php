<?php

namespace Lara\Internal;

class S3DownloadParams
{
    public static function fromResponse($response)
    {
        return new S3DownloadParams($response["url"]);
    }

    private $url;

    public function __construct($url)
    {
        $this->url = $url;
    }

    public function getUrl() {
        return $this->url;
    }

    public function setUrl($url) {
        $this->url = $url;
    }
}