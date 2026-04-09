<?php

namespace Lara;

class ProfanityDetectResult
{

    /**
     * @param $response array
     * @return ProfanityDetectResult
     */
    public static function fromResponse($response)
    {
        $profanities = [];
        if (isset($response['profanities'])) {
            foreach ($response['profanities'] as $p) {
                $profanities[] = [
                    'text' => $p['text'],
                    'startCharIndex' => $p['start_char_index'],
                    'endCharIndex' => $p['end_char_index'],
                    'score' => $p['score'],
                ];
            }
        }

        return new ProfanityDetectResult(
            $response['masked_text'],
            $profanities
        );
    }

    private $maskedText;
    private $profanities;

    /**
     * @param $maskedText string|null
     * @param $profanities array[]
     */
    public function __construct($maskedText, $profanities = [])
    {
        $this->maskedText = $maskedText;
        $this->profanities = $profanities;
    }

    /**
     * @return string|null
     */
    public function getMaskedText()
    {
        return $this->maskedText;
    }

    /**
     * @return array[] Each element has keys: text, startCharIndex, endCharIndex, score
     */
    public function getProfanities()
    {
        return $this->profanities;
    }
}
