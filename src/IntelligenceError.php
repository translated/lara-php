<?php

namespace Lara;

class IntelligenceError implements \JsonSerializable
{
    public static function fromResponse($response)
    {
        return new IntelligenceError(
            isset($response['statusCode']) ? $response['statusCode'] : $response['status_code'],
            $response['message']
        );
    }

    private $statusCode;
    private $message;

    public function __construct($statusCode, $message)
    {
        $this->statusCode = $statusCode;
        $this->message = $message;
    }

    /** @return int */
    public function getStatusCode()
    {
        return $this->statusCode;
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
