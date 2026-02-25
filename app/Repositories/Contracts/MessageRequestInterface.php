<?php

namespace App\Repositories\Contracts;

use App\Models\MessageRequest;
use Illuminate\Pagination\LengthAwarePaginator;

interface MessageRequestInterface
{
    public function getPendingForUser(string $userId): LengthAwarePaginator;
    public function getSentByUser(string $userId): LengthAwarePaginator;
    public function findById(string $id): ?MessageRequest;
    public function sendRequest(array $data): MessageRequest;
    public function updateStatus(string $id, string $status): bool;
    public function hasAcceptedRequest(string $senderId, string $receiverId): bool;
    public function getAcceptedCount(string $userId): int;
}