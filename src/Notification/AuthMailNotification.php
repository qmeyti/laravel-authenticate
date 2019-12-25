<?php

namespace Qmeyti\LaravelAuth\Notification;

use Illuminate\Bus\Queueable;
use Illuminate\Notifications\Notification;
use Illuminate\Contracts\Queue\ShouldQueue;
use Qmeyti\LaravelAuth\Models\User;

class AuthMailNotification extends Notification
{
    use Queueable;

    /**
     * Create a new notification instance.
     *
     * @param User $user
     */
    protected $user;
    protected $mailMessage;

    public function __construct(User $user, $mailMessage)
    {
        $this->user = $user;
        $this->mailMessage = $mailMessage;
    }

    /**
     * Get the notification's delivery channels.
     *
     * @param  mixed $notifiable
     * @return array
     */
    public function via($notifiable)
    {
        return ['mail'];
    }

    /**
     * Get the mail representation of the notification.
     *
     * @param  mixed $notifiable
     * @return \Illuminate\Notifications\Messages\MailMessage
     */
    public function toMail($notifiable)
    {
        return $this->mailMessage;
    }

    /**
     * Get the array representation of the notification.
     *
     * @param  mixed $notifiable
     * @return array
     */
    public function toArray($notifiable)
    {
        return [
            //
        ];
    }
}
