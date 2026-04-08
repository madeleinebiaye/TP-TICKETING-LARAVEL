<?php

namespace App\Http\Controllers;

use App\Models\Ticket;
use Illuminate\Http\RedirectResponse;
use Illuminate\Http\Request;
use Illuminate\View\View;

class ClientValidationController extends Controller
{
    public function index(): View
    {
        $client = auth()->user()?->clientProfile;

        abort_if(! $client, 403, 'Compte client non rattaché à une fiche client.');

        $tickets = Ticket::query()
            ->where('type', 'Facturable')
            ->whereHas('project', fn ($query) => $query->where('client_id', $client->id))
            ->with('project')
            ->orderByDesc('updated_at')
            ->get();

        return view('client.tickets.index', compact('tickets', 'client'));
    }

    public function validateTicket(Request $request, int $ticketId): RedirectResponse
    {
        $client = auth()->user()?->clientProfile;

        abort_if(! $client, 403);

        $ticket = Ticket::query()
            ->where('id', $ticketId)
            ->where('type', 'Facturable')
            ->whereHas('project', fn ($query) => $query->where('client_id', $client->id))
            ->firstOrFail();

        $request->validate([
            'comment' => 'nullable|string|max:1000',
        ]);

        $ticket->update([
            'status' => 'Validé',
            'validation_comment' => $request->input('comment'),
            'validated_at' => now(),
        ]);

        return back()->with('success', 'Ticket validé pour facturation.');
    }

    public function refuseTicket(Request $request, int $ticketId): RedirectResponse
    {
        $client = auth()->user()?->clientProfile;

        abort_if(! $client, 403);

        $ticket = Ticket::query()
            ->where('id', $ticketId)
            ->where('type', 'Facturable')
            ->whereHas('project', fn ($query) => $query->where('client_id', $client->id))
            ->firstOrFail();

        $validated = $request->validate([
            'comment' => 'required|string|max:1000',
        ]);

        $ticket->update([
            'status' => 'Refusé',
            'validation_comment' => $validated['comment'],
            'validated_at' => now(),
        ]);

        return back()->with('success', 'Ticket refusé, retour en statut Refusé.');
    }
}
