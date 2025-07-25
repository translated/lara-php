<?php

namespace Lara;

class DocumentTranslateOptions
{
    private $adaptTo = null;
    private $outputFormat = null;
    private $noTrace = null;
    private $glossaries = null;
    private $style = null;

    public function __construct($options = [])
    {
        if (isset($options['adaptTo']))
            $this->setAdaptTo($options['adaptTo']);
        if (isset($options['outputFormat']))
            $this->setOutputFormat($options['outputFormat']);
        if (isset($options['noTrace']))
            $this->setNoTrace($options['noTrace']);
        if (isset($options['glossaries']))
            $this->setGlossaries($options['glossaries']);
        if (isset($options['style']))
            $this->setStyle($options['style']);
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
     * @param $outputFormat string|null
     */
    public function setOutputFormat($outputFormat)
    {
        $this->outputFormat = $outputFormat;
    }

    /**
     * @return string|null
     */
    public function getOutputFormat()
    {
        return $this->outputFormat;
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
     * @return array
     */
    public function toParams() {
        $params = [];
        if ($this->adaptTo) {
            $params['adapt_to'] = $this->adaptTo;
        }
        if ($this->outputFormat) {
            $params['output_format'] = $this->outputFormat;
        }
        if ($this->glossaries) {
            $params['glossaries'] = $this->glossaries;
        }
        if ($this->style) {
            $params['style'] = $this->style;
        }
        return $params;
    }
}