<?php

namespace Lara;

class TextBlock implements \JsonSerializable
{
    /**
     * @param $response array
     * @return TextBlock
     */
    public static function fromResponse($response)
    {
        return new TextBlock($response["text"], !!$response["translatable"]);
    }

    private $text;
    private $translatable;

    /**
     * @param $text string
     * @param $translatable bool
     */
    public function __construct($text, $translatable = true)
    {
        $this->text = $text;
        $this->translatable = $translatable;
    }

    /**
     * @return string
     */
    public function getText()
    {
        return $this->text;
    }

    /**
     * @return bool
     */
    public function isTranslatable()
    {
        return $this->translatable;
    }

    public function __toString()
    {
        return $this->text;
    }

    // Compatibility layer for PHP 8.1+
    #[\ReturnTypeWillChange]
    public function jsonSerialize()
    {
        return get_object_vars($this);
    }

}