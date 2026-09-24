<?php

namespace Lara;

/** Text direction and colors used when rendering an image paragraph. */
class ImageTextInfo implements \JsonSerializable
{
    private $direction;
    private $textColor;
    private $backgroundColor;

    /**
     * @param $response array
     * @return ImageTextInfo
     */
    public static function fromResponse($response)
    {
        return new ImageTextInfo(
            $response['direction'],
            $response['text_color'],
            $response['background_color']
        );
    }

    /**
     * @param $direction string "ltr", "rtl" or "ttb"
     * @param $textColor string
     * @param $backgroundColor string
     */
    public function __construct($direction, $textColor, $backgroundColor)
    {
        $this->direction = $direction;
        $this->textColor = $textColor;
        $this->backgroundColor = $backgroundColor;
    }

    /** @return string */
    public function getDirection()
    {
        return $this->direction;
    }

    /** @return string */
    public function getTextColor()
    {
        return $this->textColor;
    }

    /** @return string */
    public function getBackgroundColor()
    {
        return $this->backgroundColor;
    }

    // Compatibility layer for PHP 8.1+
    #[\ReturnTypeWillChange]
    public function jsonSerialize()
    {
        return [
            'direction' => $this->direction,
            'text_color' => $this->textColor,
            'background_color' => $this->backgroundColor
        ];
    }
}
