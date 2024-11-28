<?php

namespace Lara;

class TranslatorOptions
{
    private $serverUrl;

    public function __construct($options = [])
    {
        if (isset($options['serverUrl']))
            $this->setServerUrl($options['serverUrl']);
    }

    /**
     * @param $serverUrl string|null
     */
    public function setServerUrl($serverUrl)
    {
        $this->serverUrl = $serverUrl;
    }

    /**
     * @return string|null
     */
    public function getServerUrl()
    {
        return $this->serverUrl;
    }

}