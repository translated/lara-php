<?php

namespace Lara;

class NGMemoryMatch
{

    /**
     * @param $response array
     * @return NGMemoryMatch
     */
    public static function fromResponse($response)
    {
        return new NGMemoryMatch(
            $response['memory'],
            isset($response['tuid']) ? $response['tuid'] : null,
            $response['language'],
            $response['sentence'],
            $response['translation'],
            $response['score']
        );
    }

    private $memory;
    private $tuid;
    private $language;
    private $sentence;
    private $translation;
    private $score;

    public function __construct($memory, $tuid, $language, $sentence, $translation, $score)
    {
        $this->memory = $memory;
        $this->tuid = $tuid;
        $this->language = $language;
        $this->sentence = $sentence;
        $this->translation = $translation;
        $this->score = $score;
    }

    /**
     * @return string
     */
    public function getMemory()
    {
        return $this->memory;
    }

    /**
     * @return string|null
     */
    public function getTuid()
    {
        return $this->tuid;
    }

    /**
     * @return string[]
     */
    public function getLanguage()
    {
        return $this->language;
    }

    /**
     * @return string
     */
    public function getSentence()
    {
        return $this->sentence;
    }

    /**
     * @return string
     */
    public function getTranslation()
    {
        return $this->translation;
    }

    /**
     * @return float
     */
    public function getScore()
    {
        return $this->score;
    }
}