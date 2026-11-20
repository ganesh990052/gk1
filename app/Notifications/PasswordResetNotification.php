<?php

namespace App\Notifications;

use Illuminate\Bus\Queueable;
use Illuminate\Notifications\Notification;
use Illuminate\Notifications\Messages\MailMessage;

class PasswordResetNotification extends Notification
{
    use Queueable;

    protected $token;
    protected $email;

    // Constructor to pass the token and email to the notification
    public function __construct($token, $email)
    {
        $this->token = $token;
        $this->email = $email;
    }

    // The notification’s delivery channels
    public function via($notifiable)
    {
        return ['mail'];
    }

    // The content of the email
    public function toMail($notifiable)
    {
        $resetUrl = url("/api/reset-password1?token={$this->token}&email={$this->email}");

        return (new MailMessage)
                    ->subject('Password Reset Request')
                    ->line('You are receiving this email because we received a password reset request for your account.')
                    ->action('Reset Password', $resetUrl)
                    ->line('If you did not request a password reset, no further action is required.');
    }
}
