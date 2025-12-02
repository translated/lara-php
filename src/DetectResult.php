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
            $response["content_type"]
        );
    }

    private $language;
    private $contentType;

    /**
     * @param $language string
     * @param $contentType string
     */
    public function __construct($language, $contentType)
    {
        $this->language = $language;
        $this->contentType = $contentType;
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

    #[\ReturnTypeWillChange]
    public function jsonSerialize()
    {
        return get_object_vars($this);
    }
}