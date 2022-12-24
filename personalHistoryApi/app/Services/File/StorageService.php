<?php

namespace App\Services\File;

use Illuminate\Support\Facades\Storage;

class StorageService
{

    public function uploadFile(
        $file,
        $filePath,
        $fileName,
    ) {
        $fileFullPath = $filePath . '/' . $fileName;
        $this->deleteFile($filePath . '/' . $fileName);
        $disk = $this->getDisk();
        $disk->putFileAs($filePath, $file, $fileName);
        return $disk->url($fileFullPath);
    }

    public function deleteFile($fileFullPath)
    {
        $disk = $this->getDisk();
        if ($disk->exists($fileFullPath)) {
            $disk->delete($fileFullPath);
        }
    }

    /**
     * Return the gcs storage disk.
     *
     * @return \Illuminate\Filesystem\FilesystemAdapter
     */
    private function getDisk()
    {
        return Storage::disk('gcs');
    }
}
