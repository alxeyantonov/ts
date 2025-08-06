<?php

namespace App\Notifications;

use App\Models\Email;
use Illuminate\Bus\Queueable;
use Illuminate\Contracts\Queue\ShouldQueue;
use Illuminate\Notifications\Messages\MailMessage;
use Illuminate\Notifications\Notification;

class GreetingNotification extends Notification implements ShouldQueue
{
    use Queueable;

    public function __construct()
    {
        $this->onQueue('emails');
    }

    public function via(Email $notifiable): array
    {
        return ['mail'];
    }

    public function toMail(Email $notifiable): MailMessage
    {
        return (new MailMessage)
            ->subject(__('emails.greeting.subject'))
            ->greeting(__('emails.greeting.greeting', ['user' => $notifiable->user->full_name]));
    }
}
