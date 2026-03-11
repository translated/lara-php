<?php

namespace Lara;

class AudioTranslateOptions
{
    private $adaptTo = null;
    private $noTrace = null;
    private $glossaries = null;
    private $style = null;
    private $voiceGender = null;
    public function __construct($options = [])
    {
        if (isset($options['adaptTo']))
            $this->setAdaptTo($options['adaptTo']);
        if (isset($options['noTrace']))
            $this->setNoTrace($options['noTrace']);
        if (isset($options['glossaries']))
            $this->setGlossaries($options['glossaries']);
        if (isset($options['style']))
            $this->setStyle($options['style']);
        if (isset($options['voiceGender']))
            $this->setVoiceGender($options['voiceGender']);
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
     * @param $voiceGender string|null
     */
    public function setVoiceGender($voiceGender)
    {
        $this->voiceGender = $voiceGender;
    }

    /**
     * @return string|null
     */
    public function getVoiceGender()
    {
        return $this->voiceGender;
    }

    /**
     * @return array
     */
    public function toParams() {
        $params = [];
        if ($this->adaptTo) {
            $params['adapt_to'] = $this->adaptTo;
        }
        if ($this->glossaries) {
            $params['glossaries'] = $this->glossaries;
        }
        if ($this->style) {
            $params['style'] = $this->style;
        }
        if ($this->voiceGender) {
            $params['voice_gender'] = $this->voiceGender;
        }
        return $params;
    }
}
