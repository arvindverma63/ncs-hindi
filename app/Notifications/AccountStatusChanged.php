<?php

namespace App\Notifications;

use Illuminate\Bus\Queueable;
use Illuminate\Contracts\Queue\ShouldQueue;
use Illuminate\Notifications\Messages\MailMessage;
use Illuminate\Notifications\Notification;

class AccountStatusChanged extends Notification implements ShouldQueue
{
    use Queueable;

    protected $status;
    protected $remark;

    public function __construct($status, $remark = null)
    {
        $this->status = $status;
        $this->remark = $remark;
    }

    public function via($notifiable)
    {
        return ['mail', 'database']; // <--- Added 'database'
    }

    // Add this method to store data for the topbar
    public function toArray($notifiable)
    {
        $isApproved = $this->status === 'approved';
        
        return [
            'title'   => $isApproved ? 'Profile Approved' : 'Profile Status Update',
            'message' => $isApproved ? 'Your profile is now live.' : 'Your profile status is: ' . $this->status,
            'type'    => $isApproved ? 'success' : 'danger', // Colors: success(green), danger(red)
            'icon'    => $isApproved ? 'tabler:check' : 'tabler:alert-circle',
        ];
    }

    public function toMail($notifiable)
    {
        $message = (new MailMessage)->greeting('Hello ' . $notifiable->name . ',');

        if ($this->status === 'approved') {
            $message->subject('🎉 Profile Approved - BestBusinessCoachIndia')
                ->line('Great news! Your coach profile has been verified and approved.')
                ->line('Your profile is now visible to seekers on our platform.')
                ->action('View Profile', url('/')); // Adjust URL as needed
        } elseif ($this->status === 'rejected') {
            $message->subject('Profile Status Update')
                ->line('We regret to inform you that your profile application was not approved.')
                ->line('Reason: ' . ($this->remark ?? 'Does not meet current guidelines.'))
                ->line('Please update your profile information and contact support.');
        } else {
            $message->subject('Profile Status Changed')
                ->line('Your profile status has been changed to: ' . ucfirst($this->status));
        }

        return $message;
    }
}