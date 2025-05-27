<?php

namespace Lara;

class DocumentDownloadOptions
{
    private $outputFormat = null;

    public function __construct($options = [])
    {
        if (isset($options['outputFormat']))
            $this->setOutputFormat($options['outputFormat']);
    }

    /**
     * @param $outputFormat string|null
     */
    public function setOutputFormat($outputFormat)
    {
        $this->outputFormat = $outputFormat;
    }

    /**
     * @return string|null
     */
    public function getOutputFormat()
    {
        return $this->outputFormat;
    }

    /**
     * @return array
     */
    public function toParams() {
        $params = [];
        if ($this->outputFormat) {
            $params['output_format'] = $this->outputFormat;
        }
        return $params;
    }
}