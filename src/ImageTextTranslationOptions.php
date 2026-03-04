<?php

namespace Lara;

class ImageTextTranslationOptions
{
    private $adaptTo = null;
    private $glossaries = null;
    private $style = null;
    private $noTrace = null;

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
}
