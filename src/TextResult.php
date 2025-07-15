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

        return new TextResult(
            $response["content_type"],
            $response["source_language"],
            $translation,
            isset($response["adapted_to"]) ? $response["adapted_to"] : null,
            isset($response["glossaries"]) ? $response["glossaries"] : null,
            isset($response["adapted_to_matches"]) ? $response["adapted_to_matches"] : null,
            isset($response["glossaries_matches"]) ? $response["glossaries_matches"] : null
        );
    }

    private $contentType;
    private $sourceLanguage;
    private $translation;
    private $adaptedTo;
    private $glossaries;
    private $adaptedToMatches;
    private $glossariesMatches;

    /**
     * @param $contentType string
     * @param $sourceLanguage string
     * @param $translation string|string[]|TextBlock[]
     * @param $adaptedTo string[]|null
     * @param $glossaries string[]|null
     * @param $adaptedToMatches NGMemoryMatch[]|NGMemoryMatch[][]|null
     * @param $glossariesMatches NGGlossaryMatch[]|NGGlossaryMatch[][]|null
     */
    public function __construct($contentType, $sourceLanguage, $translation, $adaptedTo = null, $glossaries = null, $adaptedToMatches = null, $glossariesMatches = null)
    {
        $this->contentType = $contentType;
        $this->sourceLanguage = $sourceLanguage;
        $this->translation = $translation;
        $this->adaptedTo = $adaptedTo;
        $this->glossaries = $glossaries;
        $this->adaptedToMatches = $adaptedToMatches;
        $this->glossariesMatches = $glossariesMatches;
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


    // Compatibility layer for PHP 8.1+
    #[\ReturnTypeWillChange]
    public function jsonSerialize()
    {
        return get_object_vars($this);
    }

}