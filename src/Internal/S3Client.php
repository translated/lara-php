<?php

namespace Lara\Internal;

use CURLFile;
use Lara\LaraApiException;

class S3Client
{

    private $curl;

    public function __construct()
    {
        $this->curl = curl_init();
    }

    public function __destruct()
    {
        curl_close($this->curl);
    }

    public function upload($url, $fields, $file) {
        if (!file_exists($file)) {
            throw new LaraApiException(400, 'FileNotFound', "File $file not found");
        }
        $headers = [
            "Content-Type" => "multipart/form-data"
        ];
        $requestBody = [];
        foreach (array_filter($fields) as $key => $value) {
            $requestBody[$key] = $value;
        }
        $requestBody["file"] = new CURLFile($file);

        curl_reset($this->curl);
        curl_setopt_array($this->curl, [
            CURLOPT_URL => $url,
            CURLOPT_RETURNTRANSFER => 1,
            CURLOPT_HTTPHEADER => array_map(function ($key, $value) {
                return "$key: $value";
            }, array_keys($headers), $headers),
            CURLOPT_POST => 1,
            CURLOPT_POSTFIELDS => $requestBody
        ]);

        curl_exec($this->curl);
    }

    /**
     * @param $url string
     * @return resource
     */
    public function download($url) {
        curl_reset($this->curl);
        curl_setopt_array($this->curl, [
            CURLOPT_URL => $url,
            CURLOPT_RETURNTRANSFER => false,
            CURLOPT_HTTPHEADER => [
                "Content-Type: application/json"
            ]
        ]);
        $stream = fopen('php://temp', 'w+');
        curl_setopt($this->curl, CURLOPT_FILE, $stream);
        curl_exec($this->curl);
        rewind($stream);
        return $stream;
    }
}
