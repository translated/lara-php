<?php

namespace Lara;

class DocxExtractionParams extends DocumentExtractionParams
{
    public $extractComments = null;
    public $acceptRevisions = null;

    /**
     * @param $extractComments bool|null
     * @param $acceptRevisions bool|null
     */
    public function __construct($extractComments = null, $acceptRevisions = null)
    {
        $this->extractComments = $extractComments;
        $this->acceptRevisions = $acceptRevisions;
    }

    /**
     * Convert the extraction parameters to an array suitable for API requests
     * @return array
     */
    public function toParams()
    {
        $params = [];
        if ($this->extractComments !== null) {
            $params['extract_comments'] = $this->extractComments;
        }
        if ($this->acceptRevisions !== null) {
            $params['accept_revisions'] = $this->acceptRevisions;
        }
        return $params;
    }
}
