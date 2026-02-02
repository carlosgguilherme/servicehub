<?php

namespace App\Http\Controllers;

use App\Models\Ticket;
use Illuminate\Support\Facades\Storage;

class TicketAttachmentController extends Controller
{
    public function show(Ticket $ticket)
    {
        $ticket->load('project');

        if ($ticket->project->company_id !== auth()->user()->company_id) {
            abort(403);
        }

        if (!$ticket->attachment_path) {
            abort(404);
        }

        if (!Storage::disk('public')->exists($ticket->attachment_path)) {
            abort(404);
        }

        return Storage::disk('public')->response($ticket->attachment_path);
    }
}
