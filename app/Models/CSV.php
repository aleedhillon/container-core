<?php

namespace App\Models;

use App\Services\Model;

class CSV extends Model
{
    public function getTable(): string
    {
        return 'csvs';
    }
}
