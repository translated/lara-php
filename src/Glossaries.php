<?php

namespace Lara;

class Glossaries
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
     * @return Glossary[]
     * @throws LaraException
     */
    public function getAll()
    {
        return array_map(function ($e) {
            return Glossary::fromResponse($e);
        }, $this->client->get("/glossaries"));
    }

    /**
     * @param $name string
     * @return Glossary
     * @throws LaraException
     */
    public function create($name)
    {
        return Glossary::fromResponse($this->client->post("/glossaries", [
            'name' => $name
        ]));
    }

    /**
     * @param $id string
     * @return Glossary|null
     * @throws LaraException
     */
    public function get($id)
    {
        try {
            return Glossary::fromResponse($this->client->get("/glossaries/$id"));
        } catch (LaraApiException $e) {
            if ($e->getCode() == 404) return null;
            throw $e;
        }
    }

    /**
     * @param $id string
     * @return Glossary
     * @throws LaraException
     */
    public function delete($id)
    {
        return Glossary::fromResponse($this->client->delete("/glossaries/$id"));
    }

    /**
     * @param $id string
     * @param $name string
     * @return Glossary
     * @throws LaraException
     */
    public function update($id, $name)
    {
        return Glossary::fromResponse($this->client->put("/glossaries/$id", [
            'name' => $name,
        ]));
    }

    /**
     * @param $id string
     * @param $csv string
     * @param $gzip bool
     * @return GlossaryImport
     * @throws LaraException
     */
    public function importCsv($id, $csv, $gzip = false)
    {
        return GlossaryImport::fromResponse($this->client->post("/glossaries/$id/import", [
            'compression' => $gzip ? 'gzip' : null
        ], [
            'csv' => $csv
        ]));
    }

    /**
     * @param $id string
     * @return GlossaryImport
     * @throws LaraException
     */
    public function getImportStatus($id)
    {
        return GlossaryImport::fromResponse($this->client->get("/glossaries/imports/$id"));
    }

    /**
     * @param $import MemoryImport
     * @param $maxWaitTime int seconds
     * @return GlossaryImport
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

    /**
     * @return GlossaryCounts
     * @throws LaraException
     */
    public function counts($id)
    {
        return GlossaryCounts::fromResponse($this->client->get("/glossaries/$id/counts"));
    }

    /**
     * @param $id string
     * @param $contentType string
     * @param $source string | null
     * @return string
     * @throws LaraException
     */
    public function export($id, $contentType, $source)
    {
        return $this->client->get("/glossaries/$id/export", [
            'content_type' => $contentType,
            'source' => $source
        ]);
    }
}
