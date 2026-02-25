<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Concerns\HasUuids;

class Interaction extends Model
{
    use HasFactory, HasUuids;

    protected $fillable = [
        'seeker_id',
        'coach_id',
        'subject',
        'message',
        'status',
    ];

    /**
     * Relationship: The Seeker (User) who sent the message.
     */
    public function seeker()
    {
        return $this->belongsTo(User::class, 'seeker_id');
    }

    /**
     * Relationship: The Coach (User) who received the message.
     */
    public function coach()
    {
        return $this->belongsTo(User::class, 'coach_id');
    }

    public function canMessageCoach($seekerId, $coachId)
    {
        return MessageRequest::where('sender_id', $seekerId)
            ->where('receiver_id', $coachId)
            ->where('status', 'accepted')
            ->exists();
    }
}