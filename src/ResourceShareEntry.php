<?php

namespace Lara;

class ResourceShareEntry implements \JsonSerializable
{
    private $id;
    private $name;
    private $shareName;
    private $sharedAt;
    private $permissionMask;

    public static function fromResponse($response)
    {
        return new self(
            $response['id'],
            $response['name'],
            $response['share_name'],
            $response['shared_at'],
            $response['permission_mask']
        );
    }

    public function __construct($id, $name, $shareName, $sharedAt, $permissionMask)
    {
        $this->id = $id;
        $this->name = $name;
        $this->shareName = $shareName;
        $this->sharedAt = $sharedAt;
        $this->permissionMask = $permissionMask;
    }

    public function getId() { return $this->id; }
    public function getName() { return $this->name; }
    public function getShareName() { return $this->shareName; }
    public function getSharedAt() { return $this->sharedAt; }

    /** @return string The permissions stored on this share. */
    public function getPermissionMask() { return $this->permissionMask; }

    #[\ReturnTypeWillChange]
    public function jsonSerialize() { return get_object_vars($this); }
}
