<?php

namespace App\Http\Controllers\Api;

use App\Http\Controllers\Controller;
use App\Models\Ticket;
use Illuminate\Http\JsonResponse;
use Illuminate\Http\Request;

class TicketApiController extends Controller
{
    private function normalizeStatus(string $status): string
    {
        return $status === 'ouvert' ? 'Nouveau' : $status;
    }

    public function index(): JsonResponse
    {
        $tickets = Ticket::with('project')
            ->orderByDesc('id')
            ->get()
            ->map(function (Ticket $ticket): array {
                return [
                    'id' => $ticket->id,
                    'title' => $ticket->title,
                    'description' => $ticket->description,
                    'status' => $ticket->status,
                    'type' => $ticket->type,
                    'priority' => $ticket->priority,
                    'project_id' => $ticket->project_id,
                    'project_name' => $ticket->project?->name,
                    'hours_estimated' => $ticket->hours_estimated,
                    'hours_spent' => $ticket->hours_spent,
                    'remaining_hours' => $ticket->remaining_hours,
                    'billable_hours' => $ticket->billable_hours,
                    'created_at' => $ticket->created_at,
                ];
            });

        return response()->json($tickets);
    }

    public function store(Request $request): JsonResponse
    {
        $validated = $request->validate([
            'title' => 'required|string|max:255',
            'description' => 'nullable|string',
            'status' => 'required|in:Nouveau,En cours,Terminé,ouvert',
            'type' => 'required|in:Inclus,Facturable',
            'priority' => 'nullable|in:Basse,Moyenne,Haute',
            'estimated_time' => 'nullable|integer|min:0',
            'spent_time' => 'nullable|integer|min:0',
            'project_id' => 'nullable|exists:projects,id',
        ]);

        $ticket = Ticket::create([
            'title' => $validated['title'],
            'description' => $validated['description'] ?? null,
            'status' => $this->normalizeStatus($validated['status']),
            'type' => $validated['type'],
            'priority' => $validated['priority'] ?? null,
            'hours_estimated' => $validated['estimated_time'] ?? 0,
            'hours_spent' => $validated['spent_time'] ?? 0,
            'project_id' => $validated['project_id'] ?? null,
        ]);

        $ticket->load('project');

        return response()->json([
            'id' => $ticket->id,
            'title' => $ticket->title,
            'description' => $ticket->description,
            'status' => $ticket->status,
            'type' => $ticket->type,
            'priority' => $ticket->priority,
            'project_id' => $ticket->project_id,
            'project_name' => $ticket->project?->name,
            'hours_estimated' => $ticket->hours_estimated,
            'hours_spent' => $ticket->hours_spent,
            'remaining_hours' => $ticket->remaining_hours,
            'billable_hours' => $ticket->billable_hours,
        ], 201);
    }

    public function stats(): JsonResponse
    {
        return response()->json([
            'total' => Ticket::count(),
            'nouveau' => Ticket::whereIn('status', ['Nouveau', 'ouvert'])->count(),
            'encours' => Ticket::where('status', 'En cours')->count(),
            'termine' => Ticket::where('status', 'Terminé')->count(),
        ]);
    }
}