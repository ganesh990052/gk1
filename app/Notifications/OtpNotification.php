<?php
// app/Notifications/OtpNotification.php

namespace App\Notifications;

use Illuminate\Bus\Queueable;
use Illuminate\Contracts\Queue\ShouldQueue;
use Illuminate\Notifications\Messages\MailMessage;
use Illuminate\Notifications\Notification;

class OtpNotification extends Notification
{
    use Queueable;

    public $otp;

    public function __construct($otp)
    {
        $this->otp = $otp;
    }

    public function toMail($notifiable)
    {
        return (new MailMessage)
            ->line('Your OTP is: ' . $this->otp)
            ->line('Please do not share this OTP with others.');
    }

    public function toDatabase($notifiable)
    {
        return [
            'otp' => $this->otp,
        ];
    }

    public function toArray($notifiable)
    {
        return [
            'otp' => $this->otp,
        ];
    }

    public function via($notifiable)
    {
        return ['mail', 'database']; // Add any other channels you want to use
    }
}
