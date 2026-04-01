<?php

namespace App\Http\Middleware;

use Closure;
use Illuminate\Http\Request;

class AuthMiddleware
{
    public function handle(Request $request, Closure $next)
    {
        // Vérifie si utilisateur connecté
        if (!session()->has('user_id')) {

            // message optionnel
            return redirect('/')->with('error', 'Veuillez vous connecter.');
        }

        return $next($request);
    }
}