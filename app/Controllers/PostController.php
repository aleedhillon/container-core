<?php

namespace App\Controllers;

use App\Models\Post;
use App\Services\DB;
use PDO;

class PostController
{

    protected $post;

    public function __construct()
    {
        $this->post = new Post;
    }

    public function index(array $data)
    {
        $data = $this->post->where($data);

        return jsonResponse([
            'data' => $data
        ]);
    }

    public function store(array $data)
    {
        $erros = [];

        if (!isset($data['title'])) {
            $erros['title'][] = 'title field is required.';
        }

        if ($this->post->whereFirst(['title' => $data['title']])) {
            $erros['title'][] = 'title already exist';
        }

        if (!isset($data['body'])) {
            $erros['body'][] = 'body field is required';
        }

        if (count($erros)) {
            return validationErrors($erros);
        }

        $postId = $this->post->createOne($data);

        return jsonResponse([
            'post_id' => $postId
        ]);
    }
}
