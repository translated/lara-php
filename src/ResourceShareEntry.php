<?php

namespace Lara;

class ResourceShareEntry implements \JsonSerializable
{
    private $id;
    private $name;
    private $shareName;
    private $sharedAt;
    private $permissions;

    public static function fromResponse($response)
    {
        return new self(
            $response['id'],
            $response['name'],
            $response['share_name'],
            $response['shared_at'],
            $response['permissions']
        );
    }

    public function __construct($id, $name, $shareName, $sharedAt, $permissions)
    {
        $this->id = $id;
        $this->name = $name;
        $this->shareName = $shareName;
        $this->sharedAt = $sharedAt;
        $this->permissions = $permissions;
    }

    public function getId() { return $this->id; }
    public function getName() { return $this->name; }
    public function getShareName() { return $this->shareName; }
    public function getSharedAt() { return $this->sharedAt; }
    public function getPermissions() { return $this->permissions; }

    #[\ReturnTypeWillChange]
    public function jsonSerialize() { return get_object_vars($this); }
}
