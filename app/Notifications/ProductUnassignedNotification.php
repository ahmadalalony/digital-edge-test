<?php

namespace App\Notifications;

use Illuminate\Bus\Queueable;
use Illuminate\Contracts\Queue\ShouldQueue;
use Illuminate\Notifications\Messages\BroadcastMessage;
use Illuminate\Notifications\Messages\MailMessage;
use Illuminate\Notifications\Notification;

class ProductUnassignedNotification extends Notification implements ShouldQueue
{
    use Queueable;

    public function __construct(public string $productName, public string $unassignedBy) {}

    public function via(object $notifiable): array
    {
        return ['database', 'broadcast'];
    }

    public function toMail(object $notifiable): MailMessage
    {
        return (new MailMessage)
            ->line('A product has been unassigned from you.')
            ->line("Product: {$this->productName}")
            ->line("Unassigned by: {$this->unassignedBy}")
            ->line('Thank you for using our application!');
    }

    public function toArray(object $notifiable): array
    {
        return [
            'title' => 'Product Unassigned',
            'body' => "{$this->productName} has been unassigned from you by {$this->unassignedBy}.",
        ];
    }

    public function toBroadcast($notifiable): BroadcastMessage
    {
        return new BroadcastMessage([
            'title' => 'Product Unassigned',
            'body' => "{$this->productName} has been unassigned from you by {$this->unassignedBy}.",
        ]);
    }
}
