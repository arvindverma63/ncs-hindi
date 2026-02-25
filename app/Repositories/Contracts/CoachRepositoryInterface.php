<?php

namespace App\Repositories\Contracts;

use App\Models\CoachProfile;
use Illuminate\Pagination\LengthAwarePaginator;

interface CoachRepositoryInterface
{
    public function getAll(int $perPage = 10): LengthAwarePaginator;
    public function getPending(int $perPage = 10): LengthAwarePaginator;
    public function findById(string $id): ?CoachProfile;
    public function findByUserId(string $userId): ?CoachProfile;
    public function create(array $data): CoachProfile;
    public function update(string $id, array $data): bool;
    public function updateStatus(string $id, string $status): bool;
    public function delete(string $id): bool;
    public function countApproved();
    public function countPending();
    public function getRecentPending($limit = 5);
}