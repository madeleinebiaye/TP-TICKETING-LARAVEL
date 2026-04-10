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
            if ($request->expectsJson()) {
                return response()->json(['message' => 'Authentification requise.'], 401);
            }

            return redirect('/login');
        }

        if (!in_array($user->role, $roles, true)) {
            if ($request->expectsJson()) {
                return response()->json(['message' => 'Accès non autorisé pour ce rôle.'], 403);
            }

            return redirect('/accueil')->with('error', 'Accès non autorisé pour ce rôle.');
        }

        return $next($request);
    }
}