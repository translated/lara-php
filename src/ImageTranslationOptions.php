<?php

namespace Lara;

class ImageTranslationOptions
{
    private $adaptTo = null;
    private $glossaries = null;
    private $style = null;
    private $textRemoval = null;
    private $noTrace = null;

    public function __construct($options = [])
    {
        if (isset($options['adaptTo']))
            $this->setAdaptTo($options['adaptTo']);
        if (isset($options['glossaries']))
            $this->setGlossaries($options['glossaries']);
        if (isset($options['style']))
            $this->setStyle($options['style']);
        if (isset($options['textRemoval']))
            $this->setTextRemoval($options['textRemoval']);
        if (isset($options['noTrace']))
            $this->setNoTrace($options['noTrace']);
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
     * @param $textRemoval string|null "overlay" or "inpainting"
     */
    public function setTextRemoval($textRemoval)
    {
        $this->textRemoval = $textRemoval;
    }

    /**
     * @return string|null
     */
    public function getTextRemoval()
    {
        return $this->textRemoval;
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
}
