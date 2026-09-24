<?php

namespace Lara;

/** A quadrilateral with integer [x, y] coordinate pairs. */
class ImageBBox implements \JsonSerializable
{
    private $topLeft;
    private $topRight;
    private $bottomRight;
    private $bottomLeft;

    /**
     * @param $response array
     * @return ImageBBox
     */
    public static function fromResponse($response)
    {
        return new ImageBBox(
            $response['top_left'],
            $response['top_right'],
            $response['bottom_right'],
            $response['bottom_left']
        );
    }

    /**
     * @param $topLeft int[]
     * @param $topRight int[]
     * @param $bottomRight int[]
     * @param $bottomLeft int[]
     */
    public function __construct($topLeft, $topRight, $bottomRight, $bottomLeft)
    {
        $this->topLeft = $topLeft;
        $this->topRight = $topRight;
        $this->bottomRight = $bottomRight;
        $this->bottomLeft = $bottomLeft;
    }

    /** @return int[] */
    public function getTopLeft()
    {
        return $this->topLeft;
    }

    /** @return int[] */
    public function getTopRight()
    {
        return $this->topRight;
    }

    /** @return int[] */
    public function getBottomRight()
    {
        return $this->bottomRight;
    }

    /** @return int[] */
    public function getBottomLeft()
    {
        return $this->bottomLeft;
    }

    // Compatibility layer for PHP 8.1+
    #[\ReturnTypeWillChange]
    public function jsonSerialize()
    {
        return [
            'top_left' => array_values($this->topLeft),
            'top_right' => array_values($this->topRight),
            'bottom_right' => array_values($this->bottomRight),
            'bottom_left' => array_values($this->bottomLeft)
        ];
    }
}
