<?php

namespace Lara;

class TranslateOptions
{
    private $sourceHint = null;
    private $adaptTo = null;
    private $instructions = null;
    private $glossaries = null;
    private $contentType = null;
    private $multiline = null;
    private $timeoutInMillis = null;
    private $priority = null;
    private $useCache = null;
    private $cacheTTLSeconds = null;
    private $noTrace = null;
    private $verbose = null;
    private $style = null;
    private $headers = null;

    public function __construct($options = [])
    {
        if (isset($options['sourceHint']))
            $this->setSourceHint($options['sourceHint']);
        if (isset($options['adaptTo']))
            $this->setAdaptTo($options['adaptTo']);
        if (isset($options['instructions']))
            $this->setInstructions($options['instructions']);
        if (isset($options['glossaries']))
            $this->setGlossaries($options['glossaries']);
        if (isset($options['contentType']))
            $this->setContentType($options['contentType']);
        if (isset($options['multiline']))
            $this->setMultiline($options['multiline']);
        if (isset($options['timeoutInMillis']))
            $this->setTimeoutInMillis($options['timeoutInMillis']);
        if (isset($options['priority']))
            $this->setPriority($options['priority']);
        if (isset($options['useCache']))
            $this->setUseCache($options['useCache']);
        if (isset($options['cacheTTLSeconds']))
            $this->setCacheTTLSeconds($options['cacheTTLSeconds']);
        if (isset($options['noTrace']))
            $this->setNoTrace($options['noTrace']);
        if (isset($options['verbose']))
            $this->setVerbose($options['verbose']);
        if (isset($options['style']))
            $this->setStyle($options['style']);
        if (isset($options['headers']))
            $this->setHeaders($options['headers']);

    }

    /**
     * @param $sourceHint string|null
     */
    public function setSourceHint($sourceHint)
    {
        $this->sourceHint = $sourceHint;
    }

    /**
     * @return string|null
     */
    public function getSourceHint()
    {
        return $this->sourceHint;
    }

    /**
     * @param $adaptTo string[]|null
     */
    public function setAdaptTo($adaptTo)
    {
        $this->adaptTo = $adaptTo;
    }

    /**
     * @return string[]|null
     */
    public function getAdaptTo()
    {
        return $this->adaptTo;
    }

    /**
     * @param $instructions string[]|null
     */
    public function setInstructions(array $instructions)
    {
        $this->instructions = $instructions;
    }

    /**
     * @return string[]|null
     */
    public function getInstructions()
    {
        return $this->instructions;
    }

    /**
     * @param $glossaries string[]|null
     */
    public function setGlossaries($glossaries)
    {
        $this->glossaries = $glossaries;
    }

    /**
     * @return string[]|null
     */
    public function getGlossaries()
    {
        return $this->glossaries;
    }

    /**
     * @param $contentType string|null
     */
    public function setContentType($contentType)
    {
        $this->contentType = $contentType;
    }

    /**
     * @return string|null
     */
    public function getContentType()
    {
        return $this->contentType;
    }

    /**
     * @param $multiline bool|null
     */
    public function setMultiline($multiline)
    {
        $this->multiline = $multiline;
    }

    /**
     * @return bool|null
     */
    public function isMultiline()
    {
        return $this->multiline;
    }

    /**
     * @param $timeoutInMillis int|null
     */
    public function setTimeoutInMillis($timeoutInMillis)
    {
        $this->timeoutInMillis = $timeoutInMillis;
    }

    /**
     * @return int|null
     */
    public function getTimeoutInMillis()
    {
        return $this->timeoutInMillis;
    }

    /**
     * @param $priority string|null
     */
    public function setPriority($priority)
    {
        $this->priority = $priority;
    }

    /**
     * @return string|null
     */
    public function getPriority()
    {
        return $this->priority;
    }

    /**
     * @param $useCache bool|string|null
     */
    public function setUseCache($useCache)
    {
        $this->useCache = $useCache;
    }

    /**
     * @return bool|string|null
     */
    public function getUseCache()
    {
        return $this->useCache;
    }

    /**
     * @param $cacheTTLSeconds int|null
     */
    public function setCacheTTLSeconds($cacheTTLSeconds)
    {
        $this->cacheTTLSeconds = $cacheTTLSeconds;
    }

    /**
     * @return int|null
     */
    public function getCacheTTLSeconds()
    {
        return $this->cacheTTLSeconds;
    }

    /**
     * @param $noTrace bool|null
     */
    public function setNoTrace($noTrace)
    {
        $this->noTrace = $noTrace;
    }

    /**
     * @return bool|null
     */
    public function isNoTrace()
    {
        return $this->noTrace;
    }

    /**
     * @param $verbose bool|null
     */
    public function setVerbose($verbose) {
        $this->verbose = $verbose;
    }

    /**
     * @return bool|null
     */
    public function isVerbose() {
        return $this->verbose;
    }

    /**
     * @param $style string|null
     */
    public function setStyle($style)
    {
        $this->style = $style;
    }

    /**
     * @return string|null
     */
    public function getStyle()
    {
        return $this->style;
    }

    /**
     * @param $headers array|null
     */
    public function setHeaders($headers) {
        $this->headers = $headers;
    }

    /**
     * @return array|null
     */
    public function getHeaders() {
        return $this->headers;
    }
}
