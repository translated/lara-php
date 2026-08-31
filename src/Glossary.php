<?php

namespace Lara;

class Glossary implements \JsonSerializable
{
    /**
     * @param $response array
     * @return Glossary
     */
    public static function fromResponse($response)
    {
        return new Glossary(
            $response['id'],
            $response['created_at'],
            $response['updated_at'],
            $response['name'],
            $response['owner_id'],
            isset($response['is_personal']) ? (bool)$response['is_personal'] : false,
            $response['shared_at']
        );
    }

    private $id;
    private $createdAt;
    private $updatedAt;
    private $sharedAt;
    private $name;
    private $ownerId;
    private $isPersonal;

    /**
     * @param $id string
     * @param $createdAt string
     * @param $updatedAt string
     * @param $name string
     * @param $ownerId string
     * @param $isPersonal bool
     * @param $sharedAt string
     */
    public function __construct($id, $createdAt, $updatedAt, $name, $ownerId, $isPersonal, $sharedAt)
    {
        $this->id = $id;
        $this->createdAt = $createdAt;
        $this->updatedAt = $updatedAt;
        $this->sharedAt = $sharedAt;
        $this->name = $name;
        $this->ownerId = $ownerId;
        $this->isPersonal = $isPersonal;
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

    public function getSharedAt()
    {
        return $this->sharedAt;
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
