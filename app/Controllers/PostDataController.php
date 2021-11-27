<?php

namespace App\Controllers;

class PostDataController
{
    protected $data = [];

    public function __construct(array $data)
    {
        $this->data = $data;
        $this->headers = getallheaders();
        if(isset($this->headers['Content-Type']) && $this->headers['Content-Type'] == 'application/json') {
            $this->data = json_decode(file_get_contents('php://input'), true);
        } else {
            $this->data = $_REQUEST;
        }
    }
    public function __invoke()
    {
        return jsonResponse([
            'headers' => $this->headers,
            'data' => $this->data
        ]);
    }
}
