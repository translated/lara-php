<?php

namespace Lara;

class DocumentUploadOptions
{
    private $adaptTo = null;
    private $noTrace = null;

    public function __construct($options = [])
    {
        if (isset($options['adaptTo']))
            $this->setAdaptTo($options['adaptTo']);
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
     * @return array
     */
    public function toParams() {
        $params = [];
        if ($this->adaptTo) {
            $params['adapt_to'] = $this->adaptTo;
        }
        return $params;
    }
}