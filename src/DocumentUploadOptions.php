<?php

namespace Lara;

class DocumentUploadOptions
{
    private $adaptTo = null;

    public function __construct($options = [])
    {
        if (isset($options['adaptTo']))
            $this->setAdaptTo($options['adaptTo']);
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