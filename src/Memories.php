<?php

namespace Lara;

class Memories
{
    /**
     * @var Internal\HttpClient
     */
    private $client;
    private $pollingInterval = 2;

    public function __construct($client)
    {
        $this->client = $client;
    }

    /**
     * Get all memories
     * @return Memory[]
     * @throws LaraException
     */
    public function getAll()
    {
        return array_map(function ($e) {
            return Memory::fromResponse($e);
        }, $this->client->get("/v2/memories"));
    }

    /**
     * @param $name string
     * @param $external_id string|null
     * @return Memory
     * @throws LaraException
     */
    public function create($name, $external_id = null)
    {
        return Memory::fromResponse($this->client->post("/v2/memories", [
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
            return Memory::fromResponse($this->client->get("/v2/memories/$id"));
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
        return Memory::fromResponse($this->client->delete("/v2/memories/$id"));
    }

    /**
     * @param $id string
     * @param $name string
     * @return Memory
     * @throws LaraException
     */
    public function update($id, $name)
    {
        return Memory::fromResponse($this->client->put("/v2/memories/$id", [
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
        }, $this->client->post("/v2/memories/connect", [
            'ids' => $isArray ? $ids : [$ids]
        ]));

        return $isArray ? $memories : $memories[0];
    }

    /**
     * @param $id string
     * @param $tmx string
     * @param $gzip bool
     * @return MemoryImport
     * @throws LaraException
     */
    public function importTmx($id, $tmx, $gzip = false)
    {
        return MemoryImport::fromResponse($this->client->post("/v2/memories/$id/import", [
            'compression' => $gzip ? 'gzip' : null
        ], [
            'tmx' => $tmx
        ]));
    }

    /**
     * @param $id string
     * @return MemoryImport
     * @throws LaraException
     */
    public function getImportStatus($id)
    {
        return MemoryImport::fromResponse($this->client->get("/v2/memories/imports/$id"));
    }

    /**
     * @param $id string|string[]
     * @param $source string
     * @param $target string
     * @param $sentence string
     * @param $translation string
     * @param $tuid string|null
     * @param $sentenceBefore string|null
     * @param $sentenceAfter string|null
     * @param $headers array|null
     * @return MemoryImport
     * @throws LaraException
     */
    public function addTranslation($id, $source, $target, $sentence, $translation,
                                   $tuid = null, $sentenceBefore = null, $sentenceAfter = null, $headers = null)
    {
        $body = [
            'source' => $source,
            'target' => $target,
            'sentence' => $sentence,
            'translation' => $translation,
            'tuid' => $tuid,
            'sentence_before' => $sentenceBefore,
            'sentence_after' => $sentenceAfter
        ];

        if (is_array($id)) {
            $body['ids'] = $id;
            return MemoryImport::fromResponse($this->client->put("/v2/memories/content", $body, null, $headers));
        } else {
            return MemoryImport::fromResponse($this->client->put("/v2/memories/$id/content", $body, null, $headers));
        }
    }

    /**
     * @param $id string|string[]
     * @param $source string
     * @param $target string
     * @param $sentence string
     * @param $translation string
     * @param $tuid string|null
     * @param $sentenceBefore string|null
     * @param $sentenceAfter string|null
     * @return MemoryImport
     * @throws LaraException
     */
    public function deleteTranslation($id, $source, $target, $sentence, $translation,
                                      $tuid = null, $sentenceBefore = null, $sentenceAfter = null)
    {
        $body = [
            'source' => $source,
            'target' => $target,
            'sentence' => $sentence,
            'translation' => $translation,
            'tuid' => $tuid,
            'sentence_before' => $sentenceBefore,
            'sentence_after' => $sentenceAfter
        ];

        if (is_array($id)) {
            $body['ids'] = $id;
            return MemoryImport::fromResponse($this->client->delete("/v2/memories/content", $body));
        } else {
            return MemoryImport::fromResponse($this->client->delete("/v2/memories/$id/content", $body));
        }
    }

    /**
     * @param $import MemoryImport
     * @param $maxWaitTime int seconds
     * @return MemoryImport
     * @throws LaraException
     */
    public function waitForImport($import, $maxWaitTime = 0)
    {
        $start = time();
        while ($import->getProgress() < 1.0) {
            if ($maxWaitTime > 0 && time() - $start > $maxWaitTime)
                throw new LaraTimeoutException();

            sleep($this->pollingInterval);

            $import = $this->getImportStatus($import->getId());
        }

        return $import;
    }

}