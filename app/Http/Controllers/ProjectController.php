<?php

namespace App\Http\Controllers;

use App\Models\Project;
use App\Models\Company;
use Illuminate\Http\Request;
use Inertia\Inertia;


class ProjectController extends Controller
{
    /**
     * Display a listing of the resource.
     */
    public function index()
    {
        $projects = Project::where('company_id', auth()->user()->company_id)
            ->orderBy('name')
            ->get();

        return Inertia::render('Projects/Index', [
            'projects' => $projects,
        ]);
    }

    /**
     * Show the form for creating a new resource.
     */
    public function create()
    {
        return Inertia::render('Projects/Create');
    }

    /**
     * Store a newly created resource in storage.
     */
    public function store(Request $request)
    {
        $data = $request->validate([
            'name' => 'required|string|max:255',
        ]);

        Project::create([
            'name' => $data['name'],
            'company_id' => auth()->user()->company_id,
        ]);

        return redirect()->route('projects.index');
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
    public function destroy(Project $project)
    {
        if ($project->company_id !== auth()->user()->company_id) {
            abort(403);
        }

        if ($project->tickets()->exists()) {
            return back()->with('error', 'Não é possível excluir um projeto que já possui tickets.');
        }

        $project->delete();

        return redirect()
            ->route('projects.index')
            ->with('success', 'Projeto excluído com sucesso.');
    }
}
