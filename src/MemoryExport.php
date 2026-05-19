<?php

namespace Lara;

class MemoryExport implements \JsonSerializable
{
    /**
     * @param $response array
     * @return MemoryExport
     */
    public static function fromResponse($response)
    {
        return new MemoryExport($response['job_id']);
    }

    private $jobId;

    /**
     * @param $jobId string
     */
    public function __construct($jobId)
    {
        $this->jobId = $jobId;
    }

    /**
     * @return string
     */
    public function getJobId()
    {
        return $this->jobId;
    }

    public function __toString()
    {
        return $this->jobId;
    }

    // Compatibility layer for PHP 8.1+
    #[\ReturnTypeWillChange]
    public function jsonSerialize()
    {
        return get_object_vars($this);
    }
}
