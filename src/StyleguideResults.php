<?php

namespace Lara;

class StyleguideResults
{

    /**
     * @param $response array
     * @return StyleguideResults
     */
    public static function fromResponse($response)
    {
        $changes = [];
        if (isset($response['changes'])) {
            foreach ($response['changes'] as $change) {
                $changes[] = StyleguideChange::fromResponse($change);
            }
        }

        return new StyleguideResults(
            isset($response['original_translation']) ? $response['original_translation'] : null,
            $changes
        );
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
}
