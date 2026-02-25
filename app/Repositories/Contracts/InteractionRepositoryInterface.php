<?php

namespace App\Repositories\Contracts;

use App\Models\Interaction;
use Illuminate\Pagination\LengthAwarePaginator;
use Illuminate\Support\Collection;

interface InteractionRepositoryInterface
{
    public function getAll(int $perPage = 10): LengthAwarePaginator;
    public function getForCoach(string $coachId, int $perPage = 10): LengthAwarePaginator;
    public function getForSeeker(string $seekerId, int $perPage = 10): LengthAwarePaginator;
    public function findById(string $id): ?Interaction;
    public function create(array $data): Interaction;
    public function adminCreate(array $data): Interaction;
    public function updateStatus(string $id, string $status): bool;
    public function delete(string $id): bool;
    public function getConversation(string $userId1, string $userId2, int $limit = 50): Collection;
    public function markAsRead(string $receiverId, string $senderId): bool;
}