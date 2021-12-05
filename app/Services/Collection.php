<?php

namespace App\Services;

use ArrayAccess;

class Collection implements ArrayAccess
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

    public function offsetSet($offset, $value)
    {
        if (is_null($offset)) {
            $this->data[] = $value;
        } else {
            $this->data[$offset] = $value;
        }
    }

    public function offsetExists($offset)
    {
        return isset($this->data[$offset]);
    }

    public function offsetUnset($offset)
    {
        unset($this->data[$offset]);
    }

    public function offsetGet($offset)
    {
        return isset($this->data[$offset]) ? $this->data[$offset] : null;
    }

    public function toArray()
    {
        return $this->data;
    }
}
