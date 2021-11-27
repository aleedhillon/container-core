<?php

namespace App\Models;

use App\Services\DB;
use PDO;

class Post
{
    protected $db;

    protected $table = 'posts';

    public function __construct()
    {
        $this->db = new DB;
    }

    public function getAll(array $attributes = ['*'])
    {
        return $this->db->select($this->table, $attributes);
    }

    public function getOne(int $id, $attributes = ['*'])
    {
        return $this->db->find($this->table, $id);
    }

    public function createOne(array $data)
    {
        $post = [];

        $postId = $this->db->insertOne('posts', $data);

        if ($postId) {
            $post = $this->db->find($this->table, $postId);
        }

        return $post;
    }

    public function where(array $search)
    {
        return count($search) ? $this->db->where('posts', $search) : $this->db->select('posts');
    }

    public function whereFirst(array $search)
    {
        return count($search) ? $this->db->whereFirst('posts', $search) : $this->db->select('posts');
    }
}
