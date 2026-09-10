<?php

namespace Lara;

/** Options for importing a glossary file. */
class GlossaryImportOptions
{
    private $contentType = GlossaryFileFormat::CSV_TABLE_UNI;
    private $gzip = false;
    private $callbackUrl = null;

    public function __construct($options = [])
    {
        if (isset($options['contentType']))
            $this->setContentType($options['contentType']);
        if (isset($options['gzip']))
            $this->setGzip($options['gzip']);
        if (isset($options['callbackUrl']))
            $this->setCallbackUrl($options['callbackUrl']);
    }

    public function getContentType()
    {
        return $this->contentType;
    }

    public function setContentType($contentType)
    {
        $this->contentType = $contentType;
    }

    /** Whether the supplied file is already gzip-compressed. */
    public function getGzip()
    {
        return $this->gzip;
    }

    public function setGzip($gzip)
    {
        $this->gzip = $gzip;
    }

    public function getCallbackUrl()
    {
        return $this->callbackUrl;
    }

    public function setCallbackUrl($callbackUrl)
    {
        $this->callbackUrl = $callbackUrl;
    }
}
