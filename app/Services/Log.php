<?php

namespace App\Services;

class Log
{
    protected $data;

    public function __construct(mixed $data)
    {
        $this->data = $data;
    }

    public function info()
    {
        var_dump($this->data);
    }
}
