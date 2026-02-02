<?php

use App\Http\Controllers\ProfileController;
use Illuminate\Foundation\Application;
use Illuminate\Support\Facades\Route;
use Inertia\Inertia;
use App\Models\Project;
use App\Models\Ticket;
use App\Http\Controllers\TicketAttachmentController;
use App\Http\Controllers\CompanyController;
use App\Http\Controllers\TicketController;
use App\Http\Controllers\ProjectController;


Route::get('/', function () {
    return auth()->check()
        ? redirect()->route('dashboard')
        : redirect()->route('login');
});


Route::get('/dashboard', function () {
    $companyId = auth()->user()->company_id;

    $projects = Project::where('company_id', $companyId)
        ->orderBy('name')
        ->take(8)
        ->get(['id', 'name']);

    $tickets = Ticket::with(['project:id,name,company_id', 'detail:id,ticket_id,technical_data'])
        ->whereHas('project', fn($q) => $q->where('company_id', $companyId))
        ->latest()
        ->take(8)
        ->get(['id', 'title', 'status', 'project_id', 'attachment_path', 'user_id', 'created_at']);

    $stats = [
        'projects_total' => Project::where('company_id', $companyId)->count(),
        'tickets_total' => Ticket::whereHas('project', fn($q) => $q->where('company_id', $companyId))->count(),
        'tickets_open' => Ticket::whereHas('project', fn($q) => $q->where('company_id', $companyId))
            ->where('status', 'open')
            ->count(),
    ];

    return Inertia::render('Dashboard', [
        'stats' => $stats,
        'projects' => $projects,
        'tickets' => $tickets,
    ]);
})->middleware(['auth', 'verified'])->name('dashboard');


Route::middleware('auth')->group(function () {
    Route::get('/profile', [ProfileController::class, 'edit'])->name('profile.edit');
    Route::patch('/profile', [ProfileController::class, 'update'])->name('profile.update');
    Route::delete('/profile', [ProfileController::class, 'destroy'])->name('profile.destroy');
    Route::resource('tickets', TicketController::class);
    Route::resource('companies', CompanyController::class);
    Route::resource('projects', ProjectController::class);
    Route::patch('/tickets/{ticket}/status', [TicketController::class, 'updateStatus'])->name('tickets.status');
    Route::get('/tickets/{ticket}/attachment', [TicketAttachmentController::class, 'show'])
        ->name('tickets.attachment');
});

require __DIR__ . '/auth.php';
