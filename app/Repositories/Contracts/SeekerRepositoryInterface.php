<?php

namespace App\Repositories\Contracts;

use App\Models\SeekerProfile;
use Illuminate\Pagination\LengthAwarePaginator;

interface SeekerRepositoryInterface
{
    public function getAll(int $perPage = 10): LengthAwarePaginator;
    public function findById(string $id): ?SeekerProfile;
    public function findByUserId(string $userId): ?SeekerProfile;
    public function create(array $data): SeekerProfile;
    public function update(string $id, array $data): bool;
    public function delete(string $id): bool;
    public function countAll();
}