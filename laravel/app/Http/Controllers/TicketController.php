<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use App\Models\Ticket;

class TicketController extends Controller
{
    // 📄 Afficher tous les tickets
    public function index()
    {
        $tickets = Ticket::orderBy('id', 'desc')->get();
        return view('tickets.index', compact('tickets'));
    }

    // 📄 Formulaire de création
    public function create()
    {
        return view('tickets.create');
    }

    // 💾 Enregistrer un ticket
    public function store(Request $request)
    {
        $request->validate([
            'title' => 'required'
        ]);

        Ticket::create([
            'title' => $request->title,
            'description' => $request->description,
            'status' => 'ouvert',
            'hours_estimated' => 0,
            'hours_spent' => 0
        ]);

        return redirect('/tickets')->with('success', 'Ticket créé avec succès');
    }

    // 🔍 Voir détail d’un ticket
    public function show($id)
    {
        $ticket = Ticket::findOrFail($id);
        return view('tickets.show', compact('ticket'));
    }

    // ✏️ Formulaire d'édition
    public function edit($id)
    {
        $ticket = Ticket::findOrFail($id);
        return view('tickets.edit', compact('ticket'));
    }

    // 💾 Mise à jour
    public function update(Request $request, $id)
    {
        $request->validate([
            'title' => 'required'
        ]);

        $ticket = Ticket::findOrFail($id);

        $ticket->update([
            'title' => $request->title,
            'description' => $request->description,
            'status' => $request->status,
            'hours_estimated' => $request->hours_estimated,
            'hours_spent' => $request->hours_spent
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