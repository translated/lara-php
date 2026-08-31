<?php

namespace Lara;

class StyleguideShares
{
    private $styleguide;
    private $account;
    private $groups;
    private $users;

    public static function fromResponse($response)
    {
        return new self(
            Styleguide::fromResponse($response['styleguide']),
            isset($response['account']) ? ResourceShareEntry::fromResponse($response['account']) : null,
            array_map([ResourceShareEntry::class, 'fromResponse'], $response['groups']),
            array_map([ResourceShareEntry::class, 'fromResponse'], $response['users'])
        );
    }

    public function __construct($styleguide, $account, $groups, $users)
    {
        $this->styleguide = $styleguide;
        $this->account = $account;
        $this->groups = $groups;
        $this->users = $users;
    }

    public function getStyleguide() { return $this->styleguide; }
    public function getAccount() { return $this->account; }
    public function getGroups() { return $this->groups; }
    public function getUsers() { return $this->users; }
}
