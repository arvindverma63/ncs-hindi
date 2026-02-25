<?php

namespace App\Repositories\Eloquent;

use App\Models\MessageRequest;
use App\Repositories\Contracts\MessageRequestInterface;
use Illuminate\Pagination\LengthAwarePaginator;

class EloquentMessageRequestRepository implements MessageRequestInterface
{
    public function getPendingForUser(string $userId): LengthAwarePaginator
    {
        return MessageRequest::where('receiver_id', $userId)
            ->where('status', 'pending')
            ->with('sender')
            ->latest()
            ->paginate(10);
    }

    public function getSentByUser(string $userId): LengthAwarePaginator
    {
        return MessageRequest::where('sender_id', $userId)
            ->with('receiver')
            ->latest()
            ->paginate(10);
    }

    public function findById(string $id): ?MessageRequest
    {
        return MessageRequest::with(['sender', 'receiver'])->find($id);
    }

    public function sendRequest(array $data): MessageRequest
    {
        return MessageRequest::create([
            'sender_id'   => $data['sender_id'],
            'receiver_id' => $data['receiver_id'],
            'message'     => $data['message'] ?? null,
            'status'      => 'pending'
        ]);
    }

    public function updateStatus(string $id, string $status): bool
    {
        $request = MessageRequest::find($id);
        if (!$request) return false;

        return $request->update(['status' => $status]);
    }

    public function hasAcceptedRequest(string $senderId, string $receiverId): bool
    {
        return MessageRequest::where(function($query) use ($senderId, $receiverId) {
                $query->where('sender_id', $senderId)->where('receiver_id', $receiverId);
            })
            ->orWhere(function($query) use ($senderId, $receiverId) {
                $query->where('sender_id', $receiverId)->where('receiver_id', $senderId);
            })
            ->where('status', 'accepted')
            ->exists();
    }

    public function getAcceptedCount(string $userId): int
    {
        return MessageRequest::where('receiver_id', $userId)
            ->where('status', 'accepted')
            ->count();
    }
}