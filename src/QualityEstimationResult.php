<?php

namespace Lara;

class QualityEstimationResult implements \JsonSerializable
{
    /**
     * @param $response array
     * @return QualityEstimationResult
     */
    public static function fromResponse($response)
    {
        return new QualityEstimationResult($response['score']);
    }

    private $score;

    /**
     * @param $score float
     */
    public function __construct($score)
    {
        $this->score = $score;
    }

    /**
     * @return float
     */
    public function getScore()
    {
        return $this->score;
    }

    #[\ReturnTypeWillChange]
    public function jsonSerialize()
    {
        return get_object_vars($this);
    }
}
