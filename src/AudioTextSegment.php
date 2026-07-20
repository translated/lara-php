<?php

namespace Lara;

class AudioTextSegment
{
    private $id;
    private $start;
    private $end;
    private $text;
    private $translation;

    /**
     * @param array $response
     * @return AudioTextSegment
     */
    public static function fromResponse($response)
    {
        return new AudioTextSegment(
            isset($response['id']) ? $response['id'] : null,
            isset($response['start']) ? $response['start'] : null,
            isset($response['end']) ? $response['end'] : null,
            isset($response['text']) ? $response['text'] : null,
            isset($response['translation']) ? $response['translation'] : null
        );
    }

    /**
     * @param $id int|null
     * @param $start float|null
     * @param $end float|null
     * @param $text string|null
     * @param $translation string|null
     */
    public function __construct($id, $start, $end, $text, $translation)
    {
        $this->id = $id;
        $this->start = $start;
        $this->end = $end;
        $this->text = $text;
        $this->translation = $translation;
    }

    /**
     * @return int|null
     */
    public function getId()
    {
        return $this->id;
    }

    /**
     * @return float|null
     */
    public function getStart()
    {
        return $this->start;
    }

    /**
     * @return float|null
     */
    public function getEnd()
    {
        return $this->end;
    }

    /**
     * @return string|null
     */
    public function getText()
    {
        return $this->text;
    }

    /**
     * @return string|null
     */
    public function getTranslation()
    {
        return $this->translation;
    }
}
