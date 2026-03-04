<?php

namespace Lara;

class Audio
{
    /**
     * @param $response array
     * @return Audio
     */
    public static function fromResponse($response) {
        return new Audio(
            $response['id'],
            $response['status'],
            isset($response['source']) ? $response['source'] : null,
            isset($response['target']) ? $response['target'] : null,
            $response['filename'],
            $response['created_at'],
            $response['updated_at'],
            isset($response['error_reason']) ? $response['error_reason'] : null,
            isset($response['options']) ? $response['options'] : null,
            isset($response['translated_seconds']) ? $response['translated_seconds'] : null,
            isset($response['total_seconds']) ? $response['total_seconds'] : null
        );
    }

    private $id;
    private $status;
    private $source;
    private $target;
    private $filename;
    private $createdAt;
    private $updatedAt;
    private $errorReason;
    private $options;
    private $translatedSeconds;
    private $totalSeconds;

    public function __construct($id, $status, $source, $target, $filename, $createdAt, $updatedAt, $errorReason, $options = null, $translatedSeconds = null, $totalSeconds = null) {
        $this->id = $id;
        $this->status = $status;
        $this->source = $source;
        $this->target = $target;
        $this->filename = $filename;
        $this->createdAt = $createdAt;
        $this->updatedAt = $updatedAt;
        $this->errorReason = $errorReason;
        $this->options = $options;
        $this->translatedSeconds = $translatedSeconds;
        $this->totalSeconds = $totalSeconds;
    }

    /**
     * @return string
     */
    public function getId() {
        return $this->id;
    }

    /**
     * @return string
     */
    public function getStatus() {
        return $this->status;
    }

    /**
     * @return string
     */
    public function getSource() {
        return $this->source;
    }

    /**
     * @return string
     */
    public function getTarget() {
        return $this->target;
    }

    /**
     * @return string
     */
    public function getFilename() {
        return $this->filename;
    }

    public function getCreatedAt() {
        return $this->createdAt;
    }

    public function getUpdatedAt() {
        return $this->updatedAt;
    }

    /**
     * @return string
     */
    public function getErrorReason() {
        return $this->errorReason;
    }

    /**
     * @return array|null
     */
    public function getOptions() {
        return $this->options;
    }

    /**
     * @return int|null
     */
    public function getTranslatedSeconds() {
        return $this->translatedSeconds;
    }

    /**
     * @return int|null
     */
    public function getTotalSeconds() {
        return $this->totalSeconds;
    }

    /**
     * @return string
     */
    public function __toString()
    {
        return $this->id;
    }
}
