<?php

namespace App\Models;

use App\Services\DB;

class File
{
    protected $db;

    protected $table = 'files';

    public function __construct()
    {
        $this->db = new DB;
    }

    public function getAll(array $attributes = ['*'])
    {
        return $this->db->select($this->table, $attributes);
    }

    public function createOne(array $data)
    {
        $post = [];

        $postId = $this->db->insertOne('files', $data);

        if ($postId) {
            $post = $this->db->find($this->table, $postId);
        }

        return $post;
    }
}
