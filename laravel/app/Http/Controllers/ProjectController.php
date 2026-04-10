<?php

namespace App\Http\Controllers;

use App\Models\Project;
use App\Models\Client;
use Illuminate\Http\RedirectResponse;
use Illuminate\Http\Request;
use Illuminate\View\View;

class ProjectController extends Controller
{
    public function index(): View
    {
        $projects = Project::all();

        return view('projects.index', compact('projects'));
    }

    public function create(Request $request): View
    {
        $clients = Client::orderBy('name')->get();
        $selectedClientId = $request->integer('client_id') ?: null;

        return view('projects.create', compact('clients', 'selectedClientId'));
    }

    public function store(Request $request): RedirectResponse
    {
        $validated = $request->validate([
            'title'       => 'required|string|max:255',
            'description' => 'required|string',
            'client_id'   => 'required|exists:clients,id',
        ]);

        Project::create([
            'name'        => $validated['title'],
            'description' => $validated['description'],
            'client_id'   => $validated['client_id'],
        ]);

        return redirect('/projects')->with('success', 'Projet créé');
    }

    public function show(int $id): View
    {
        $project = Project::with(['tickets.timeEntries', 'contract', 'client'])->findOrFail($id);

        $consumedIncludedMinutes = $project->consumedIncludedMinutes();
        $remainingIncludedMinutes = $project->remainingIncludedMinutes();
        $billableMinutes = $project->billableMinutes();

        return view('projects.show', compact(
            'project',
            'consumedIncludedMinutes',
            'remainingIncludedMinutes',
            'billableMinutes'
        ));
    }

    public function edit(int $id): View
    {
        $project = Project::findOrFail($id);
        $clients  = Client::orderBy('name')->get();

        return view('projects.edit', compact('project', 'clients'));
    }

    public function update(Request $request, int $id): RedirectResponse
    {
        $validated = $request->validate([
            'title'       => 'required|string|max:255',
            'description' => 'required|string',
            'client_id'   => 'required|exists:clients,id',
        ]);

        $project = Project::findOrFail($id);

        $project->update([
            'name'        => $validated['title'],
            'description' => $validated['description'],
            'client_id'   => $validated['client_id'],
        ]);

        return redirect('/projects')->with('success', 'Projet modifié');
    }

    public function destroy(int $id): RedirectResponse
    {
        $project = Project::findOrFail($id);
        $project->delete();

        return redirect('/projects')->with('success', 'Projet supprimé');
    }
}