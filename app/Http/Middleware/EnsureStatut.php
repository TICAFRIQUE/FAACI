<?php

namespace App\Http\Middleware;

use Closure;
use Illuminate\Http\Request;
use Symfony\Component\HttpFoundation\Response;

class EnsureStatut
{
    /**
     * Vérifie que l'utilisateur authentifié possède un des statuts autorisés.
     *
     * Exemple : ->middleware(['auth', 'role:membre', 'statut:actif'])
     */
    public function handle(Request $request, Closure $next, string ...$statuts): Response
    {
        $user = $request->user();

        if (! $user || ! in_array($user->statut, $statuts, true)) {
            abort(403, "Votre compte n'a pas accès à cette section.");
        }

        return $next($request);
    }
}
