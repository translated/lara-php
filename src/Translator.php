<?php

namespace Lara;

class Translator
{
    /**
     * @var Internal\HttpClient
     */
    protected $client;

    /**
     * @var Memories
     */
    public $memories;

    /**
     * @var Documents
     */
    public $documents;

    /**
     * @var Glossaries
     */
    public $glossaries;

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
        $this->documents = new Documents($this->client);
        $this->glossaries = new Glossaries($this->client);
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
        $headers = [];

        if ($source) $data["source"] = $source;

        if ($options) {
            if ($options->getSourceHint() !== null) $data["source_hint"] = $options->getSourceHint();
            if ($options->getContentType() !== null) $data["content_type"] = $options->getContentType();
            if ($options->isMultiline() !== null) $data["multiline"] = $options->isMultiline();
            if ($options->getAdaptTo() !== null) $data["adapt_to"] = $options->getAdaptTo();
            if ($options->getInstructions() !== null) $data["instructions"] = $options->getInstructions();
            if ($options->getGlossaries() !== null) $data["glossaries"] = $options->getGlossaries();
            if ($options->getTimeoutInMillis() !== null) $data["timeout"] = $options->getTimeoutInMillis();
            if ($options->getPriority() !== null) $data["priority"] = $options->getPriority();
            if ($options->getUseCache() !== null) $data["use_cache"] = $options->getUseCache();
            if ($options->getCacheTTLSeconds() !== null) $data["cache_ttl"] = $options->getCacheTTLSeconds();
            if ($options->isVerbose() !== null) $data["verbose"] = $options->isVerbose();
            if ($options->getStyle() !== null) $data["style"] = $options->getStyle();

            if ($options->getHeaders() !== null) {
                foreach ($options->getHeaders() as $name => $value) {
                    $headers[$name] = $value;
                }
            }

            if ($options->isNoTrace() !== null) $headers["X-No-Trace"] = "true";
        }


        return TextResult::fromResponse($this->client->post("/translate", $data, null, $headers));
    }

    /**
     * @param $text string
     * @param $hint string|null
     * @param $passlist string[]|null
     * @return DetectResult
     * @throws LaraException
     */
    public function detect($text, $hint = null, $passlist = null)
    {
        $data = ["q" => $text];
        if ($hint) $data["hint"] = $hint;
        if ($passlist) $data["passlist"] = $passlist;

        return DetectResult::fromResponse($this->client->post("/detect", $data));
    }

}