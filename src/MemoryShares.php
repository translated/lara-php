<?php

namespace Lara;

class MemoryShares
{
    private $memory;
    private $account;
    private $groups;
    private $users;

    public static function fromResponse($response)
    {
        return new self(
            Memory::fromResponse($response['memory']),
            isset($response['account']) ? ResourceShareEntry::fromResponse($response['account']) : null,
            array_map([ResourceShareEntry::class, 'fromResponse'], $response['groups']),
            array_map([ResourceShareEntry::class, 'fromResponse'], $response['users'])
        );
    }

    public function __construct($memory, $account, $groups, $users)
    {
        $this->memory = $memory;
        $this->account = $account;
        $this->groups = $groups;
        $this->users = $users;
    }

    public function getMemory() { return $this->memory; }
    public function getAccount() { return $this->account; }
    public function getGroups() { return $this->groups; }
    public function getUsers() { return $this->users; }
}
