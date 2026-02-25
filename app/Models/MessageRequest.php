<?php 

namespace App\Models;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Concerns\HasUuids;
use Illuminate\Database\Eloquent\Factories\HasFactory;

class MessageRequest extends Model
{
    use HasFactory, HasUuids;

    protected $fillable = [
        'sender_id',
        'receiver_id',
        'status',
        'message',
    ];

    /**
     * The User who sent the request.
     */
    public function sender()
    {
        return $this->belongsTo(User::class, 'sender_id');
    }

    /**
     * The User who received the request.
     */
    public function receiver()
    {
        return $this->belongsTo(User::class, 'receiver_id');
    }
}