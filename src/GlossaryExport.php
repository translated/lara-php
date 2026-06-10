<?php

namespace Lara;

class GlossaryExport implements \JsonSerializable
{
    /**
     * @param $response array
     * @return GlossaryExport
     */
    public static function fromResponse($response)
    {
        return new GlossaryExport($response['job_id']);
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
