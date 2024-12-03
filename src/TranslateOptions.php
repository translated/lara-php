<?php

namespace Lara;

class TranslateOptions
{
    private $sourceHint = null;
    private $adaptTo = null;
    private $instructions = null;
    private $contentType = null;
    private $multiline = null;
    private $timeoutInMillis = null;
    private $priority = null;
    private $useCache = null;
    private $cacheTTLSeconds = null;

    public function __construct($options = [])
    {
        if (isset($options['sourceHint']))
            $this->setSourceHint($options['sourceHint']);
        if (isset($options['adaptTo']))
            $this->setAdaptTo($options['adaptTo']);
        if (isset($options['instructions']))
            $this->setInstructions($options['instructions']);
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

}
