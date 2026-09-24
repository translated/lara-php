<?php

namespace Lara;

/** Translated image text with the layout required by overlay and inpainting. */
class ImageLayoutParagraph extends ImageParagraph
{
    private $bbox;
    private $linesBboxes;
    private $textInfo;
    private $alignment;

    /**
     * @param $text string
     * @param $translation string
     * @param $bbox ImageBBox
     * @param $linesBboxes ImageBBox[]
     * @param $textInfo ImageTextInfo
     * @param $alignment string "left", "center" or "right"
     * @param $adaptedToMatches NGMemoryMatch[]|null
     * @param $glossariesMatches NGGlossaryMatch[]|null
     */
    public function __construct($text, $translation, $bbox, $linesBboxes, $textInfo, $alignment,
                                $adaptedToMatches = null, $glossariesMatches = null)
    {
        parent::__construct($text, $translation, $adaptedToMatches, $glossariesMatches);
        $this->bbox = $bbox;
        $this->linesBboxes = $linesBboxes;
        $this->textInfo = $textInfo;
        $this->alignment = $alignment;
    }

    /** @return ImageBBox */
    public function getBbox()
    {
        return $this->bbox;
    }

    /** @return ImageBBox[] */
    public function getLinesBboxes()
    {
        return $this->linesBboxes;
    }

    /** @return ImageTextInfo */
    public function getTextInfo()
    {
        return $this->textInfo;
    }

    /** @return string "left", "center" or "right" */
    public function getAlignment()
    {
        return $this->alignment;
    }
}
