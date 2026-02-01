<?php

namespace App\Notifications;

use App\Models\Ticket;
use Illuminate\Bus\Queueable;
use Illuminate\Notifications\Notification;

class TicketProcessed extends Notification
{
    use Queueable;

    public function __construct(public Ticket $ticket) {}

    public function via(object $notifiable): array
    {
        return ['database'];
    }

    public function toDatabase(object $notifiable): array
    {
        $status = data_get($this->ticket, 'detail.technical_data.status');

        return [
            'ticket_id' => $this->ticket->id,
            'title' => $this->ticket->title,
            'project' => $this->ticket->project?->name,
            'status' => $this->ticket->status,
            'processing_status' => $status,
        ];
    }
}
