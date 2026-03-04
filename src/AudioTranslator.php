<?php

namespace Lara;

use Lara\Internal\S3Client;
use Lara\Internal\S3DownloadParams;
use Lara\Internal\S3UploadParams;

class AudioTranslator
{
    /**
     * @var Internal\HttpClient
     */
    private $client;
    private $s3Client;
    private $pollingInterval = 2;

    /**
     * @param $client Internal\HttpClient
     */
    public function __construct($client)
    {
        $this->client = $client;
        $this->s3Client = new S3Client();
    }

    /**
     * @param $filepath string path to the audio file to translate
     * @param $source string|null source language
     * @param $target string target language
     * @param $options AudioTranslateOptions|null
     * @return Audio
     * @throws LaraException
     */
    public function upload($filepath, $source, $target, $options = null)
    {
        $filename = basename($filepath);

        $s3Upload = S3UploadParams::fromResponse($this->client->get('/v2/audio/upload-url', ['filename' => $filename]));

        $this->s3Client->upload($s3Upload->getUrl(), $s3Upload->getFields(), $filepath);

        $data = [
            "source" => $source,
            "target" => $target,
            "s3key" => $s3Upload->getFields()['key']
        ];
        $headers = [];

        if ($options) {
            foreach (array_filter($options->toParams()) as $key => $value) {
                $data[$key] = $value;
            }

            if ($options->isNoTrace()) {
                $headers['X-No-Trace'] = 'true';
            }
        }

        return Audio::fromResponse($this->client->post("/v2/audio/translate", $data, null, $headers));
    }

    /**
     * @param $id string
     * @return Audio
     * @throws LaraException
     */
    public function status($id)
    {
        return Audio::fromResponse($this->client->get("/v2/audio/$id"));
    }

    /**
     * @param $id string
     * @return resource
     * @throws LaraException
     */
    public function download($id)
    {
        $s3Url = S3DownloadParams::fromResponse($this->client->get("/v2/audio/$id/download-url"));

        return $this->s3Client->download($s3Url->getUrl());
    }

    /**
     * @param $filepath string
     * @param $source string
     * @param $target string
     * @param $options AudioTranslateOptions|null
     * @return resource
     * @throws LaraException
     * @throws LaraTimeoutException
     */
    public function translate($filepath, $source, $target, $options = null)
    {
        $audio = $this->upload($filepath, $source, $target, $options);

        $maxWaitTime = 900; // 15 minutes
        $startTime = time();
        while (time() - $startTime < $maxWaitTime) {
            sleep($this->pollingInterval);

            $audio = $this->status($audio->getId());

            if ($audio->getStatus() === AudioStatus::TRANSLATED) {
                return $this->download($audio->getId());
            }
            if ($audio->getStatus() === AudioStatus::ERROR) {
                throw new LaraApiException(500, "AudioError", $audio->getErrorReason());
            }
        }

        throw new LaraTimeoutException();
    }
}
