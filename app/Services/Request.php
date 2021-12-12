<?php

namespace App\Services;

class Request
{
    public array $headers = [];
    public array $data;
    public string $method;
    public string $uri;

    const GET = 'GET';
    const POST = 'POST';

    public function __construct()
    {
        if (function_exists('getallheaders')) {
            $this->headers = getallheaders();
        }

        $this->uri = isset($_SERVER['REQUEST_URI']) ? parse_url($_SERVER['REQUEST_URI'])['path'] : '';
        $this->method =  $_SERVER['REQUEST_METHOD'] ?? '';
        $this->data = $this->getRequestData();

        $log = new Log();

        $log->info('New Request has come through.', [
            'path' => $this->uri,
            'method' => $this->method,
            'data' => $this->data,
            'headers' => $this->headers
        ]);
    }

    public function getHeaders()
    {
        return $this->headers;
    }

    public function getData()
    {
        return $this->data;
    }

    public function wantsJson()
    {
        return (isset($this->headers['Accept']) && $this->headers['Accept'] == 'application/json');
    }

    protected function getRequestData()
    {
        if ($this->method == self::POST) {
            return $this->getPostRequestData();
        }

        if ($this->method == self::GET) {
            return $this->getGetRequestData();
        }

        return [];
    }

    protected function getGetRequestData()
    {
        $data = [];
        $headers = getallheaders();
        if (isset($headers['Content-Type']) && $headers['Content-Type'] == 'application/json') {
            $data = json_decode(file_get_contents('php://input'), true) ?? [];
        } else {
            $data = $_GET;
        }

        return $data;
    }

    protected function getPostRequestData()
    {
        $data = [];
        $headers = getallheaders();
        if (isset($headers['Content-Type']) && $headers['Content-Type'] == 'application/json') {
            $data = json_decode(file_get_contents('php://input'), true) ?? [];
        } else {
            $data = $_POST;
        }

        return $data;
    }

    public function validate(array $fields)
    {
        $errors = [];

        foreach ($fields as $field) {
            if (!isset($this->data[$field])) {
                $errors[$field] = $field . ' is required';
            }
        }

        if (count($errors)) {
            return validationErrors($errors);
        }

        return array_filter($this->data, function ($field, $key) use ($fields) {
            return in_array($key, $fields);
        }, ARRAY_FILTER_USE_BOTH);
    }
}
