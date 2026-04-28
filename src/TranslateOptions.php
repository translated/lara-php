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
    private $reasoning = null;
    private $metadata = null;
    private $profanitiesDetect = null;
    private $profanitiesHandling = null;
    private $styleguideId = null;
    private $styleguideReasoning = null;
    private $styleguideExplanationLanguage = null;

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
        if (isset($options['reasoning']))
            $this->setReasoning($options['reasoning']);
        if (isset($options['metadata']))
            $this->setMetadata($options['metadata']);
        if (isset($options['profanitiesDetect']))
            $this->setProfanitiesDetect($options['profanitiesDetect']);
        if (isset($options['profanitiesHandling']))
            $this->setProfanitiesHandling($options['profanitiesHandling']);
        if (isset($options['styleguideId']))
            $this->setStyleguideId($options['styleguideId']);
        if (isset($options['styleguideReasoning']))
            $this->setStyleguideReasoning($options['styleguideReasoning']);
        if (isset($options['styleguideExplanationLanguage']))
            $this->setStyleguideExplanationLanguage($options['styleguideExplanationLanguage']);

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

    /**
     * @param $reasoning bool|null
     */
    public function setReasoning($reasoning)
    {
        $this->reasoning = $reasoning;
    }

    /**
     * @return bool|null
     */
    public function isReasoning()
    {
        return $this->reasoning;
    }

    /**
     * @param $metadata string|array|null
     */
    public function setMetadata($metadata)
    {
        $this->metadata = $metadata;
    }

    /**
     * @return string|array|null
     */
    public function getMetadata()
    {
        return $this->metadata;
    }

    /**
     * @param $profanitiesDetect string|null "target" or "source_target"
     */
    public function setProfanitiesDetect($profanitiesDetect)
    {
        $this->profanitiesDetect = $profanitiesDetect;
    }

    /**
     * @return string|null
     */
    public function getProfanitiesDetect()
    {
        return $this->profanitiesDetect;
    }

    /**
     * @param $profanitiesHandling string|null "hide", "avoid", or "detect"
     */
    public function setProfanitiesHandling($profanitiesHandling)
    {
        $this->profanitiesHandling = $profanitiesHandling;
    }

    /**
     * @return string|null
     */
    public function getProfanitiesHandling()
    {
        return $this->profanitiesHandling;
    }

    /**
     * @param $styleguideId string|null
     */
    public function setStyleguideId($styleguideId)
    {
        $this->styleguideId = $styleguideId;
    }

    /**
     * @return string|null
     */
    public function getStyleguideId()
    {
        return $this->styleguideId;
    }

    /**
     * @param $styleguideReasoning bool|null
     */
    public function setStyleguideReasoning($styleguideReasoning)
    {
        $this->styleguideReasoning = $styleguideReasoning;
    }

    /**
     * @return bool|null
     */
    public function isStyleguideReasoning()
    {
        return $this->styleguideReasoning;
    }

    /**
     * @param $styleguideExplanationLanguage string|null
     */
    public function setStyleguideExplanationLanguage($styleguideExplanationLanguage)
    {
        $this->styleguideExplanationLanguage = $styleguideExplanationLanguage;
    }

    /**
     * @return string|null
     */
    public function getStyleguideExplanationLanguage()
    {
        return $this->styleguideExplanationLanguage;
    }
}
