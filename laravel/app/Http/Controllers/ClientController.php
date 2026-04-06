<?php

namespace App\Http\Controllers;

use App\Models\Client;
use Illuminate\Http\RedirectResponse;
use Illuminate\Http\Request;
use Illuminate\View\View;

class ClientController extends Controller
{
    public function index(): View
    {
        $clients = Client::withCount('projects')->orderBy('name')->get();

        return view('clients.index', compact('clients'));
    }

    public function create(): View
    {
        return view('clients.create');
    }

    public function store(Request $request): RedirectResponse
    {
        $validated = $request->validate([
            'name'    => 'required|string|max:255',
            'email'   => 'nullable|email|max:255',
            'phone'   => 'nullable|string|max:50',
            'company' => 'nullable|string|max:255',
        ]);

        Client::create($validated);

        return redirect('/clients')->with('success', 'Client créé');
    }

    public function show(int $id): View
    {
        $client = Client::with('projects.tickets')->findOrFail($id);

        return view('clients.show', compact('client'));
    }

    public function edit(int $id): View
    {
        $client = Client::findOrFail($id);

        return view('clients.edit', compact('client'));
    }

    public function update(Request $request, int $id): RedirectResponse
    {
        $validated = $request->validate([
            'name'    => 'required|string|max:255',
            'email'   => 'nullable|email|max:255',
            'phone'   => 'nullable|string|max:50',
            'company' => 'nullable|string|max:255',
        ]);

        $client = Client::findOrFail($id);
        $client->update($validated);

        return redirect('/clients')->with('success', 'Client modifié');
    }

    public function destroy(int $id): RedirectResponse
    {
        $client = Client::findOrFail($id);
        $client->delete();

        return redirect('/clients')->with('success', 'Client supprimé');
    }
}
