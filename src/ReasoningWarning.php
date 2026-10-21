<?php

namespace Lara;

class ReasoningWarning implements \JsonSerializable
{
    public static function fromResponse($response)
    {
        return new ReasoningWarning(
            $response['code'],
            $response['message']
        );
    }

    private $code;
    private $message;

    public function __construct($code, $message)
    {
        $this->code = $code;
        $this->message = $message;
    }

    /** @return string */
    public function getCode()
    {
        return $this->code;
    }

    /** @return string */
    public function getMessage()
    {
        return $this->message;
    }

    #[\ReturnTypeWillChange]
    public function jsonSerialize()
    {
        return get_object_vars($this);
    }
}
