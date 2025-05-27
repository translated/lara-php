<?php

namespace Lara;

class Document
{
    /**
     * @param $response array
     * @return Document
     */
    public static function fromResponse($response) {
        return new Document(
            $response['id'],
            $response['status'],
            isset($response['source']) ? $response['source'] : null,
            $response['target'],
            $response['filename'],
            $response['created_at'],
            $response['updated_at'],
            isset($response['translated_chars']) ? $response['translated_chars'] : null,
            isset($response['total_chars']) ? $response['total_chars'] : null,
            isset($response['error_reason']) ? $response['error_reason'] : null
        );
    }

    private $id;
    private $status;
    private $source;
    private $target;
    private $filename;
    private $createdAt;
    private $updatedAt;
    private $translatedChars;
    private $totalChars;
    private $errorReason;

    public function __construct($id, $status, $source, $target, $filename, $createdAt, $updatedAt, $translatedChars, $totalChars, $errorReason) {
        $this->id = $id;
        $this->status = $status;
        $this->source = $source;
        $this->target = $target;
        $this->filename = $filename;
        $this->createdAt = $createdAt;
        $this->updatedAt = $updatedAt;
        $this->translatedChars = $translatedChars;
        $this->totalChars = $totalChars;
        $this->errorReason = $errorReason;
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
     * @return int
     */
    public function getTranslatedChars() {
        return $this->translatedChars;
    }

    /**
     * @return int
     */
    public function getTotalChars() {
        return $this->totalChars;
    }

    /**
     * @return string
     */
    public function getErrorReason() {
        return $this->errorReason;
    }

    /**
     * @return string
     */
    public function __toString()
    {
        return $this->id;
    }
}