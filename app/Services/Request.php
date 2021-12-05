<?php

namespace App\Services;

class Request
{
    public array $headers;
    public array $data;

    const GET = 'GET';
    const POST = 'POST';

    public function __construct(string $requestMethod)
    {
        $this->headers = getallheaders();
        $this->data = $this->getRequestData($requestMethod);
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

    protected function getRequestData(string $httpMethod)
    {
        if ($httpMethod == self::POST) {
            return $this->getPostRequestData();
        }

        if ($httpMethod == self::GET) {
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
