<?php

namespace App\Http\Controllers;

use App\Models\Project;
use App\Models\Client;
use App\Models\Ticket;
use Illuminate\Http\RedirectResponse;
use Illuminate\Http\Request;
use Illuminate\View\View;

class ProjectController extends Controller
{
    private function isAdmin(): bool
    {
        return auth()->user()?->role === 'admin';
    }

    private function isCollaborateur(): bool
    {
        return auth()->user()?->role === 'collaborateur';
    }

    private function assignedProjectIdsForCollaborateur(): array
    {
        $userId = auth()->id();

        if (! $userId) {
            return [];
        }

        return Ticket::query()
            ->whereNotNull('project_id')
            ->whereJsonContains('collaborators', (int) $userId)
            ->distinct()
            ->pluck('project_id')
            ->map(fn ($value) => (int) $value)
            ->all();
    }

    private function ensureProjectVisibleForCurrentUser(Project $project): void
    {
        if (! $this->isCollaborateur()) {
            return;
        }

        $isAssigned = Ticket::query()
            ->where('project_id', $project->id)
            ->whereJsonContains('collaborators', (int) auth()->id())
            ->exists();

        abort_if(! $isAssigned, 403, 'Vous ne pouvez voir que les projets qui vous sont assignés.');
    }

    public function index(): View
    {
        $projectsQuery = Project::query();

        if ($this->isCollaborateur()) {
            $assignedProjectIds = $this->assignedProjectIdsForCollaborateur();
            $projectsQuery->whereIn('id', $assignedProjectIds === [] ? [-1] : $assignedProjectIds);
        }

        $projects = $projectsQuery->with('client')->orderBy('name')->get();

        return view('projects.index', compact('projects'));
    }

    public function create(Request $request): View
    {
        abort_if(! $this->isAdmin(), 403, 'Seul un administrateur peut créer un projet.');

        $clients = Client::orderBy('name')->get();
        $selectedClientId = $request->integer('client_id') ?: null;

        return view('projects.create', compact('clients', 'selectedClientId'));
    }

    public function store(Request $request): RedirectResponse
    {
        abort_if(! $this->isAdmin(), 403, 'Seul un administrateur peut créer un projet.');

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
        $this->ensureProjectVisibleForCurrentUser($project);

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
        abort_if(! $this->isAdmin(), 403, 'Seul un administrateur peut modifier un projet.');

        $project = Project::findOrFail($id);
        $clients  = Client::orderBy('name')->get();

        return view('projects.edit', compact('project', 'clients'));
    }

    public function update(Request $request, int $id): RedirectResponse
    {
        abort_if(! $this->isAdmin(), 403, 'Seul un administrateur peut modifier un projet.');

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
        abort_if(! $this->isAdmin(), 403, 'Seul un administrateur peut supprimer un projet.');

        $project = Project::findOrFail($id);
        $project->delete();

        return redirect('/projects')->with('success', 'Projet supprimé');
    }
}