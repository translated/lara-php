<?php

namespace Lara;

class StyleguideResults implements \JsonSerializable
{

    /**
     * @param $response array
     * @return StyleguideResults
     */
    public static function fromResponse($response)
    {
        $originalTranslation = isset($response['original_translation']) ? $response['original_translation'] : null;
        if (is_array($originalTranslation)) {
            $originalTranslation = array_map(function ($e) {
                if (is_string($e))
                    return $e;
                else
                    return TextBlock::fromResponse($e);
            }, $originalTranslation);
        }

        $changes = [];
        if (isset($response['changes'])) {
            foreach ($response['changes'] as $change) {
                $changes[] = StyleguideChange::fromResponse($change);
            }
        }

        return new StyleguideResults($originalTranslation, $changes);
    }

    private $originalTranslation;
    private $changes;

    /**
     * @param $originalTranslation string|string[]|TextBlock[]|null
     * @param $changes StyleguideChange[]
     */
    public function __construct($originalTranslation, $changes = [])
    {
        $this->originalTranslation = $originalTranslation;
        $this->changes = $changes;
    }

    /**
     * @return string|string[]|TextBlock[]|null
     */
    public function getOriginalTranslation()
    {
        return $this->originalTranslation;
    }

    /**
     * @return StyleguideChange[]
     */
    public function getChanges()
    {
        return $this->changes;
    }

    // Compatibility layer for PHP 8.1+
    #[\ReturnTypeWillChange]
    public function jsonSerialize()
    {
        return get_object_vars($this);
    }
}
