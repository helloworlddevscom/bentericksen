<?php

namespace App\Notifications;

use App\BonusPro\Plan;
use Illuminate\Bus\Queueable;
use Illuminate\Notifications\Messages\MailMessage;
use Illuminate\Notifications\Notification;

class BonusProPasswordReset extends Notification
{
    use Queueable;

    protected $url;

    protected $plan;

    /**
     * Create a new notification instance.
     *
     * @param $token
     */
    public function __construct($token, Plan $plan)
    {
        $this->plan = $plan;
        $this->url = route('bonuspro.plan.showReset', ['plan' => $plan->id, 'token' => $token]);
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
        $expire = config('auth.passwords.bonuspro-plans.expire');

        return (new MailMessage)
                    ->greeting('Dear '.ucfirst($this->plan->user->first_name).',')
                    ->line('Click here to reset your password:')
                    ->action('Reset Your Password', $this->url)
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
