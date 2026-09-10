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

    public function getShares($id)
    {
        return GlossaryShares::fromResponse($this->client->get("/v2/glossaries/$id/shares"));
    }

    public function addAccountShare($id, $name = null)
    {
        return Glossary::fromResponse($this->client->post("/v2/glossaries/$id/shares", ['name' => $name]));
    }

    public function renameAccountShare($id, $name)
    {
        return Glossary::fromResponse($this->client->put("/v2/glossaries/$id/shares", ['name' => $name]));
    }

    public function revokeAccountShare($id)
    {
        return Glossary::fromResponse($this->client->delete("/v2/glossaries/$id/shares"));
    }

    public function addGroupShare($id, $groupId, $name = null)
    {
        return Glossary::fromResponse($this->client->post("/v2/glossaries/$id/shares/groups/$groupId", ['name' => $name]));
    }

    public function renameGroupShare($id, $groupId, $name)
    {
        return Glossary::fromResponse($this->client->put("/v2/glossaries/$id/shares/groups/$groupId", ['name' => $name]));
    }

    public function revokeGroupShare($id, $groupId)
    {
        return Glossary::fromResponse($this->client->delete("/v2/glossaries/$id/shares/groups/$groupId"));
    }

    /**
     * @param $id string
     * @param $file string
     * @param $options GlossaryImportOptions|null
     * @return GlossaryImport
     * @throws LaraException
     */
    public function importFile($id, $file, $options = null)
    {
        if ($options === null) {
            $options = new GlossaryImportOptions();
        }
        $body = [
            'compression' => $options->getGzip() ? 'gzip' : null,
            'content_type' => $options->getContentType()
        ];
        $callbackUrl = $options->getCallbackUrl();
        if ($callbackUrl !== null) {
            $body['callback_url'] = $callbackUrl;
        }
        return GlossaryImport::fromResponse($this->client->post("/v2/glossaries/$id/import", $body, [
            'csv' => $file
        ]));
    }

    /**
     * @param $id string
     * @param $csv string
     * @param $gzip bool
     * @param $callbackUrl string|null
     * @return GlossaryImport
     * @throws LaraException
     * @deprecated Use importFile() instead.
     */
    public function importCsv($id, $csv, $gzip = false, $callbackUrl = null)
    {
        return $this->importFile($id, $csv, new GlossaryImportOptions([
            'gzip' => $gzip,
            'callbackUrl' => $callbackUrl
        ]));
    }

    /**
     * @param $id string
     * @param $csv string
     * @param $contentType string
     * @param $gzip bool
     * @param $callbackUrl string|null
     * @return GlossaryImport
     * @throws LaraException
     * @deprecated Use importFile() instead.
     */
    public function importCsvWithContentType($id, $csv, $contentType, $gzip = false, $callbackUrl = null)
    {
        if (!in_array($contentType, [GlossaryFileFormat::CSV_TABLE_UNI, GlossaryFileFormat::CSV_TABLE_MULTI], true)) {
            throw new \InvalidArgumentException("importCsvWithContentType only supports CSV formats; use importFile for TBX files.");
        }
        return $this->importFile($id, $csv, new GlossaryImportOptions([
            'contentType' => $contentType,
            'gzip' => $gzip,
            'callbackUrl' => $callbackUrl
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
     * @param $callbackUrl string
     * @param $contentType string
     * @param $source string|null
     * @return GlossaryExport
     * @throws LaraException
     */
    public function exportAsync($id, $callbackUrl, $contentType, $source = null)
    {
        $params = [
            'callback_url' => $callbackUrl,
            'content_type' => $contentType
        ];
        if ($source !== null) {
            $params['source'] = $source;
        }
        return GlossaryExport::fromResponse($this->client->get("/v2/glossaries/$id/export/async", $params));
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
