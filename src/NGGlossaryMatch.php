<?php

namespace Lara;

class NGGlossaryMatch
{

    /**
     * @param $response array
     * @return NGGlossaryMatch
     */
    public static function fromResponse($response)
    {
        return new NGGlossaryMatch(
            $response['memory'],
            $response['language'],
            $response['term'],
            $response['translation']
        );
    }

    private $memory;
    private $language;
    private $term;
    private $translation;

    public function __construct($memory, $language, $term, $translation)
    {
        $this->memory = $memory;
        $this->language = $language;
        $this->term = $term;
        $this->translation = $translation;
    }

    /**
     * @return string
     */
    public function getMemory()
    {
        return $this->memory;
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
    public function getTerm()
    {
        return $this->term;
    }

    /**
     * @return string
     */
    public function getTranslation()
    {
        return $this->translation;
    }

}