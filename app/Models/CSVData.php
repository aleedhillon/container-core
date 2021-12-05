<?php

namespace App\Models;

use App\Services\Model;

class CSVData extends Model
{
    public function getTable(): string
    {
        return 'csv_data';
    }
}
