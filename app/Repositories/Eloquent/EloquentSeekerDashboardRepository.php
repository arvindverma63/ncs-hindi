<?php

namespace App\Repositories\Eloquent;

use App\Models\MessageRequest;
use App\Models\Interaction;
use App\Repositories\Contracts\SeekerDashboardInterface;
use Illuminate\Support\Collection;

class EloquentSeekerDashboardRepository implements SeekerDashboardInterface
{
    public function getStats(string $userId): array
    {
        return [
            'sent_requests' => MessageRequest::where('sender_id', $userId)->count(),
            
            'active_connections' => MessageRequest::where('sender_id', $userId)
                ->where('status', 'accepted')
                ->count(),
            
            // Unread messages where the seeker is the receiver (coach_id field stores recipient in schema)
            'unread_messages' => Interaction::where('coach_id', $userId)
                ->where('status', 'sent')
                ->count(),
        ];
    }

    public function getRecentRequests(string $userId, int $limit = 5): Collection
    {
        return MessageRequest::with('receiver') // Eager load the Coach details
            ->where('sender_id', $userId)
            ->latest()
            ->take($limit)
            ->get();
    }
}