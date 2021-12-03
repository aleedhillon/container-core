<?php

namespace App\Services;

class Response
{
    protected string $response;
    protected int $code;

    public function __construct(string $response = '', int $code = 200)
    {
        $this->code = $code;
        $this->response = $response;
    }

    public function render()
    {
        http_response_code($this->code);
        return $this->response;
    }

    public function json(array $data, int $code = 200)
    {
        http_response_code($code ?? $this->code);

        header('Content-Type: application/json; charset=utf-8');

        return json_encode($data);
    }

    public static function make(string $response, int $code)
    {
        return new static($response, $code);
    }

    public function __toString()
    {
        return $this->render();
    }
}
