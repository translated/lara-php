<?php

namespace Lara;

abstract class DocumentExtractionParams
{
    /**
     * Convert the extraction parameters to an array suitable for API requests
     * @return array
     */
    abstract public function toParams();
}
