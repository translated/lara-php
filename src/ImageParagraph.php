<?php

namespace Lara;

class ImageParagraph
{
    private $text;
    private $translation;
    private $adaptedToMatches;
    private $glossariesMatches;

    /**
     * Responses with complete layout metadata produce ImageLayoutParagraph instances.
     * @param array $response
     * @return ImageParagraph
     */
    public static function fromResponse($response)
    {
        $adaptedToMatches = null;
        if (isset($response['adapted_to_matches'])) {
            $adaptedToMatches = array_map(function ($m) {
                return NGMemoryMatch::fromResponse($m);
            }, $response['adapted_to_matches']);
        }

        $glossariesMatches = null;
        if (isset($response['glossaries_matches'])) {
            $glossariesMatches = array_map(function ($m) {
                return NGGlossaryMatch::fromResponse($m);
            }, $response['glossaries_matches']);
        }

        if (isset($response['bbox'], $response['lines_bboxes'], $response['text_info'], $response['alignment'])) {
            return new ImageLayoutParagraph(
                $response['text'],
                $response['translation'],
                ImageBBox::fromResponse($response['bbox']),
                array_map(function ($bbox) {
                    return ImageBBox::fromResponse($bbox);
                }, $response['lines_bboxes']),
                ImageTextInfo::fromResponse($response['text_info']),
                $response['alignment'],
                $adaptedToMatches,
                $glossariesMatches
            );
        }

        return new ImageParagraph(
            $response['text'],
            $response['translation'],
            $adaptedToMatches,
            $glossariesMatches
        );
    }

    /**
     * @param $text string
     * @param $translation string
     * @param $adaptedToMatches NGMemoryMatch[]|null
     * @param $glossariesMatches NGGlossaryMatch[]|null
     */
    public function __construct($text, $translation, $adaptedToMatches = null, $glossariesMatches = null)
    {
        $this->text = $text;
        $this->translation = $translation;
        $this->adaptedToMatches = $adaptedToMatches;
        $this->glossariesMatches = $glossariesMatches;
    }

    /**
     * @return string
     */
    public function getText()
    {
        return $this->text;
    }

    /**
     * @return string
     */
    public function getTranslation()
    {
        return $this->translation;
    }

    /**
     * @return NGMemoryMatch[]|null
     */
    public function getAdaptedToMatches()
    {
        return $this->adaptedToMatches;
    }

    /**
     * @return NGGlossaryMatch[]|null
     */
    public function getGlossariesMatches()
    {
        return $this->glossariesMatches;
    }
}
