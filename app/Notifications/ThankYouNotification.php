<?php

namespace App\Notifications;

use Illuminate\Bus\Queueable;
use Illuminate\Contracts\Queue\ShouldQueue;
use Illuminate\Notifications\Messages\MailMessage;
use Illuminate\Notifications\Notification;

class ThankYouNotification extends Notification
{
    use Queueable;
    public $user;

    public function __construct($user)
    {
        $this->user = $user;
    }

    public function via($notifiable)
    {
        return ['mail']; // sirf mail bhejna hai
    }

    public function toMail($notifiable)
    {
        return (new MailMessage)
            ->subject('🎉 Thank You for Registering at Investifya!')
            ->greeting('Hello ' . $this->user->name . ',')
            ->line('Thank you for joining Investifya! We’re excited to have you on board.')
            ->line('Start exploring your account and enjoy exclusive benefits!')
            ->action('Visit Investifya', url('https://investifya.com'))
            ->line('If you have any questions, feel free to contact us anytime.');
    }
}
