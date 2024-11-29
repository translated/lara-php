<?php

namespace Lara;

class Memories
{
    /**
     * @var internal\HttpClient
     */
    private $client;
    private $pollingInterval = 2000;

    public function __construct($client)
    {
        $this->client = $client;
    }

    /**
     * @return Memory[]
     * @throws LaraException
     */
    public function getAll()
    {
        return array_map(function ($e) {
            return Memory::fromResponse($e);
        }, $this->client->get("/memories"));
    }

    /**
     * @param $name string
     * @param $external_id string|null
     * @return Memory
     * @throws LaraException
     */
    public function create($name, $external_id = null)
    {
        return Memory::fromResponse($this->client->post("/memories", [
            'name' => $name,
            'external_id' => $external_id
        ]));
    }

    /**
     * @param $id string
     * @return Memory|null
     * @throws LaraException
     */
    public function get($id)
    {
        try {
            return Memory::fromResponse($this->client->get("/memories/$id"));
        } catch (LaraApiException $e) {
            if ($e->getCode() == 404) return null;
            throw $e;
        }
    }

    /**
     * @param $id string
     * @return Memory
     * @throws LaraException
     */
    public function delete($id)
    {
        return Memory::fromResponse($this->client->delete("/memories/$id"));
    }

    /**
     * @param $id string
     * @param $name string
     * @return Memory
     * @throws LaraException
     */
    public function update($id, $name)
    {
        return Memory::fromResponse($this->client->put("/memories/$id", [
            'name' => $name,
        ]));
    }

    /**
     * @param $ids string|string[]
     * @return Memory|Memory[]
     * @throws LaraException
     */
    public function connect($ids)
    {
        $isArray = is_array($ids);

        $memories = array_map(function ($e) {
            return Memory::fromResponse($e);
        }, $this->client->post("/memories/connect", [
            'memories' => $ids
        ]));

        return $isArray ? $memories : $memories[0];
    }
}