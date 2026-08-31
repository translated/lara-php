<?php

namespace Lara;

class GlossaryShares
{
    private $glossary;
    private $account;
    private $groups;
    private $users;

    public static function fromResponse($response)
    {
        return new self(
            Glossary::fromResponse($response['glossary']),
            isset($response['account']) ? ResourceShareEntry::fromResponse($response['account']) : null,
            array_map([ResourceShareEntry::class, 'fromResponse'], $response['groups']),
            array_map([ResourceShareEntry::class, 'fromResponse'], $response['users'])
        );
    }

    public function __construct($glossary, $account, $groups, $users)
    {
        $this->glossary = $glossary;
        $this->account = $account;
        $this->groups = $groups;
        $this->users = $users;
    }

    public function getGlossary() { return $this->glossary; }
    public function getAccount() { return $this->account; }
    public function getGroups() { return $this->groups; }
    public function getUsers() { return $this->users; }
}
