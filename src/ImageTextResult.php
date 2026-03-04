<?php

namespace Lara;

class ImageTextResult
{
    private $sourceLanguage;
    private $adaptedTo;
    private $glossaries;
    private $paragraphs;

    /**
     * @param array $response
     * @return ImageTextResult
     */
    public static function fromResponse($response)
    {
        $paragraphs = array_map(function ($p) {
            return ImageParagraph::fromResponse($p);
        }, isset($response['paragraphs']) ? $response['paragraphs'] : []);

        return new ImageTextResult(
            $response['source_language'],
            isset($response['adapted_to']) ? $response['adapted_to'] : null,
            isset($response['glossaries']) ? $response['glossaries'] : null,
            $paragraphs
        );
    }

    /**
     * @param $sourceLanguage string
     * @param $adaptedTo string[]|null
     * @param $glossaries string[]|null
     * @param $paragraphs ImageParagraph[]
     */
    public function __construct($sourceLanguage, $adaptedTo, $glossaries, $paragraphs)
    {
        $this->sourceLanguage = $sourceLanguage;
        $this->adaptedTo = $adaptedTo;
        $this->glossaries = $glossaries;
        $this->paragraphs = $paragraphs;
    }

    /**
     * @return string
     */
    public function getSourceLanguage()
    {
        return $this->sourceLanguage;
    }

    /**
     * @return string[]|null
     */
    public function getAdaptedTo()
    {
        return $this->adaptedTo;
    }

    /**
     * @return string[]|null
     */
    public function getGlossaries()
    {
        return $this->glossaries;
    }

    /**
     * @return ImageParagraph[]
     */
    public function getParagraphs()
    {
        return $this->paragraphs;
    }
}
