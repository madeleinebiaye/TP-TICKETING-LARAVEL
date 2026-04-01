<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use App\Models\User;
use Illuminate\Support\Facades\Hash;

class AuthController extends Controller
{
    // Afficher la page login
    public function showLogin()
    {
        return view('auth.login');
    }

    // Traiter le login
    public function login(Request $request)
    {
        // Validation
        $request->validate([
            'email' => 'required|email',
            'password' => 'required'
        ]);

        // Vérifier utilisateur
        $user = User::where('email', $request->email)->first();

        if ($user && Hash::check($request->password, $user->password)) {

            session([
                'user_id' => $user->id,
                'user_email' => $user->email
            ]);

            return redirect('/dashboard');
        }

        return back()->with('error', 'Email ou mot de passe incorrect.');
    }
}