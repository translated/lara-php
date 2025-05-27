<?php

namespace Lara\Internal;

class S3UploadParams
{
    /**
     * @param $response
     * @return S3UploadParams
     */
    public static function fromResponse($response)
    {
        return new S3UploadParams(
            $response['url'],
            $response['fields']
        );
    }

    private $url;
    private $fields;

    /**
     * @param $url string
     * @param $fields array
     */
    public function __construct($url, $fields) {
        $this->url = $url;
        $this->fields = $fields;
    }

    /**
     * @return string
     */
    public function getUrl() {
        return $this->url;
    }

    /**
     * @return array
     */
    public function getFields() {
        return $this->fields;
    }
}