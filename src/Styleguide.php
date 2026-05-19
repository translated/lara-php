<?php

namespace Lara;

class Styleguide implements \JsonSerializable
{
    /**
     * @param $response array
     * @return Styleguide
     */
    public static function fromResponse($response)
    {
        return new Styleguide(
            $response['id'],
            $response['created_at'],
            $response['updated_at'],
            $response['name'],
            $response['owner_id'],
            $response['is_personal'],
            isset($response['content']) ? $response['content'] : null
        );
    }

    private $id;
    private $createdAt;
    private $updatedAt;
    private $name;
    private $ownerId;
    private $isPersonal;
    private $content;

    /**
     * @param $id string
     * @param $createdAt string
     * @param $updatedAt string
     * @param $name string
     * @param $ownerId string
     * @param $isPersonal bool
     * @param $content string|null
     */
    public function __construct($id, $createdAt, $updatedAt, $name, $ownerId, $isPersonal, $content = null)
    {
        $this->id = $id;
        $this->createdAt = $createdAt;
        $this->updatedAt = $updatedAt;
        $this->name = $name;
        $this->ownerId = $ownerId;
        $this->isPersonal = $isPersonal;
        $this->content = $content;
    }

    /**
     * @return string
     */
    public function getId()
    {
        return $this->id;
    }

    /**
     * @return string
     */
    public function getCreatedAt()
    {
        return $this->createdAt;
    }

    /**
     * @return string
     */
    public function getUpdatedAt()
    {
        return $this->updatedAt;
    }

    /**
     * @return string
     */
    public function getName()
    {
        return $this->name;
    }

    /**
     * @return string
     */
    public function getOwnerId()
    {
        return $this->ownerId;
    }

    /**
     * @return bool
     */
    public function getIsPersonal()
    {
        return $this->isPersonal;
    }

    /**
     * @return string|null
     */
    public function getContent()
    {
        return $this->content;
    }

    public function __toString()
    {
        return $this->id;
    }

    // Compatibility layer for PHP 8.1+
    #[\ReturnTypeWillChange]
    public function jsonSerialize()
    {
        return get_object_vars($this);
    }
}
