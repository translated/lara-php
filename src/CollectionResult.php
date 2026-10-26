<?php

namespace Lara;

/** @template T */
class CollectionResult implements \JsonSerializable
{
    private $items;
    private $total;
    private $page;
    private $size;

    /**
     * @param T[] $items
     * @param int $total Total number of matching resources.
     * @param int|null $page Current page (1-based).
     * @param int|null $size Page size.
     */
    public function __construct($items, $total, $page = null, $size = null)
    {
        $this->items = $items;
        $this->total = $total;
        $this->page = $page;
        $this->size = $size;
    }

    /** @return T[] */
    public function getItems()
    {
        return $this->items;
    }

    /** @return int */
    public function getTotal()
    {
        return $this->total;
    }

    /** @return int|null */
    public function getPage()
    {
        return $this->page;
    }

    /** @return int|null */
    public function getSize()
    {
        return $this->size;
    }

    // Compatibility layer for PHP 8.1+
    #[\ReturnTypeWillChange]
    public function jsonSerialize()
    {
        $result = ['items' => $this->items, 'total' => $this->total];
        if ($this->page !== null) {
            $result['page'] = $this->page;
        }
        if ($this->size !== null) {
            $result['size'] = $this->size;
        }
        return $result;
    }
}
