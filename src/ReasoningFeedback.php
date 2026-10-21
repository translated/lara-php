<?php

namespace Lara;

class ReasoningFeedback implements \JsonSerializable
{
    public static function fromResponse($response)
    {
        return new ReasoningFeedback(
            $response['applied'],
            isset($response['error']) ? IntelligenceError::fromResponse($response['error']) : null,
            array_map([ReasoningWarning::class, 'fromResponse'], isset($response['warnings']) ? $response['warnings'] : [])
        );
    }

    private $applied;
    private $error;
    private $warnings;

    public function __construct($applied, $error, $warnings)
    {
        $this->applied = $applied;
        $this->error = $error;
        $this->warnings = $warnings;
    }

    /** @return bool */
    public function getApplied()
    {
        return $this->applied;
    }

    /** @return IntelligenceError|null */
    public function getError()
    {
        return $this->error;
    }

    /** @return ReasoningWarning[] */
    public function getWarnings()
    {
        return $this->warnings;
    }

    #[\ReturnTypeWillChange]
    public function jsonSerialize()
    {
        return get_object_vars($this);
    }
}
