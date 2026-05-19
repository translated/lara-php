<?php

namespace Lara;

class Memory implements \JsonSerializable
{
    /**
     * @param $response array
     * @return Memory
     */
    public static function fromResponse($response)
    {
        return new Memory(
            $response['id'],
            $response['created_at'],
            $response['updated_at'],
            $response['shared_at'],
            $response['name'],
            $response['owner_id'],
            $response['collaborators_count'],
            $response['is_personal'],
            isset($response['external_id']) ? $response['external_id'] : null,
            isset($response['secret']) ? $response['secret'] : null
        );
    }

    private $id;
    private $createdAt;
    private $updatedAt;
    private $sharedAt;
    private $name;
    private $ownerId;
    private $collaboratorsCount;
    private $externalId;
    private $secret;
    private $isPersonal;

    /**
     * @param $id string
     * @param $createdAt string
     * @param $updatedAt string
     * @param $sharedAt string
     * @param $name string
     * @param $ownerId string
     * @param $collaboratorsCount int
     * @param $isPersonal bool
     * @param $externalId string|null
     * @param $secret string|null
     */
    public function __construct($id, $createdAt, $updatedAt, $sharedAt, $name, $ownerId, $collaboratorsCount,
                                $isPersonal, $externalId = null, $secret = null)
    {
        $this->id = $id;
        $this->createdAt = $createdAt;
        $this->updatedAt = $updatedAt;
        $this->sharedAt = $sharedAt;
        $this->name = $name;
        $this->ownerId = $ownerId;
        $this->collaboratorsCount = $collaboratorsCount;
        $this->isPersonal = $isPersonal;
        $this->externalId = $externalId;
        $this->secret = $secret;
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
     * @return int
     */
    public function getCollaboratorsCount()
    {
        return $this->collaboratorsCount;
    }

    /**
     * @return string|null
     */
    public function getExternalId()
    {
        return $this->externalId;
    }

    /**
     * @return string|null
     */
    public function getSecret()
    {
        return $this->secret;
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