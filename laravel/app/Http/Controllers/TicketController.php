<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use App\Models\Client;
use App\Models\Project;
use App\Models\Ticket;
use App\Models\User;
use Illuminate\Validation\Rule;

class TicketController extends Controller
{
    private array $allowedStatuses = [
        'Nouveau',
        'En cours',
        'En attente client',
        'Terminé',
        'À valider',
        'Validé',
        'Refusé',
        'ouvert',
    ];

    private function normalizeStatus(string $status): string
    {
        return $status === 'ouvert' ? 'Nouveau' : $status;
    }

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

    private function isResponsibleCollaborateur(Ticket $ticket): bool
    {
        $userId = auth()->id();

        if (! $userId) {
            return false;
        }

        return collect($ticket->collaborators ?? [])
            ->map(fn ($value) => (int) $value)
            ->contains((int) $userId);
    }

    private function ensureCollaborateurCanAccessTicket(Ticket $ticket): void
    {
        if (! $this->isCollaborateur()) {
            return;
        }

        abort_if(! $this->isResponsibleCollaborateur($ticket), 403, 'Vous ne pouvez accéder qu’aux tickets dont vous êtes responsable.');
    }

    // 📄 Afficher tous les tickets
    public function index()
    {
        $ticketsQuery = Ticket::with('project')->orderBy('id', 'desc');

        if ($this->isCollaborateur()) {
            $ticketsQuery->whereJsonContains('collaborators', (int) auth()->id());
        }

        $tickets = $ticketsQuery->get();

        return view('tickets.index', compact('tickets'));
    }

    // 📄 Formulaire de création
    public function create()
    {
        $selectedClientId = request()->integer('client_id') ?: null;

        if ($this->isCollaborateur()) {
            $assignedProjectIds = $this->assignedProjectIdsForCollaborateur();
            $projects = Project::query()
                ->whereIn('id', $assignedProjectIds === [] ? [-1] : $assignedProjectIds)
                ->orderBy('name')
                ->get(['id', 'name', 'client_id']);

            $clients = Client::query()
                ->whereIn('id', $projects->pluck('client_id')->filter()->unique()->values()->all())
                ->orderBy('name')
                ->get(['id', 'name', 'company']);
        } else {
            $clients = Client::orderBy('name')->get(['id', 'name', 'company']);
            $projects = Project::orderBy('name')->get(['id', 'name', 'client_id']);
        }

        $collaborators = User::query()
            ->whereIn('role', ['admin', 'collaborateur'])
            ->orderBy('name')
            ->get(['id', 'name', 'email']);

        return view('tickets.create', compact('clients', 'projects', 'collaborators', 'selectedClientId'));
    }

    // 💾 Enregistrer un ticket
    public function store(Request $request)
{
    $validated = $request->validate([
        'title' => 'required|string|max:255',
        'description' => 'required|string',
        'status' => 'required|in:'.implode(',', $this->allowedStatuses),
        'type' => 'required|in:Inclus,Facturable',
        'priority' => 'nullable|in:Basse,Moyenne,Haute',
        'estimated_time' => 'nullable|integer|min:0',
        'spent_time' => 'nullable|integer|min:0',
        'client_id' => 'nullable|exists:clients,id',
        'project_id' => 'nullable|exists:projects,id',
        'collaborators' => 'nullable|array',
        'collaborators.*' => ['integer', Rule::exists('users', 'id')->where(fn ($query) => $query->whereIn('role', ['admin', 'collaborateur']))],
    ]);

    if ($this->isCollaborateur()) {
        if (empty($validated['project_id'])) {
            return back()->withErrors(['project_id' => 'Le projet est obligatoire pour un collaborateur.'])->withInput();
        }

        $assignedProjectIds = $this->assignedProjectIdsForCollaborateur();
        if (! in_array((int) $validated['project_id'], $assignedProjectIds, true)) {
            return back()->withErrors(['project_id' => 'Vous ne pouvez créer des tickets que sur vos projets assignés.'])->withInput();
        }

        if (in_array($validated['status'], ['Validé', 'Refusé'], true)) {
            return back()->withErrors(['status' => 'Seul le client peut valider ou refuser un ticket facturable.'])->withInput();
        }
    }

    if (!empty($validated['project_id']) && !empty($validated['client_id'])) {
        $projectBelongsToClient = Project::whereKey((int) $validated['project_id'])
            ->where('client_id', (int) $validated['client_id'])
            ->exists();

        if (! $projectBelongsToClient) {
            return back()->withErrors([
                'project_id' => 'Le projet sélectionné ne correspond pas au client choisi.',
            ])->withInput();
        }
    }

    $ticketData = [
        'title' => $validated['title'],
        'description' => $validated['description'],
        'status' => $this->normalizeStatus($validated['status']),
        'type' => $validated['type'],
        'priority' => $validated['priority'] ?? null,
        'hours_estimated' => $validated['estimated_time'] ?? 0,
        'hours_spent' => $validated['spent_time'] ?? 0,
        'project_id' => $validated['project_id'] ?? null,
        'collaborators' => $validated['collaborators'] ?? null,
    ];

    if ($this->isCollaborateur()) {
        $ticketData['collaborators'] = collect($validated['collaborators'] ?? [])
            ->filter(fn ($value) => is_numeric($value))
            ->map(fn ($value) => (int) $value)
            ->push((int) auth()->id())
            ->unique()
            ->values()
            ->all();
    }

    if (($ticketData['type'] ?? null) === 'Inclus' && !empty($ticketData['project_id'])) {
        $project = Project::with('contract')->find($ticketData['project_id']);

        // Petit garde-fou métier: plus d'heures incluses => ticket bascule en facturable.
        if ($project && $project->remainingIncludedMinutes() <= 0) {
            $ticketData['type'] = 'Facturable';
            $ticketData['status'] = 'À valider';
        }
    }

    Ticket::create($ticketData);

    return redirect('/tickets')->with('success', 'Ticket créé');
}

    // 🔍 Voir détail d’un ticket
    public function show($id)
    {
        $ticket = Ticket::with(['project', 'timeEntries.user'])->findOrFail($id);
        $this->ensureCollaborateurCanAccessTicket($ticket);

        return view('tickets.show', compact('ticket'));
    }

    // ✏️ Formulaire d'édition
    public function edit($id)
    {
        $ticket = Ticket::findOrFail($id);
        $this->ensureCollaborateurCanAccessTicket($ticket);

        if ($this->isCollaborateur()) {
            $assignedProjectIds = $this->assignedProjectIdsForCollaborateur();
            $projects = Project::query()
                ->whereIn('id', $assignedProjectIds === [] ? [-1] : $assignedProjectIds)
                ->orderBy('name')
                ->get(['id', 'name', 'client_id']);

            $clients = Client::query()
                ->whereIn('id', $projects->pluck('client_id')->filter()->unique()->values()->all())
                ->orderBy('name')
                ->get(['id', 'name', 'company']);
        } else {
            $clients = Client::orderBy('name')->get(['id', 'name', 'company']);
            $projects = Project::orderBy('name')->get(['id', 'name', 'client_id']);
        }

        $collaborators = User::query()
            ->whereIn('role', ['admin', 'collaborateur'])
            ->orderBy('name')
            ->get(['id', 'name', 'email']);

        $selectedClientId = $ticket->project?->client_id;

        return view('tickets.edit', compact('ticket', 'clients', 'projects', 'collaborators', 'selectedClientId'));
    }

    // 💾 Mise à jour
    public function update(Request $request, $id)
    {
        $validated = $request->validate([
            'title' => 'required|string|max:255',
            'description' => 'nullable|string',
            'status' => 'required|in:'.implode(',', $this->allowedStatuses),
            'type' => 'required|in:Inclus,Facturable',
            'priority' => 'nullable|in:Basse,Moyenne,Haute',
            'hours_estimated' => 'nullable|integer|min:0',
            'hours_spent' => 'nullable|integer|min:0',
            'client_id' => 'nullable|exists:clients,id',
            'project_id' => 'nullable|exists:projects,id',
            'collaborators' => 'nullable|array',
            'collaborators.*' => ['integer', Rule::exists('users', 'id')->where(fn ($query) => $query->whereIn('role', ['admin', 'collaborateur']))],
        ]);

        $ticket = Ticket::findOrFail($id);
        $this->ensureCollaborateurCanAccessTicket($ticket);

        if ($this->isCollaborateur()) {
            if (empty($validated['project_id'])) {
                return back()->withErrors(['project_id' => 'Le projet est obligatoire pour un collaborateur.'])->withInput();
            }

            $assignedProjectIds = $this->assignedProjectIdsForCollaborateur();
            if (! in_array((int) $validated['project_id'], $assignedProjectIds, true)) {
                return back()->withErrors(['project_id' => 'Vous ne pouvez modifier que des tickets de vos projets assignés.'])->withInput();
            }

            if (in_array($validated['status'], ['Validé', 'Refusé'], true)) {
                return back()->withErrors(['status' => 'Seul le client peut valider ou refuser un ticket facturable.'])->withInput();
            }
        }

        if (!empty($validated['project_id']) && !empty($validated['client_id'])) {
            $projectBelongsToClient = Project::whereKey((int) $validated['project_id'])
                ->where('client_id', (int) $validated['client_id'])
                ->exists();

            if (! $projectBelongsToClient) {
                return back()->withErrors([
                    'project_id' => 'Le projet sélectionné ne correspond pas au client choisi.',
                ])->withInput();
            }
        }

        $ticketData = [
            'title' => $validated['title'],
            'description' => $validated['description'] ?? null,
            'status' => $this->normalizeStatus($validated['status']),
            'type' => $validated['type'],
            'priority' => $validated['priority'] ?? null,
            'hours_estimated' => $validated['hours_estimated'] ?? 0,
            'hours_spent' => $validated['hours_spent'] ?? 0,
            'project_id' => $validated['project_id'] ?? null,
            'collaborators' => $validated['collaborators'] ?? null,
        ];

        if ($this->isCollaborateur()) {
            $ticketData['collaborators'] = collect($validated['collaborators'] ?? [])
                ->filter(fn ($value) => is_numeric($value))
                ->map(fn ($value) => (int) $value)
                ->push((int) auth()->id())
                ->unique()
                ->values()
                ->all();
        }

        if (($ticketData['type'] ?? null) === 'Inclus' && !empty($ticketData['project_id'])) {
            $project = Project::with('contract')->find($ticketData['project_id']);

            if ($project && $project->remainingIncludedMinutes() <= 0) {
                $ticketData['type'] = 'Facturable';
                $ticketData['status'] = 'À valider';
            }
        }

        $ticket->update($ticketData);

        return redirect('/tickets')->with('success', 'Ticket modifié');
    }

    // ❌ Supprimer un ticket
    public function destroy($id)
    {
        abort_if(! $this->isAdmin(), 403, 'Seul un administrateur peut supprimer un ticket.');

        $ticket = Ticket::findOrFail($id);
        $ticket->delete();

        return redirect('/tickets')->with('success', 'Ticket supprimé');
    }
}