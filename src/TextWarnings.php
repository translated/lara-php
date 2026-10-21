<?php

namespace Lara;

class TextWarnings implements \JsonSerializable
{
    public static function fromResponse($response)
    {
        return new TextWarnings(
            isset($response['reasoning']) ? ReasoningFeedback::fromResponse($response['reasoning']) : null,
            isset($response['references']) ? ReferenceRetrievalFailure::fromResponse($response['references']) : null,
            isset($response['prosa']) ? IntelligenceFailure::fromResponse($response['prosa']) : null
        );
    }

    private $reasoning;
    private $references;
    private $prosa;

    public function __construct($reasoning, $references, $prosa)
    {
        $this->reasoning = $reasoning;
        $this->references = $references;
        $this->prosa = $prosa;
    }

    /** @return ReasoningFeedback|null */
    public function getReasoning()
    {
        return $this->reasoning;
    }

    /** @return ReferenceRetrievalFailure|null */
    public function getReferences()
    {
        return $this->references;
    }

    /** @return IntelligenceFailure|null */
    public function getProsa()
    {
        return $this->prosa;
    }

    #[\ReturnTypeWillChange]
    public function jsonSerialize()
    {
        return get_object_vars($this);
    }
}
