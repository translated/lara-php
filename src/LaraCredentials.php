<?php

namespace Lara;

/**
 * @deprecated Use AccessKey instead.
 */
class LaraCredentials extends AccessKey
{
    /**
     * LaraCredentials constructor.
     * @param $accessKeyId string|null Access key ID (or null to auto-retrieve from LARA_ACCESS_KEY_ID env var)
     * @param $accessKeySecret string|null Access key secret (or null to auto-retrieve from LARA_ACCESS_KEY_SECRET env var)
     * @deprecated Use AccessKey instead.
     */
    public function __construct($accessKeyId = null, $accessKeySecret = null)
    {
        parent::__construct($accessKeyId, $accessKeySecret);
    }

    /**
     * @deprecated Use AccessKey::getId() instead.
     * @return string
     */
    public function getAccessKeyId()
    {
        return $this->getId();
    }

    /**
     * @deprecated Use AccessKey::getSecret() instead.
     * @return string
     */
    public function getAccessKeySecret()
    {
        return $this->getSecret();
    }

}