<?php

namespace App\Http\Controllers;

use Illuminate\Http\RedirectResponse;
use Illuminate\Http\Request;
use Illuminate\View\View;
use App\Models\User;
use Illuminate\Support\Facades\Hash;

class AuthController extends Controller
{
    public function showLogin(): View|RedirectResponse
    {
        if (session()->has('user_id')) {
            return redirect('/accueil');
        }

        return view('auth.login');
    }

    public function showRegister(): View|RedirectResponse
    {
        if (session()->has('user_id')) {
            return redirect('/accueil');
        }

        return view('auth.register');
    }

    public function login(Request $request): RedirectResponse
    {
        $request->validate([
            'email'    => 'required|email',
            'password' => 'required',
        ]);

        $user = User::where('email', $request->email)->first();

        if ($user && Hash::check($request->password, $user->password)) {
            session([
                'user_id'    => $user->id,
                'user_name'  => $user->name,
                'user_email' => $user->email,
            ]);

            return redirect('/accueil');
        }

        return back()->with('error', 'Email ou mot de passe incorrect.');
    }

    public function register(Request $request): RedirectResponse
    {
        $validated = $request->validate([
            'name' => 'required|string|max:255',
            'email' => 'required|email|max:255|unique:users,email',
            'password' => 'required|string|min:6|confirmed',
        ]);

        User::create([
            'name' => $validated['name'],
            'email' => $validated['email'],
            'password' => Hash::make($validated['password']),
        ]);

        return redirect('/login')->with('success', 'Compte créé avec succès. Vous pouvez maintenant vous connecter.');
    }

    public function logout(Request $request): RedirectResponse
    {
        $request->session()->flush();

        return redirect('/');
    }

    public function showForgotPassword(): View
    {
        return view('auth.forgot-password');
    }

    public function forgotPassword(Request $request): RedirectResponse
    {
        $request->validate([
            'email' => 'required|email',
        ]);

        // Simulation: en production, envoyer un vrai email de réinitialisation
        return back()->with('success', 'Si ce compte existe, un email de réinitialisation a été envoyé.');
    }
}
