<?php

namespace Lara;

class TranslatorOptions
{
    private $serverUrl;
    private $sessionId;

    public function __construct($options = [])
    {
        if (isset($options['serverUrl']))
            $this->setServerUrl($options['serverUrl']);
        if (isset($options['sessionId']))
            $this->setSessionId($options['sessionId']);
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

    /**
     * @param $sessionId string|null
     */
    public function setSessionId($sessionId)
    {
        $this->sessionId = $sessionId;
    }

    /**
     * @return string|null
     */
    public function getSessionId()
    {
        return $this->sessionId;
    }

}