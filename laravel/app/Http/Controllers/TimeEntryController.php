<?php

namespace App\Http\Controllers;

use App\Models\Ticket;
use App\Models\TimeEntry;
use Illuminate\Http\RedirectResponse;
use Illuminate\Http\Request;

class TimeEntryController extends Controller
{
    private function isResponsibleCollaborateurForTicket(Ticket $ticket): bool
    {
        if (auth()->user()?->role !== 'collaborateur') {
            return true;
        }

        $userId = (int) auth()->id();

        return collect($ticket->collaborators ?? [])
            ->map(fn ($value) => (int) $value)
            ->contains($userId);
    }

    public function store(Request $request, int $ticketId): RedirectResponse
    {
        $ticket = Ticket::findOrFail($ticketId);

        if (! $this->isResponsibleCollaborateurForTicket($ticket)) {
            return back()->with('error', 'Vous ne pouvez enregistrer du temps que sur les tickets dont vous êtes responsable.');
        }

        $validated = $request->validate([
            'entry_date' => 'required|date',
            'duration_minutes' => 'required|integer|min:1|max:1440',
            'comment' => 'nullable|string|max:1000',
        ]);

        // Ici on garde une trace simple et lisible pour chaque saisie de temps.
        TimeEntry::create([
            'ticket_id' => $ticket->id,
            'user_id' => auth()->id(),
            'entry_date' => $validated['entry_date'],
            'duration_minutes' => $validated['duration_minutes'],
            'comment' => $validated['comment'] ?? null,
        ]);

        $ticket->refreshHoursSpentFromTimeEntries();

        return back()->with('success', 'Temps enregistré sur le ticket.');
    }

    public function destroy(int $timeEntryId): RedirectResponse
    {
        $timeEntry = TimeEntry::with('ticket')->findOrFail($timeEntryId);

        // Admin: suppression globale. Collaborateur: uniquement ses propres entrées.
        if (auth()->user()?->role !== 'admin' && (int) $timeEntry->user_id !== (int) auth()->id()) {
            return back()->with('error', 'Vous ne pouvez pas supprimer cette entrée de temps.');
        }

        $ticket = $timeEntry->ticket;
        $timeEntry->delete();

        if ($ticket) {
            $ticket->refreshHoursSpentFromTimeEntries();
        }

        return back()->with('success', 'Entrée de temps supprimée.');
    }
}
