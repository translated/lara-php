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
        if (isset($response['profanities']) && is_array($response['profanities'])) {
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
            $profanities,
            isset($response['error']) ? $response['error'] : null
        );
    }

    private $maskedText;
    private $profanities;
    private $error;

    /**
     * @param $maskedText string
     * @param $profanities array[]
     * @param $error string|null
     */
    public function __construct($maskedText, $profanities = [], $error = null)
    {
        $this->maskedText = $maskedText;
        $this->profanities = $profanities;
        $this->error = $error;
    }

    /**
     * @return string
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

    /**
     * @return string|null
     */
    public function getError()
    {
        return $this->error;
    }
}
