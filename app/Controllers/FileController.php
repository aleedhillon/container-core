<?php

namespace App\Controllers;

use App\Models\File;
use App\Services\Request;
use App\Services\Storage;

class FileController
{
    protected $file;

    public function __construct()
    {
        $this->file = new File;
    }

    public function get(Request $request)
    {
        $files = $this->file->getAll();

        if ($request->wantsJson()) {
            return response()->json([
                'data' => $files
            ]);
        }

        return view('files/index', [
            'files' => $files
        ], true);
    }

    public function store()
    {
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

        if ($file->size > 1.049e+6 * 2) {
            return validationErrors([
                'file' => [
                    'maximum allowed file size is 2MB'
                ]
            ]);
        }

        return $file;
    }
}
