<?php

namespace App\Services;

class Collection
{
    protected array $data;

    public function __construct(array $data = [])
    {
        $this->data = $data;
    }

    public function map(callable $callback)
    {
        return array_map($callback, $this->data);
    }

    public function toArray()
    {
        return $this->data;
    }
}
