<?php

namespace Lara;

/** An HTTPS URL or base64 data URL containing a PNG, JPEG or WebP image. */
class ImageContext implements \JsonSerializable
{
    private $url;

    public function __construct($url)
    {
        $this->url = $url;
    }

    public function getUrl()
    {
        return $this->url;
    }

    #[\ReturnTypeWillChange]
    public function jsonSerialize()
    {
        return ['url' => $this->url];
    }
}
