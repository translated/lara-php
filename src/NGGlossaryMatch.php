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
            $response['glossary'],
            $response['language'],
            $response['term'],
            $response['translation']
        );
    }

    private $glossary;
    private $language;
    private $term;
    private $translation;

    public function __construct($glossary, $language, $term, $translation)
    {
        $this->glossary = $glossary;
        $this->language = $language;
        $this->term = $term;
        $this->translation = $translation;
    }

    /**
     * @return string
     */
    public function getGlossary()
    {
        return $this->glossary;
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