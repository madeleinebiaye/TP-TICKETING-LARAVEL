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

    // 📄 Afficher tous les tickets
    public function index()
    {
        $tickets = Ticket::with('project')->orderBy('id', 'desc')->get();
        return view('tickets.index', compact('tickets'));
    }

    // 📄 Formulaire de création
    public function create()
    {
        $selectedClientId = request()->integer('client_id') ?: null;

        $clients = Client::orderBy('name')->get(['id', 'name', 'company']);
        $projects = Project::orderBy('name')->get(['id', 'name', 'client_id']);
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
        return view('tickets.show', compact('ticket'));
    }

    // ✏️ Formulaire d'édition
    public function edit($id)
    {
        $ticket = Ticket::findOrFail($id);
        $clients = Client::orderBy('name')->get(['id', 'name', 'company']);
        $projects = Project::orderBy('name')->get(['id', 'name', 'client_id']);
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

        $ticket = Ticket::findOrFail($id);

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
        $ticket = Ticket::findOrFail($id);
        $ticket->delete();

        return redirect('/tickets')->with('success', 'Ticket supprimé');
    }
}