<?php

namespace App\Http\Controllers;

use App\Models\User;
use Illuminate\Http\RedirectResponse;
use Illuminate\Http\Request;
use Illuminate\View\View;

class UserController extends Controller
{
    public function index(): View
    {
        $users = User::orderBy('created_at', 'desc')->get();

        return view('users.index', compact('users'));
    }

    public function update(Request $request, int $id): RedirectResponse
    {
        $validated = $request->validate([
            'role' => 'required|in:admin,collaborateur',
        ]);

        $user = User::findOrFail($id);

        // Evite qu'un admin se retire ses propres droits par erreur.
        if ((int) auth()->id() === (int) $user->id && $validated['role'] !== 'admin') {
            return back()->with('error', 'Vous ne pouvez pas retirer votre rôle administrateur.');
        }

        $user->update([
            'role' => $validated['role'],
        ]);

        return back()->with('success', 'Rôle utilisateur mis à jour.');
    }
}