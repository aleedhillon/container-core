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
}
