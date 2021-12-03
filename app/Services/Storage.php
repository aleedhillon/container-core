<?php

namespace App\Services;

class Storage
{
    const STORAGE_DIR = __DIR__ . '/../../public/storage/files';

    public static function saveImage(string $tmpPath)
    {
        $fileName = uniqid() . '.jpeg';
        return move_uploaded_file($tmpPath, realpath(self::STORAGE_DIR) . '\\' . $fileName) ? $fileName : null;
    }

    public static function getFilePath(string $fileName)
    {
        return realpath(self::STORAGE_DIR) . '\\' . $fileName;
    }
}
