<?php

namespace Lara;

class TextResult implements \JsonSerializable
{
    /**
     * @param $response array
     * @return TextResult
     */
    public static function fromResponse($response)
    {
        $translation = $response["translation"];

        if (is_array($translation)) {
            $translation = array_map(function ($e) {
                if (is_string($e))
                    return $e;
                else
                    return TextBlock::fromResponse($e);
            }, $translation);
        }

        $profanities = null;
        if (isset($response["profanities"])) {
            $raw = $response["profanities"];
            if (is_array($raw) && !empty($raw)) {
                if (isset($raw['masked_text'])) {
                    $profanities = ProfanityDetectResult::fromResponse($raw);
                } else {
                    $profanities = array_map(function ($item) {
                        return $item !== null ? ProfanityDetectResult::fromResponse($item) : null;
                    }, $raw);
                }
            }
        }

        return new TextResult(
            $response["content_type"],
            $response["source_language"],
            $translation,
            isset($response["adapted_to"]) ? $response["adapted_to"] : null,
            isset($response["glossaries"]) ? $response["glossaries"] : null,
            isset($response["adapted_to_matches"]) ? $response["adapted_to_matches"] : null,
            isset($response["glossaries_matches"]) ? $response["glossaries_matches"] : null,
            $profanities
        );
    }

    private $contentType;
    private $sourceLanguage;
    private $translation;
    private $adaptedTo;
    private $glossaries;
    private $adaptedToMatches;
    private $glossariesMatches;
    private $profanities;

    /**
     * @param $contentType string
     * @param $sourceLanguage string
     * @param $translation string|string[]|TextBlock[]
     * @param $adaptedTo string[]|null
     * @param $glossaries string[]|null
     * @param $adaptedToMatches NGMemoryMatch[]|NGMemoryMatch[][]|null
     * @param $glossariesMatches NGGlossaryMatch[]|NGGlossaryMatch[][]|null
     * @param $profanities ProfanityDetectResult|ProfanityDetectResult[]|null
     */
    public function __construct($contentType, $sourceLanguage, $translation, $adaptedTo = null, $glossaries = null, $adaptedToMatches = null, $glossariesMatches = null, $profanities = null)
    {
        $this->contentType = $contentType;
        $this->sourceLanguage = $sourceLanguage;
        $this->translation = $translation;
        $this->adaptedTo = $adaptedTo;
        $this->glossaries = $glossaries;
        $this->adaptedToMatches = $adaptedToMatches;
        $this->glossariesMatches = $glossariesMatches;
        $this->profanities = $profanities;
    }

    /**
     * @return string
     */
    public function getContentType()
    {
        return $this->contentType;
    }

    /**
     * @return string
     */
    public function getSourceLanguage()
    {
        return $this->sourceLanguage;
    }

    /**
     * @return string|string[]|TextBlock[]
     */
    public function getTranslation()
    {
        return $this->translation;
    }

    /**
     * @return string[]|null
     */
    public function getAdaptedTo()
    {
        return $this->adaptedTo;
    }

    /**
     * @return string[]|null
     */
    public function getGlossaries()
    {
        return $this->glossaries;
    }

    /**
     * @return NGMemoryMatch[]|NGMemoryMatch[][]|null
     */
    public function getAdaptedToMatches()
    {
        return $this->adaptedToMatches;
    }

    /**
     * @return mixed|null
     */
    public function getGlossariesMatches()
    {
        return $this->glossariesMatches;
    }

    /**
     * @return ProfanityDetectResult|ProfanityDetectResult[]|null
     */
    public function getProfanities()
    {
        return $this->profanities;
    }

    // Compatibility layer for PHP 8.1+
    #[\ReturnTypeWillChange]
    public function jsonSerialize()
    {
        return get_object_vars($this);
    }

}