<?php

namespace App\Controllers;

use App\Models\Post;
use App\Services\DB;
use App\Services\Request;
use App\Services\View;
use PDO;

class PostController
{
    protected $post;

    public function __construct()
    {
        // checkAuth();
        $this->post = new Post;
    }

    public function index(Request $request)
    {
        $posts = $this->post->where($request->data);

        if ($request->wantsJson()) {
            return jsonResponse([
                'data' => $posts
            ]);
        }

        return view('posts/index', [
            'posts' => $posts
        ], true);
    }

    public function store(Request $request)
    {
        $erros = [];

        $data = $request->getData();

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
