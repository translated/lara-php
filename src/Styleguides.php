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
     * @param $name string
     * @param $content string
     * @return Styleguide
     * @throws LaraException
     */
    public function create($name, $content)
    {
        return Styleguide::fromResponse($this->client->post("/v2/styleguides", [
            'name' => $name,
            'content' => $content,
        ]));
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

    /**
     * @param $id string
     * @return Styleguide
     * @throws LaraException
     */
    public function delete($id)
    {
        return Styleguide::fromResponse($this->client->delete("/v2/styleguides/$id"));
    }

    /**
     * @param $id string
     * @param $name string|null
     * @param $content string|null
     * @return Styleguide
     * @throws LaraException
     */
    public function update($id, $name = null, $content = null)
    {
        $data = [];
        if ($name !== null) $data['name'] = $name;
        if ($content !== null) $data['content'] = $content;
        return Styleguide::fromResponse($this->client->put("/v2/styleguides/$id", $data));
    }
}
