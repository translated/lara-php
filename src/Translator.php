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
     * @var ImageTranslator
     */
    public $images;

    /**
     * @var AudioTranslator
     */
    public $audio;

    /**
     * @param $auth AccessKey|AuthToken
     * @param $options TranslatorOptions | null
     * @throws LaraException
     */
    public function __construct($auth, $options = null)
    {
        $serverUrl = $options ? $options->getServerUrl() : null;
        $serverUrl = $serverUrl ?: 'https://api.laratranslate.com';

        $this->client = new Internal\HttpClient($serverUrl, $auth);

        $this->memories = new Memories($this->client);
        $this->documents = new Documents($this->client);
        $this->glossaries = new Glossaries($this->client);
        $this->audio = new AudioTranslator($this->client);
        $this->images = new ImageTranslator($this->client);
    }

    /**
     * Gets the HTTP client instance for advanced configuration (e.g., setting logger or extra headers)
     * @return Internal\HttpClient
     */
    public function getHttpClient()
    {
        return $this->client;
    }

    /**
     * @param $options TranslatorOptions | null
     * @return string Login page URL
     */
    public static function getLoginUrl($options = null)
    {
        $serverUrl = $options ? $options->getServerUrl() : 'https://api.laratranslate.com';
        $ch = curl_init($serverUrl . '/v2/auth/login-page');
        curl_setopt($ch, CURLOPT_RETURNTRANSFER, true);
        $result = curl_exec($ch);
        curl_close($ch);
        return $result;
    }

    /**
     * @return string[]
     * @throws LaraException
     */
    public function getLanguages()
    {
        return $this->client->get("/v2/languages");
    }

    /**
     * @param $text string|string[]|TextBlock[]
     * @param $source string|null
     * @param $target string
     * @param $options TranslateOptions | null
     * @param $callback callable|null Callback for streaming partial results (result) => void
     * @return TextResult
     * @throws LaraException
     */
    public function translate($text, $source, $target, $options = null, $callback = null)
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
            if ($options->isReasoning() !== null) $data["reasoning"] = $options->isReasoning();
            if ($options->getMetadata() !== null) $data["metadata"] = $options->getMetadata();
            if ($options->getProfanityFilter() !== null) $data["profanity_filter"] = $options->getProfanityFilter();

            if ($options->getHeaders() !== null) {
                foreach ($options->getHeaders() as $name => $value) {
                    $headers[$name] = $value;
                }
            }

            if ($options->isNoTrace() !== null) $headers["X-No-Trace"] = "true";
        }

        $wrappedCallback = null;
        if ($callback !== null) {
            $wrappedCallback = function ($chunk) use ($callback) {
                call_user_func($callback, TextResult::fromResponse($chunk));
            };
        }

        return TextResult::fromResponse($this->client->postStream("/translate", $data, null, $headers, $wrappedCallback));
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

        return DetectResult::fromResponse($this->client->post("/v2/detect/language", $data));
    }

    /**
     * @param $text string
     * @param $language string
     * @param $contentType string
     * @return ProfanityDetectResult
     * @throws LaraException
     */
    public function detectProfanities($text, $language, $contentType)
    {
        $data = [
            "text" => $text,
            "language" => $language,
            "content_type" => $contentType,
        ];

        return ProfanityDetectResult::fromResponse($this->client->post("/v2/detect/profanities", $data));
    }

}