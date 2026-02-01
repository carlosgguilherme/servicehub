<?php

namespace App\Jobs;

use App\Models\Ticket;
use Illuminate\Bus\Queueable;
use Illuminate\Contracts\Queue\ShouldQueue;
use Illuminate\Foundation\Bus\Dispatchable;
use Illuminate\Queue\InteractsWithQueue;
use Illuminate\Queue\SerializesModels;
use Illuminate\Support\Facades\Log;
use Illuminate\Support\Facades\Storage;
use App\Notifications\TicketProcessed;

class ProcessTicketAttachment implements ShouldQueue
{
    use Dispatchable, InteractsWithQueue, Queueable, SerializesModels;

    public int $ticketId;

    public function __construct(int $ticketId)
    {
        $this->ticketId = $ticketId;
    }

    public function handle(): void
    {
        $ticket = Ticket::with(['detail', 'project', 'user'])->find($this->ticketId);

        if (!$ticket || !$ticket->detail) {
            return;
        }

        $disk = Storage::disk('public');

        $tech = [
            'status' => 'processed',
            'path' => $ticket->attachment_path,
            'summary' => null,
        ];

        if (!$ticket->attachment_path) {
            $tech['status'] = 'no_attachment';
            $tech['summary'] = 'Ticket sem anexo para processar';
        } else if (!$disk->exists($ticket->attachment_path)) {
            $tech['status'] = 'file_missing';
            $tech['summary'] = 'Arquivo não encontrado no storage';
        } else {
            $content = $disk->get($ticket->attachment_path);
            $ext = strtolower(pathinfo($ticket->attachment_path, PATHINFO_EXTENSION));

            $tech['extension'] = $ext;
            $tech['size_bytes'] = $disk->size($ticket->attachment_path);

            if ($ext === 'json') {
                $json = json_decode($content, true);

                if (json_last_error() === JSON_ERROR_NONE) {
                    $tech['parsed_json'] = $json;
                    $tech['summary'] = 'JSON válido processado com sucesso';
                } else {
                    $tech['status'] = 'invalid_json';
                    $tech['summary'] = 'Falha ao parsear JSON';
                    $tech['json_error'] = json_last_error_msg();
                }
            } else {
                $tech['summary'] = 'Arquivo texto processado';
                $tech['preview'] = mb_substr($content, 0, 500);
            }
        }

        $ticket->detail->update([
            'technical_data' => $tech,
        ]);

        $user = \App\Models\User::find($ticket->user_id);
        if ($user) {
            $user->notify(new \App\Notifications\TicketProcessed($ticket->fresh(['detail', 'project'])));
        }
    }
}
