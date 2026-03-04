<?php

namespace Lara;

class AccessKey
{
    private $id;
    private $secret;

    /**
     * AccessKey constructor.
     * @param $id string|null Access key ID (or null to auto-retrieve from LARA_ACCESS_KEY_ID env var)
     * @param $secret string|null Access key secret (or null to auto-retrieve from LARA_ACCESS_KEY_SECRET env var)
     */
    public function __construct($id = null, $secret = null)
    {
        // Auto-retrieve from environment if not provided
        $this->id = $id !== null ? $id : getenv('LARA_ACCESS_KEY_ID');
        $this->secret = $secret !== null ? $secret : getenv('LARA_ACCESS_KEY_SECRET');

        if (!$this->id || !$this->secret) {
            throw new LaraException('Access key credentials not provided. Either pass them to the constructor or set LARA_ACCESS_KEY_ID and LARA_ACCESS_KEY_SECRET environment variables.');
        }
    }

    public function getId()
    {
        return $this->id;
    }

    public function getSecret()
    {
        return $this->secret;
    }
}
