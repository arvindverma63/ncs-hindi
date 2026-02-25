<?php

namespace App\Notifications;

use Illuminate\Bus\Queueable;
use Illuminate\Notifications\Notification;

class NewMessageNotification extends Notification
{
    use Queueable;

    public function __construct(protected $messageData) {}

    public function via($notifiable): array
    {
        return ['database']; // Stores in your notifications table
    }

    public function toArray($notifiable): array
    {
        return [
            'seeker_id' => $this->messageData->seeker_id,
            'coach_id'  => $this->messageData->coach_id,
            'message'   => $this->messageData->message,
            'type'      => 'message'
        ];
    }
}