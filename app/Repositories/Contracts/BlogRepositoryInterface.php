<?php

namespace App\Repositories\Contracts;

use App\Models\Blog;
use Illuminate\Pagination\LengthAwarePaginator;

interface BlogRepositoryInterface
{
    public function getAll(int $perPage = 10): LengthAwarePaginator;
    public function findById(string $id): ?Blog;
    public function findBySlug(string $slug): ?Blog;
    public function create(array $data): Blog;
    public function update(string $id, array $data): bool;
    public function delete(string $id): bool;
    public function updateStatus(string $id, bool $status): bool;
}