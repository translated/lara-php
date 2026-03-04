<?php

namespace Lara;

class DetectResult implements \JsonSerializable
{
    /**
     * @param $response array
     * @return DetectResult
     */
    public static function fromResponse($response)
    {
        return new DetectResult(
            $response["language"],
            $response["content_type"],
            $response["predictions"] ?? []
        );
    }

    private $language;
    private $contentType;
    private $predictions;

    /**
     * @param $language string
     * @param $contentType string
     * @param $predictions array Array of predictions with 'language' and 'confidence' keys
     */
    public function __construct($language, $contentType, $predictions = [])
    {
        $this->language = $language;
        $this->contentType = $contentType;
        $this->predictions = $predictions;
    }

    /**
     * @return string
     */
    public function getLanguage() {
        return $this->language;
    }

    /**
     * @return string
     */
    public function getContentType()
    {
        return $this->contentType;
    }

    /**
     * @return array Array of predictions with 'language' and 'confidence' keys
     */
    public function getPredictions()
    {
        return $this->predictions;
    }

    #[\ReturnTypeWillChange]
    public function jsonSerialize()
    {
        return get_object_vars($this);
    }
}