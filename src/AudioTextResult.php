<?php

namespace Lara;

class AudioTextResult
{
    private $id;
    private $source;
    private $target;
    private $filename;
    private $duration;
    private $text;
    private $translation;
    private $segments;

    /**
     * @param array $response
     * @return AudioTextResult
     */
    public static function fromResponse($response)
    {
        $segments = array_map(function ($s) {
            return AudioTextSegment::fromResponse($s);
        }, isset($response['segments']) ? $response['segments'] : []);

        return new AudioTextResult(
            isset($response['id']) ? $response['id'] : null,
            isset($response['source']) ? $response['source'] : null,
            isset($response['target']) ? $response['target'] : null,
            isset($response['filename']) ? $response['filename'] : null,
            isset($response['duration']) ? $response['duration'] : null,
            isset($response['text']) ? $response['text'] : null,
            isset($response['translation']) ? $response['translation'] : null,
            $segments
        );
    }

    /**
     * @param $id string|null
     * @param $source string|null
     * @param $target string|null
     * @param $filename string|null
     * @param $duration float|null
     * @param $text string|null
     * @param $translation string|null
     * @param $segments AudioTextSegment[]
     */
    public function __construct($id, $source, $target, $filename, $duration, $text, $translation, $segments)
    {
        $this->id = $id;
        $this->source = $source;
        $this->target = $target;
        $this->filename = $filename;
        $this->duration = $duration;
        $this->text = $text;
        $this->translation = $translation;
        $this->segments = $segments;
    }

    /**
     * @return string|null
     */
    public function getId()
    {
        return $this->id;
    }

    /**
     * @return string|null
     */
    public function getSource()
    {
        return $this->source;
    }

    /**
     * @return string|null
     */
    public function getTarget()
    {
        return $this->target;
    }

    /**
     * @return string|null
     */
    public function getFilename()
    {
        return $this->filename;
    }

    /**
     * @return float|null
     */
    public function getDuration()
    {
        return $this->duration;
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

    /**
     * @return AudioTextSegment[]
     */
    public function getSegments()
    {
        return $this->segments;
    }
}
