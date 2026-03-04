<?php

namespace Lara;

class ImageTranslator
{
    /**
     * @var Internal\HttpClient
     */
    private $client;

    /**
     * @param $client Internal\HttpClient
     */
    public function __construct($client)
    {
        $this->client = $client;
    }

    /**
     * Translate an image, returning a stream of the translated image
     *
     * @param $filePath string path to the image file
     * @param $source string|null source language
     * @param $target string target language
     * @param $options ImageTranslationOptions|null
     * @return resource stream of the translated image
     * @throws LaraException
     */
    public function translate($filePath, $source, $target, $options = null)
    {
        $data = ["target" => $target];
        $headers = [];

        if ($source) $data["source"] = $source;

        if ($options) {
            if ($options->getAdaptTo() !== null) $data["adapt_to"] = $options->getAdaptTo();
            if ($options->getGlossaries() !== null) $data["glossaries"] = $options->getGlossaries();
            if ($options->getStyle() !== null) $data["style"] = $options->getStyle();
            if ($options->getTextRemoval() !== null) $data["text_removal"] = $options->getTextRemoval();
            if ($options->isNoTrace()) $headers["X-No-Trace"] = "true";
        }

        $files = ["image" => $filePath];

        return $this->client->postStream("/v2/images/translate", $data, $files, $headers);
    }

    /**
     * Extract and translate text from an image
     *
     * @param $filePath string path to the image file
     * @param $source string|null source language
     * @param $target string target language
     * @param $options ImageTextTranslationOptions|null
     * @return ImageTextResult
     * @throws LaraException
     */
    public function translateText($filePath, $source, $target, $options = null)
    {
        $data = ["target" => $target];
        $headers = [];

        if ($source) $data["source"] = $source;

        if ($options) {
            if ($options->getAdaptTo() !== null) $data["adapt_to"] = $options->getAdaptTo();
            if ($options->getGlossaries() !== null) $data["glossaries"] = $options->getGlossaries();
            if ($options->getStyle() !== null) $data["style"] = $options->getStyle();
            if ($options->isNoTrace()) $headers["X-No-Trace"] = "true";
        }

        $files = ["image" => $filePath];

        return ImageTextResult::fromResponse($this->client->post("/v2/images/translate-text", $data, $files, $headers));
    }
}
