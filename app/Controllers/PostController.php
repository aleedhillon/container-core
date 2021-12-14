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
    protected $request;

    public function __construct(Post $post, Request $request)
    {
        $this->post = $post;
        $this->request = $request;
    }

    public function index()
    {
        $posts = $this->post->where($this->request->data);

        if ($this->request->wantsJson()) {
            return jsonResponse([
                'data' => $posts
            ]);
        }

        return redirect('/');
        
        return view('posts/index', [
            'posts' => $posts
        ], true);
    }

    public function store()
    {
        $erros = [];

        $data = $this->request->getData();

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

        $postId = $this->post->create($data);

        return jsonResponse([
            'post_id' => $postId
        ]);
    }
}
