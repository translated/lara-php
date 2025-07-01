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
            $response['source'],
            $response['target'],
            $response['sentence'],
            $response['translation'],
            $response['score']
        );
    }

    private $memory;
    private $tuid;
    private $source;
    private $target;
    private $sentence;
    private $translation;
    private $score;

    public function __construct($memory, $tuid, $source, $target, $sentence, $translation, $score)
    {
        $this->memory = $memory;
        $this->tuid = $tuid;
        $this->source = $source;
        $this->target = $target;
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
     * @return string
     */
    public function getSource()
    {
        return $this->source;
    }

    /**
     * @return string
     */
    public function getTarget()
    {
        return $this->target;
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