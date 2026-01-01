<?php

namespace App\Notifications;

use Illuminate\Bus\Queueable;
use Illuminate\Notifications\Notification;
use Illuminate\Notifications\Messages\DatabaseMessage;

class AdminRejected extends Notification
{
    use Queueable;

    public function via($notifiable)
    {
        return ['database']; // store in DB for in-app notification
    }

    public function toDatabase($notifiable)
    {
        return [
            'title' => 'Admin Request Rejected',
            'message' => 'Your request for admin access has been rejected.',
        ];
    }
}
