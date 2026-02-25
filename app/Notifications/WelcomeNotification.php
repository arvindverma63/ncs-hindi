<?php

namespace App\Notifications;

use Illuminate\Bus\Queueable;
use Illuminate\Contracts\Queue\ShouldQueue;
use Illuminate\Notifications\Messages\MailMessage;
use Illuminate\Notifications\Notification;

class WelcomeNotification extends Notification implements ShouldQueue
{
    use Queueable;

    protected $name;
    protected $role;
    protected $password;

    public function __construct($name, $role, $password)
    {
        $this->name = $name;
        $this->role = $role;
        $this->password = $password;
    }

    public function via($notifiable)
    {
        return ['mail', 'database']; // <--- Added 'database'
    }

    public function toArray($notifiable)
    {
        return [
            'title'   => 'Welcome Aboard!',
            'message' => 'Your account has been created successfully.',
            'type'    => 'primary',
            'icon'    => 'tabler:confetti',
        ];
    }   
    public function toMail($notifiable)
    {
        return (new MailMessage)
            ->subject('Welcome to BestBusinessCoachIndia')
            ->greeting('Hello ' . $this->name . '!')
            ->line('Your account has been created successfully as a ' . ucfirst($this->role) . '.')
            ->line('Here are your login credentials:')
            ->line('Email: ' . $notifiable->email)
            ->line('Password: ' . $this->password) // Be careful sending passwords via email in production
            ->action('Login Now', route('login'))
            ->line('Please change your password after your first login.');
    }
}