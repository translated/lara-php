<?php

namespace Lara;

class LaraApiException extends LaraException
{

    /**
     * @var string The error type
     */
    private $type;

    /**
     * LaraApiException constructor.
     *
     * @param int $statusCode The HTTP status code
     * @param string $type The error type
     * @param string $message The error message
     * @param \Exception|null $previous The previous exception
     */
    public function __construct($statusCode, $type, $message, $previous = null)
    {
        parent::__construct($message, $statusCode, $previous);
        $this->type = $type;
    }

    /**
     * Get the error type
     *
     * @return string
     */
    public function getType()
    {
        return $this->type;
    }

}