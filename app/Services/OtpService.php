<?php
// app/Services/OtpService.php

namespace App\Services;
use App\Notifications\OtpNotification;
use Illuminate\Support\Facades\Notification;
class OtpService
{
    public function sendOtp($email, $mobile, $otp)
    {
        // Send OTP via email (you can customize this based on your needs)
        Notification::route('mail', $email)
            ->notify(new OtpNotification($otp));

        // Send OTP via SMS or other channels as needed
        // For SMS, you might use a service like Twilio
        // Example: \Twilio::message($mobile, 'Your OTP is: ' . $otp);
    }
}
