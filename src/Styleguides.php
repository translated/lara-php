<?php

namespace Lara;

class Styleguides
{
    /**
     * @var Internal\HttpClient
     */
    private $client;

    public function __construct($client)
    {
        $this->client = $client;
    }

    /**
     * Get all styleguides
     * @return Styleguide[]
     * @throws LaraException
     */
    public function getAll()
    {
        return array_map(function ($e) {
            return Styleguide::fromResponse($e);
        }, $this->client->get("/v2/styleguides"));
    }

    /**
     * @param $id string
     * @return Styleguide|null
     * @throws LaraException
     */
    public function get($id)
    {
        try {
            return Styleguide::fromResponse($this->client->get("/v2/styleguides/$id"));
        } catch (LaraApiException $e) {
            if ($e->getCode() == 404) return null;
            throw $e;
        }
    }
}
