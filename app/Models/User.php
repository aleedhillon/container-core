<?php

namespace App\Models;

use App\Services\Model;

class User extends Model
{
    public function getTable(): string
    {
        return 'users';
    }
}
