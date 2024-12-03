<?php

namespace Lara;

class Translator
{
    /**
     * @var Internal\HttpClient
     */
    private $client;

    /**
     * @var Memories
     */
    public $memories;

    /**
     * @param $credentials LaraCredentials
     * @param $options TranslatorOptions | null
     */
    public function __construct($credentials, $options = null)
    {
        $serverUrl = $options ? $options->getServerUrl() : null;
        $serverUrl = $serverUrl ?: 'https://api.laratranslate.com';

        $this->client = new Internal\HttpClient($serverUrl, $credentials->getAccessKeyId(), $credentials->getAccessKeySecret());
        $this->memories = new Memories($this->client);
    }

    /**
     * @return string[]
     * @throws LaraException
     */
    public function getLanguages()
    {
        return $this->client->get("/languages");
    }

    /**
     * @param $text string|string[]|TextBlock[]
     * @param $source string|null
     * @param $target string
     * @param $options TranslateOptions | null
     * @return TextResult
     * @throws LaraException
     */
    public function translate($text, $source, $target, $options = null)
    {
        $data = ["q" => $text, "target" => $target];

        if ($source) $data["source"] = $source;

        if ($options) {
            if ($options->getSourceHint() !== null) $data["source_hint"] = $options->getSourceHint();
            if ($options->getContentType() !== null) $data["content_type"] = $options->getContentType();
            if ($options->isMultiline() !== null) $data["multiline"] = $options->isMultiline();
            if ($options->getAdaptTo() !== null) $data["adapt_to"] = $options->getAdaptTo();
            if ($options->getInstructions() !== null) $data["instructions"] = $options->getInstructions();
            if ($options->getTimeoutInMillis() !== null) $data["timeout"] = $options->getTimeoutInMillis();
            if ($options->getPriority() !== null) $data["priority"] = $options->getPriority();
            if ($options->getUseCache() !== null) $data["use_cache"] = $options->getUseCache();
            if ($options->getCacheTTLSeconds() !== null) $data["cache_ttl"] = $options->getCacheTTLSeconds();
        }

        return TextResult::fromResponse($this->client->post("/translate", $data));
    }

}