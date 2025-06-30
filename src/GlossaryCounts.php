<?php

namespace Lara;

class GlossaryCounts
{
    /**
     * @param $response array
     * @return GlossaryCounts
     */
    public static function fromResponse($response)
    {
        return new GlossaryCounts(
            isset($response['unidirectional']) ? $response['unidirectional'] : null,
            isset($response['multidirectional']) ? $response['multidirectional'] : null
        );
    }

    private $unidirectional;
    private $multidirectional;

    /**
     * @param $unidirectional array<string, int>
     * @param $multidirectional int
     */
    public function __construct($unidirectional, $multidirectional)
    {
        $this->unidirectional = $unidirectional;
        $this->multidirectional = $multidirectional;
    }

    /**
     * @return array<string, int>
     */
    public function getUnidirectional()
    {
        return $this->unidirectional;
    }

    /**
     * @return int
     */
    public function getMultidirectional()
    {
        return $this->multidirectional;
    }

    public function __toString() {
        return 'Unidirectional: ' . json_encode($this->unidirectional) . ', Multidirectional: ' . $this->multidirectional;
    }

    // Compatibility layer for PHP 8.1+
    #[\ReturnTypeWillChange]
    public function jsonSerialize()
    {
        return get_object_vars($this);
    }
}