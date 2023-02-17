<?php

namespace App\Notifications;

use App\User;
use Illuminate\Bus\Queueable;
use Illuminate\Notifications\Messages\MailMessage;
use Illuminate\Notifications\Notification;

class ActivationPasswordReset extends Notification
{
    use Queueable;

    protected $url;

    protected $user;

    /**
     * Create a new notification instance.
     *
     * @param $token
     * @param \App\User $user
     * @return void
     */
    public function __construct($token, User $user)
    {
        $this->user = $user;
        $this->url = url('/').'/password/reset/'.$token;
    }

    /**
     * Get the notification's delivery channels.
     *
     * @param  mixed  $notifiable
     * @return array
     */
    public function via($notifiable)
    {
        return ['mail'];
    }

    /**
     * Get the mail representation of the notification.
     *
     * @param  mixed  $notifiable
     * @return \Illuminate\Notifications\Messages\MailMessage
     */
    public function toMail($notifiable)
    {
        $expire = config('auth.passwords.users.expire');

        return (new MailMessage)
                    ->greeting('Dear '.ucfirst($this->user->first_name).',')
                    ->subject('Account Activation')
                    ->line('Welcome to the HR Director!')
                    ->line('Click here to activate your account and get started.')
                    ->action("Let's go", $this->url)
                    ->line('NOTE: The reset link will expire in '.floor($expire / 60).' hours.');
    }

    /**
     * Get the array representation of the notification.
     *
     * @param  mixed  $notifiable
     * @return array
     */
    public function toArray($notifiable)
    {
        return [
            //
        ];
    }
}
