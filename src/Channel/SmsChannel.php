<?php

namespace Qmeyti\LaravelAuth\Channel;

use Illuminate\Notifications\Notification;
use Ipecompany\Smsirlaravel\Smsirlaravel;

class SmsChannel
{
    /**
     * Send the given notification.
     *
     * @param  mixed $notifiable
     * @param  \Illuminate\Notifications\Notification $notification
     * @return void
     */
    public function send($notifiable, Notification $notification)
    {
        $mobile = $notifiable->mobile;

        $message = $notification->toSms($notifiable);

        Smsirlaravel::sendVerification($message, $mobile);
    }
}