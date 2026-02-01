<?php

namespace App\Http\Controllers;

use App\Models\Company;
use Illuminate\Http\Request;
use Inertia\Inertia;


class CompanyController extends Controller
{
    public function index()
    {
        return Inertia::render('Companies/Index', [
            'companies' => Company::all()
        ]);
    }

    public function create()
    {
    return Inertia::render('Companies/Create');
    }

    public function store(Request $request)
    {
      $request->validate([
        'name' => 'required'
    ]);

    Company::create($request->only('name'));

    return redirect()->route('companies.index');
    }

    public function show(string $id)
    {
        //
    }

    public function edit(string $id)
    {
        //
    }

    public function update(Request $request, string $id)
    {
        //
    }

    public function destroy(string $id)
    {
        //
    }
}
