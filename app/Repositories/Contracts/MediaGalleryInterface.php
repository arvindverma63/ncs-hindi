<?php
namespace App\Repositories\Contracts;

use Illuminate\Http\UploadedFile;

interface MediaGalleryInterface
{
    public function getAll(array $filters = []);
    public function findById(string $id);
    public function store(array $data, UploadedFile $file);
    public function delete(string $id);
}