<?php

namespace App\Repositories\Eloquent;

use App\Models\Interaction;
use App\Repositories\Contracts\InteractionRepositoryInterface;
use Illuminate\Pagination\LengthAwarePaginator;
use Illuminate\Support\Collection;

class EloquentInteractionRepository implements InteractionRepositoryInterface
{
    public function getAll(int $perPage = 10): LengthAwarePaginator
    {
        return Interaction::with(['seeker', 'coach'])
            ->latest()
            ->paginate($perPage);
    }

    public function getForCoach(string $coachId, int $perPage = 10): LengthAwarePaginator
    {
        // This query gets the latest interaction for each unique seeker_id for this coach
        return Interaction::where('coach_id', $coachId)
            ->with('seeker')
            ->whereIn('id', function($query) use ($coachId) {
                $query->selectRaw('max(id)')
                    ->from('interactions')
                    ->where('coach_id', $coachId)
                    ->groupBy('seeker_id');
            })
            ->latest()
            ->paginate($perPage);
    }

    public function getForSeeker(string $seekerId, int $perPage = 10): LengthAwarePaginator
    {
        return Interaction::where('seeker_id', $seekerId)
            ->with('coach')
            ->latest()
            ->paginate($perPage);
    }

    public function findById(string $id): ?Interaction
    {
        return Interaction::with(['seeker', 'coach'])->find($id);
    }

    public function create(array $data): Interaction
    {
        return Interaction::create($data);
    }

    public function adminCreate(array $data): Interaction
    {
        return Interaction::create([
            'seeker_id' => $data['seeker_id'],
            'coach_id'  => $data['coach_id'],
            'subject'   => $data['subject'],
            'message'   => $data['message'],
            'status'    => 'sent',
        ]);
    }

    public function updateStatus(string $id, string $status): bool
    {
        $interaction = Interaction::find($id);
        
        if (!$interaction) {
            return false;
        }

        return $interaction->update(['status' => $status]);
    }

    public function delete(string $id): bool
    {
        return Interaction::destroy($id);
    }

    public function getConversation(string $userId1, string $userId2, int $limit = 50): Collection
    {
        return Interaction::where(function ($query) use ($userId1, $userId2) {
                $query->where('seeker_id', $userId1)
                      ->where('coach_id', $userId2);
            })
            ->orWhere(function ($query) use ($userId1, $userId2) {
                $query->where('seeker_id', $userId2)
                      ->where('coach_id', $userId1);
            })
            ->oldest()
            ->take($limit)
            ->get();
    }

    public function markAsRead(string $receiverId, string $senderId): bool
    {
        return Interaction::where('coach_id', $receiverId)
            ->where('seeker_id', $senderId)
            ->where('status', 'sent')
            ->update(['status' => 'read']);
    }
}