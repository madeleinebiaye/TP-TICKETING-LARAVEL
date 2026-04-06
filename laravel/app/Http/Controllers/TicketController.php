<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use App\Models\Project;
use App\Models\Ticket;

class TicketController extends Controller
{
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
        $projects = Project::orderBy('name')->get();
        return view('tickets.create', compact('projects'));
    }

    // 💾 Enregistrer un ticket
    public function store(Request $request)
{
    $validated = $request->validate([
        'title' => 'required|string|max:255',
        'description' => 'required|string',
        'status' => 'required|in:Nouveau,En cours,Terminé,ouvert',
        'type' => 'required|in:Inclus,Facturable',
        'priority' => 'nullable|in:Basse,Moyenne,Haute',
        'estimated_time' => 'nullable|integer|min:0',
        'spent_time' => 'nullable|integer|min:0',
        'project_id' => 'nullable|exists:projects,id',
        'collaborators' => 'nullable|array',
        'collaborators.*' => 'string|max:255',
    ]);

    Ticket::create([
        'title' => $validated['title'],
        'description' => $validated['description'],
        'status' => $this->normalizeStatus($validated['status']),
        'type' => $validated['type'],
        'priority' => $validated['priority'] ?? null,
        'hours_estimated' => $validated['estimated_time'] ?? 0,
        'hours_spent' => $validated['spent_time'] ?? 0,
        'project_id' => $validated['project_id'] ?? null,
        'collaborators' => $validated['collaborators'] ?? null,
    ]);

    return redirect('/tickets')->with('success', 'Ticket créé');
}

    // 🔍 Voir détail d’un ticket
    public function show($id)
    {
        $ticket = Ticket::with('project')->findOrFail($id);
        return view('tickets.show', compact('ticket'));
    }

    // ✏️ Formulaire d'édition
    public function edit($id)
    {
        $ticket = Ticket::findOrFail($id);
        $projects = Project::orderBy('name')->get();
        return view('tickets.edit', compact('ticket', 'projects'));
    }

    // 💾 Mise à jour
    public function update(Request $request, $id)
    {
        $validated = $request->validate([
            'title' => 'required|string|max:255',
            'description' => 'nullable|string',
            'status' => 'required|in:Nouveau,En cours,Terminé,ouvert',
            'type' => 'required|in:Inclus,Facturable',
            'priority' => 'nullable|in:Basse,Moyenne,Haute',
            'hours_estimated' => 'nullable|integer|min:0',
            'hours_spent' => 'nullable|integer|min:0',
            'project_id' => 'nullable|exists:projects,id',
            'collaborators' => 'nullable|array',
            'collaborators.*' => 'string|max:255',
        ]);

        $ticket = Ticket::findOrFail($id);

        $ticket->update([
            'title' => $validated['title'],
            'description' => $validated['description'] ?? null,
            'status' => $this->normalizeStatus($validated['status']),
            'type' => $validated['type'],
            'priority' => $validated['priority'] ?? null,
            'hours_estimated' => $validated['hours_estimated'] ?? 0,
            'hours_spent' => $validated['hours_spent'] ?? 0,
            'project_id' => $validated['project_id'] ?? null,
            'collaborators' => $validated['collaborators'] ?? null,
        ]);

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