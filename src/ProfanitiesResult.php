<?php

namespace Lara;

class ProfanitiesResult
{
    /**
     * @param $response array
     * @return ProfanitiesResult
     */
    public static function fromResponse($response)
    {
        return new ProfanitiesResult(
            self::parseSide($response['target']),
            isset($response['source']) ? self::parseSide($response['source']) : null
        );
    }

    private static function parseSide($raw)
    {
        if (empty($raw) || array_key_exists(0, $raw)) {
            return array_map(function ($item) {
                return $item !== null ? ProfanityDetectResult::fromResponse($item) : null;
            }, $raw);
        }
        return ProfanityDetectResult::fromResponse($raw);
    }

    private $target;
    private $source;

    /**
     * @param $target ProfanityDetectResult|ProfanityDetectResult[]
     * @param $source ProfanityDetectResult|ProfanityDetectResult[]|null
     */
    public function __construct($target, $source = null)
    {
        $this->target = $target;
        $this->source = $source;
    }

    /**
     * @return ProfanityDetectResult|ProfanityDetectResult[]
     */
    public function getTarget()
    {
        return $this->target;
    }

    /**
     * @return ProfanityDetectResult|ProfanityDetectResult[]|null
     */
    public function getSource()
    {
        return $this->source;
    }
}
