<?php

namespace Lara;

use Lara\Internal\S3Client;
use Lara\Internal\S3DownloadParams;
use Lara\Internal\S3UploadParams;

class Documents
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
     * @param $filepath string path to the file to translate
     * @param $source string|null source language
     * @param $target string target language
     * @param $options DocumentUploadOptions|null
     * @return Document
     * @throws LaraException
     */
    public function upload($filepath, $source, $target, $options = null)
    {
        $filename = basename($filepath);

        $s3Upload = S3UploadParams::fromResponse($this->client->get('/v2/documents/upload-url', ['filename' => $filename]));
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

        return Document::fromResponse($this->client->post("/v2/documents", $data, null, $headers));
    }

    /**
     * @param $documentId string
     * @return Document
     * @throws LaraException
     */
    public function status($documentId)
    {
        return Document::fromResponse($this->client->get("/v2/documents/$documentId"));
    }

    /**
     * @param $documentId string
     * @param $options DocumentDownloadOptions|null
     * @return resource
     * @throws LaraException
     */
    public function download($documentId, $options = null)
    {
        $data = null;
        if ($options) {
            $data = [];
            foreach (array_filter($options->toParams()) as $key => $value) {
                $data[$key] = $value;
            }
        }

        $s3Url = S3DownloadParams::fromResponse($this->client->get("/v2/documents/$documentId/download-url", $data));

        return $this->s3Client->download($s3Url->getUrl());
    }

    /**
     * @param $filepath string
     * @param $source string
     * @param $target string
     * @param $options DocumentTranslateOptions|null
     * @return resource
     * @throws LaraException
     * @throws LaraTimeoutException
     */
    public function translate($filepath, $source, $target, $options = null)
    {
        $documentUploadOptions = new DocumentUploadOptions();
        if ($options !== null) {
            if ($options->getAdaptTo())
                $documentUploadOptions->setAdaptTo($options->getAdaptTo());
            if ($options->isNoTrace())
                $documentUploadOptions->setNoTrace($options->isNoTrace());
            if ($options->getGlossaries())
                $documentUploadOptions->setGlossaries($options->getGlossaries());
            if ($options->getStyle())
                $documentUploadOptions->setStyle($options->getStyle());
            if ($options->getPassword())
                $documentUploadOptions->setPassword($options->getPassword());
            if ($options->getExtractionParameters())
                $documentUploadOptions->setExtractionParameters($options->getExtractionParameters());
        }

        $documentDownloadOptions = new DocumentDownloadOptions();
        if ($options !== null) {
            if ($options->getOutputFormat())
                $documentDownloadOptions->setOutputFormat($options->getOutputFormat());
        }

        $document = $this->upload($filepath, $source, $target, $documentUploadOptions);

        $maxWaitTime = 900; // 15 minutes
        $startTime = time();
        while (time() - $startTime < $maxWaitTime) {
            sleep($this->pollingInterval);

            $document = $this->status($document->getId());

            if ($document->getStatus() === "translated") {
                return $this->download($document->getId(), $documentDownloadOptions);
            }
            if ($document->getStatus() === "error") {
                throw new LaraException("Document translation failed: " . $document->getErrorReason());
            }
        }

        throw new LaraTimeoutException();
    }
}
