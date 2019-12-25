<?php

namespace Qmeyti\LaravelAuth\Notification;

use Illuminate\Bus\Queueable;
use Qmeyti\LaravelAuth\Channel\SmsChannel;
//use App\Channels\Messages\VoiceMessage;
use Illuminate\Notifications\Notification;
use Illuminate\Contracts\Queue\ShouldQueue;
use Qmeyti\LaravelAuth\Models\User;

class AuthSmsNotification extends Notification
{
    use Queueable;

    protected $user;
    protected $smsMessage;

    public function __construct(User $user, $mailMessage)
    {
        $this->user = $user;
        $this->smsMessage = $mailMessage;
    }
    /**
     * Get the notification channels.
     *
     * @param  mixed  $notifiable
     * @return array|string
     */
    public function via($notifiable)
    {
        return [SmsChannel::class];
    }

    /**
     * Get the sms representation of the notification.
     * @param $notifiable
     * @return string
     */
    public function toSms($notifiable)
    {
        return $this->smsMessage;
    }
}