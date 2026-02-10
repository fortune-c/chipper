<?php

namespace App\Notifications;

use App\Models\Chip;
use App\Models\User;
use Illuminate\Bus\Queueable;
use Illuminate\Notifications\Notification;

class UserMentioned extends Notification
{
    use Queueable;

    public $chip;
    public $mentionedBy;

    /**
     * Create a new notification instance.
     */
    public function __construct(Chip $chip, User $mentionedBy)
    {
        $this->chip = $chip;
        $this->mentionedBy = $mentionedBy;
    }

    /**
     * Get the notification's delivery channels.
     *
     * @return array<int, string>
     */
    public function via(object $notifiable): array
    {
        return ['database'];
    }

    /**
     * Get the array representation of the notification.
     *
     * @return array<string, mixed>
     */
    public function toArray(object $notifiable): array
    {
        return [
            'chip_id' => $this->chip->id,
            'user_id' => $this->mentionedBy->id,
            'title' => 'New Mention',
            'message' => "{$this->mentionedBy->name} mentioned you in a chip",
            'url' => '/', // In a real app, this would anchor to the chip
        ];
    }
}
