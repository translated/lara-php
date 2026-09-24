<?php

namespace Lara;

class ImageTextTranslationOptions
{
    private $adaptTo = null;
    private $glossaries = null;
    private $style = null;
    private $noTrace = null;
    private $verbose = null;
    private $includeLayout = null;

    public function __construct($options = [])
    {
        if (isset($options['adaptTo']))
            $this->setAdaptTo($options['adaptTo']);
        if (isset($options['glossaries']))
            $this->setGlossaries($options['glossaries']);
        if (isset($options['style']))
            $this->setStyle($options['style']);
        if (isset($options['noTrace']))
            $this->setNoTrace($options['noTrace']);
        if (isset($options['verbose']))
            $this->setVerbose($options['verbose']);
        if (isset($options['includeLayout']))
            $this->setIncludeLayout($options['includeLayout']);
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
     * Request memory and glossary matches independently of layout metadata.
     * @param $verbose bool|null
     */
    public function setVerbose($verbose)
    {
        $this->verbose = $verbose;
    }

    /**
     * @return bool|null
     */
    public function isVerbose()
    {
        return $this->verbose;
    }

    /**
     * Include complete layout metadata on every returned paragraph when true.
     * @param $includeLayout bool|null
     */
    public function setIncludeLayout($includeLayout)
    {
        $this->includeLayout = $includeLayout;
    }

    /**
     * @return bool|null
     */
    public function getIncludeLayout()
    {
        return $this->includeLayout;
    }
}
