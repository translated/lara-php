<?php

namespace Lara;

/** Options for filtering and paginating resource lists. */
class ResourceQueryOptions
{
    private $search = null;
    private $page = null;
    private $size = null;

    public function __construct($options = [])
    {
        if (isset($options['search']))
            $this->setSearch($options['search']);
        if (isset($options['page']))
            $this->setPage($options['page']);
        if (isset($options['size']))
            $this->setSize($options['size']);
    }

    /** @return string|null */
    public function getSearch()
    {
        return $this->search;
    }

    /**
     * Filter resources by their visible name. Enables pagination when provided.
     * @param string|null $search
     */
    public function setSearch($search)
    {
        $this->search = $search;
    }

    /** @return int|null */
    public function getPage()
    {
        return $this->page;
    }

    /**
     * Page number (1-based). Enables pagination when provided.
     * @param int|null $page
     */
    public function setPage($page)
    {
        $this->page = $page;
    }

    /** @return int|null */
    public function getSize()
    {
        return $this->size;
    }

    /**
     * Page size (1-200). Enables pagination when provided.
     * @param int|null $size
     */
    public function setSize($size)
    {
        $this->size = $size;
    }

    /** @return array */
    public function toParams()
    {
        return array_filter([
            'search' => $this->search,
            'page' => $this->page,
            'size' => $this->size
        ], function ($value) {
            return $value !== null;
        });
    }
}
