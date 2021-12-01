<?php

namespace App\Controllers;

use App\Models\File;
use App\Services\Storage;

class FileController
{
    protected $file;

    public function __construct()
    {
        $this->file = new File;
    }

    public function get()
    {
        $files = $this->file->getAll();

        // return jsonResponse($files);

        $view = '';

        foreach($files as $file) {
            $view .= '<div><h3>'. $file['name'] .'</h3><img width="200" src="' . '/storage/files/' . $file['path'] . '"></div>';
        }

        echo $view;
    }

    public function store()
    {
        return jsonResponse($_FILES);
        $tmpFile = $this->validate();

        $path = Storage::saveImage($tmpFile->tmp_name);

        $file = $this->file->createOne([
            'name' => $tmpFile->name,
            'path' => $path
        ]);

        return jsonResponse([
            'data' => $file
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

        if ($file->type !== 'image/jpeg') {
            return validationErrors([
                'file' => [
                    'Only image/jpeg type file is allowed.'
                ]
            ]);
        }

        if($file->size > 1.049e+6 * 2) {
            return validationErrors([
                'file' => [
                    'maximum allowed file size is 2MB'
                ]
            ]);
        }

        return $file;
    }
}
