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

    public function find(int $id, $attributes = ['*'])
    {
        return $this->db->find($this->table, $id);
    }

    public function create(array $data)
    {
        $postId = $this->db->insertOne($this->table, $data);

        return $this->find($postId);
    }

    public function where(array $search)
    {
        return count($search) ? $this->db->where($this->table, $search) : $this->db->select($this->table);
    }

    public function whereFirst(array $search)
    {
        return count($search) ? $this->db->whereFirst($this->table, $search) : $this->db->select($this->table);
    }
}
