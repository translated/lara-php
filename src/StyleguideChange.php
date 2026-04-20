<?php

namespace Lara;

class StyleguideChange
{

    /**
     * @param $response array
     * @return StyleguideChange
     */
    public static function fromResponse($response)
    {
        return new StyleguideChange(
            isset($response['id']) ? $response['id'] : null,
            $response['original_translation'],
            $response['refined_translation'],
            $response['explanation']
        );
    }

    private $id;
    private $originalTranslation;
    private $refinedTranslation;
    private $explanation;

    public function __construct($id, $originalTranslation, $refinedTranslation, $explanation)
    {
        $this->id = $id;
        $this->originalTranslation = $originalTranslation;
        $this->refinedTranslation = $refinedTranslation;
        $this->explanation = $explanation;
    }

    /**
     * @return string|null
     */
    public function getId()
    {
        return $this->id;
    }

    /**
     * @return string
     */
    public function getOriginalTranslation()
    {
        return $this->originalTranslation;
    }

    /**
     * @return string
     */
    public function getRefinedTranslation()
    {
        return $this->refinedTranslation;
    }

    /**
     * @return string
     */
    public function getExplanation()
    {
        return $this->explanation;
    }
}
