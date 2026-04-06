<?php

namespace App\Http\Middleware;

use Closure;
use Illuminate\Http\RedirectResponse;
use Illuminate\Http\Request;

class RoleMiddleware
{
    public function handle(Request $request, Closure $next, string ...$roles): mixed
    {
        $user = $request->user();

        if (!$user) {
            return redirect('/login');
        }

        if (!in_array($user->role, $roles, true)) {
            return redirect('/accueil')->with('error', 'Accès non autorisé pour ce rôle.');
        }

        return $next($request);
    }
}