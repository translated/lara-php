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
     * Get all glossaries
     * @return Glossary[]
     * @throws LaraException
     */
    public function getAll()
    {
        return array_map(function ($e) {
            return Glossary::fromResponse($e);
        }, $this->client->get("/v2/glossaries"));
    }

    /**
     * @param $name string
     * @return Glossary
     * @throws LaraException
     */
    public function create($name)
    {
        return Glossary::fromResponse($this->client->post("/v2/glossaries", [
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
            return Glossary::fromResponse($this->client->get("/v2/glossaries/$id"));
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
        return Glossary::fromResponse($this->client->delete("/v2/glossaries/$id"));
    }

    /**
     * @param $id string
     * @param $name string
     * @return Glossary
     * @throws LaraException
     */
    public function update($id, $name)
    {
        return Glossary::fromResponse($this->client->put("/v2/glossaries/$id", [
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
        return $this->importCsvWithContentType($id, $csv, GlossaryFileFormat::CSV_TABLE_UNI, $gzip);
    }

    /**
     * @param $id string
     * @param $csv string
     * @param $contentType string
     * @param $gzip bool
     * @return GlossaryImport
     * @throws LaraException
     */
    public function importCsvWithContentType($id, $csv, $contentType, $gzip = false)
    {
        return GlossaryImport::fromResponse($this->client->post("/v2/glossaries/$id/import", [
            'compression' => $gzip ? 'gzip' : null,
            'content_type' => $contentType
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
        return GlossaryImport::fromResponse($this->client->get("/v2/glossaries/imports/$id"));
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
        return GlossaryCounts::fromResponse($this->client->get("/v2/glossaries/$id/counts"));
    }

    /**
     * @param $id string
     * @param $contentType string
     * @param $source string|null
     * @return string
     * @throws LaraException
     */
    public function export($id, $contentType, $source = null)
    {
        return $this->client->get("/v2/glossaries/$id/export", [
            'content_type' => $contentType,
            'source' => $source
        ]);
    }

    /**
     * @param $id string
     * @param $terms array<array{language: string, value: string}>
     * @param $guid string|null
     * @return GlossaryImport
     * @throws LaraException
     */
    public function addOrReplaceEntry($id, $terms, $guid = null)
    {
        $data = ['terms' => $terms];
        if ($guid !== null) {
            $data['guid'] = $guid;
        }
        return GlossaryImport::fromResponse($this->client->put("/v2/glossaries/$id/content", $data));
    }

    /**
     * @param $id string
     * @param $term array{language: string, value: string}|null
     * @param $guid string|null
     * @return GlossaryImport
     * @throws LaraException
     */
    public function deleteEntry($id, $term = null, $guid = null)
    {
        $data = [];
        if ($term !== null) {
            $data['term'] = $term;
        }
        if ($guid !== null) {
            $data['guid'] = $guid;
        }
        return GlossaryImport::fromResponse($this->client->delete("/v2/glossaries/$id/content", $data));
    }
}
