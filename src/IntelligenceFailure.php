<?php

namespace Lara;

class IntelligenceFailure implements \JsonSerializable
{
    public static function fromResponse($response)
    {
        return new IntelligenceFailure(
            $response['applied'],
            IntelligenceError::fromResponse($response['error'])
        );
    }

    private $applied;
    private $error;

    public function __construct($applied, $error)
    {
        $this->applied = $applied;
        $this->error = $error;
    }

    /** @return bool */
    public function getApplied()
    {
        return $this->applied;
    }

    /** @return IntelligenceError */
    public function getError()
    {
        return $this->error;
    }

    #[\ReturnTypeWillChange]
    public function jsonSerialize()
    {
        return get_object_vars($this);
    }
}
