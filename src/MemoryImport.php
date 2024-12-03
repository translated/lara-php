<?php

namespace Lara;

class MemoryImport implements \JsonSerializable
{
    /**
     * @param $response array
     * @return MemoryImport
     */
    public static function fromResponse($response)
    {
        return new MemoryImport(
            $response['id'],
            $response['begin'],
            $response['end'],
            $response['channel'],
            $response['size'],
            $response['progress']
        );
    }

    private $id;
    private $begin;
    private $end;
    private $channel;
    private $size;
    private $progress;

    /**
     * @param $id string
     * @param $begin string
     * @param $end string
     * @param $channel int
     * @param $size int
     * @param $progress float
     */
    public function __construct($id, $begin, $end, $channel, $size, $progress)
    {
        $this->id = $id;
        $this->begin = $begin;
        $this->end = $end;
        $this->channel = $channel;
        $this->size = $size;
        $this->progress = $progress;
    }

    /**
     * @return string
     */
    public function getId()
    {
        return $this->id;
    }

    /**
     * @return string
     */
    public function getBegin()
    {
        return $this->begin;
    }

    /**
     * @return string
     */
    public function getEnd()
    {
        return $this->end;
    }

    /**
     * @return int
     */
    public function getChannel()
    {
        return $this->channel;
    }

    /**
     * @return int
     */
    public function getSize()
    {
        return $this->size;
    }

    /**
     * @return float
     */
    public function getProgress()
    {
        return $this->progress;
    }

    public function __toString()
    {
        return $this->id;
    }

    // Compatibility layer for PHP 8.1+
    #[\ReturnTypeWillChange]
    public function jsonSerialize()
    {
        return get_object_vars($this);
    }

}