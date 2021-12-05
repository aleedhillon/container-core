<?php

namespace App\Controllers;

use App\Models\CSV;
use App\Models\CSVData;

class CSVController
{
    protected CSV $csv;
    protected CSVData $csvData;

    public function __construct()
    {
        $this->csv = new CSV;
        $this->csvData = new CSVData;
    }
    public function index()
    {
        $csvs = $this->csv->get();

        $csvs = array_map(function($csv) {
            $data = $this->csvData->where('csv_id', $csv['id']);

            $csv['data'] = [];
            foreach($data as $row) {
                $rowData = json_decode($row['data'], true);
                array_push($csv['data'], [
                    'name' => $rowData['Name'],
                    'phone' => $rowData['Phone 1 - Value']
                ]);
            }

            return $csv;
        }, $csvs);

        return response()->json([
            'data' => $csvs
        ]);
    }

    public function store()
    {
        $file = $this->validate();

        $fileName = $file->name;

        $data = [];

        $file = fopen($file->tmp_name, 'r');

        $headings = fgetcsv($file);

        while (!feof($file)) {
            $row = fgetcsv($file);
            if ($row) {
                $row = array_map(function ($value) {
                    return mb_convert_encoding($value, 'UTF-8', 'UTF-8');
                }, $row);
                $row = array_combine($headings, $row);
                array_push($data, $row);
            }
        }

        fclose($file);

        $csv = $this->csv->create([
            'name' => $fileName
        ]);

        $csv['data'] = [];

        foreach($data as $row) {
            $csvData = $this->csvData->create([
                'csv_id' => $csv['id'],
                'data' => json_encode($row)
            ]);

            $csvData['data'] = json_decode($csvData['data']);

            array_push($csv['data'], $csvData);
        }

        return response()->json([
            'data' => $csv
        ]);
    }

    protected function validate()
    {
        if (!isset($_FILES['file'])) {
            return validationErrors([
                'file' => ['file is required']
            ]);
        }

        $file = (object) $_FILES['file'];

        if ($file->type !== 'text/csv') {
            return validationErrors([
                'file' => [
                    'Only text/csv type file is allowed.'
                ]
            ]);
        }

        return $file;
    }
}
