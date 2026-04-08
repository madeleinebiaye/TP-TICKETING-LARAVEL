<?php

namespace App\Http\Controllers;

use App\Models\Client;
use App\Models\User;
use Illuminate\Http\RedirectResponse;
use Illuminate\Http\Request;
use Illuminate\Validation\Rule;
use Illuminate\View\View;

class ClientController extends Controller
{
    public function index(): View
    {
        $clients = Client::with('user')->withCount('projects')->orderBy('name')->get();

        return view('clients.index', compact('clients'));
    }

    public function create(): View
    {
        $clientUsers = User::query()
            ->where('role', 'client')
            ->orderBy('name')
            ->get(['id', 'name', 'email']);

        return view('clients.create', compact('clientUsers'));
    }

    public function store(Request $request): RedirectResponse
    {
        $validated = $request->validate([
            'user_id' => [
                'nullable',
                'exists:users,id',
                Rule::unique('clients', 'user_id'),
            ],
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
        $client = Client::with('user', 'projects.tickets')->findOrFail($id);

        return view('clients.show', compact('client'));
    }

    public function edit(int $id): View
    {
        $client = Client::findOrFail($id);
        $clientUsers = User::query()
            ->where('role', 'client')
            ->orderBy('name')
            ->get(['id', 'name', 'email']);

        return view('clients.edit', compact('client', 'clientUsers'));
    }

    public function update(Request $request, int $id): RedirectResponse
    {
        $validated = $request->validate([
            'user_id' => [
                'nullable',
                'exists:users,id',
                Rule::unique('clients', 'user_id')->ignore($id),
            ],
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
