<?php

namespace App\Http\Controllers;

use App\Models\User;
use App\Models\Ticket;
use App\Models\TicketDetail;
use App\Models\Project;
use Illuminate\Http\Request;
use Inertia\Inertia;
use App\Jobs\ProcessTicketAttachment;




class TicketController extends Controller
{
    /**
     * Display a listing of the resource.
     */
    public function index()
    {
        $tickets = Ticket::with('project')
            ->where('user_id', auth()->id())
            ->latest()
            ->get();

        return Inertia::render('Tickets/Index', [
            'tickets' => $tickets,
        ]);
    }

    public function create()
    {
        $projects = Project::where('company_id', auth()->user()->company_id)
            ->orderBy('name')
            ->get(['id', 'name']);

        return Inertia::render('Tickets/Create', [
            'projects' => $projects,
        ]);
    }

    public function store(Request $request)
    {
        $data = $request->validate([
            'title' => 'required|string|max:255',
            'project_id' => 'required|integer|exists:projects,id',
            'notes' => 'nullable|string',
            'attachment' => 'nullable|file|max:5120', // 5MB
        ]);

        $projectOk = Project::where('id', $data['project_id'])
            ->where('company_id', auth()->user()->company_id)
            ->exists();

        if (!$projectOk) {
            return back()->withErrors([
                'project_id' => 'Projeto inválido para sua empresa.'
            ]);
        }

        $path = null;
        if ($request->hasFile('attachment')) {
            $path = $request->file('attachment')->store('attachments', 'public');
        }

        $ticket = Ticket::create([
            'title' => $data['title'],
            'project_id' => $data['project_id'],
            'user_id' => auth()->id(),
            'status' => 'open',
            'attachment_path' => $path,
        ]);

        TicketDetail::create([
            'ticket_id' => $ticket->id,
            'notes' => $data['notes'] ?? null,
            'technical_data' => null,
        ]);

        \App\Jobs\ProcessTicketAttachment::dispatch($ticket->id);

        return redirect()->route('tickets.index');
    }


    /**
     * Display the specified resource.
     */
    public function show(string $id)
    {
        //
    }

    /**
     * Show the form for editing the specified resource.
     */
    public function edit(string $id)
    {
        //
    }

    /**
     * Update the specified resource in storage.
     */
    public function update(Request $request, string $id)
    {
        //
    }

    /**
     * Remove the specified resource from storage.
     */
    public function destroy(Ticket $ticket)
    {
        $companyId = auth()->user()->company_id;
        $ticket->load('project');

        if ($ticket->project->company_id !== $companyId) {
            abort(403);
        }

        if ($ticket->attachment_path) {
            \Illuminate\Support\Facades\Storage::disk('public')->delete($ticket->attachment_path);
        }

        $ticket->delete();

        return redirect()->route('tickets.index');
    }
}
