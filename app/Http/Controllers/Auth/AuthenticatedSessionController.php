<?php

namespace App\Http\Controllers\Auth;

use App\Http\Controllers\Controller;
use App\Http\Requests\Auth\LoginRequest;
use App\Models\User;
use Illuminate\Http\RedirectResponse;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use Illuminate\View\View;

class AuthenticatedSessionController extends Controller
{
    /**
     * Affiche le formulaire de connexion.
     */
    public function create(): View
    {
        return view('auth.login');
    }

    /**
     * Traite une tentative de connexion.
     */
    public function store(LoginRequest $request): RedirectResponse
    {
        $request->authenticate();

        $request->session()->regenerate();

        return redirect()->intended($this->redirectPath($request->user()));
    }

    /**
     * Déconnecte l'utilisateur.
     */
    public function destroy(Request $request): RedirectResponse
    {
        Auth::guard('web')->logout();

        $request->session()->invalidate();
        $request->session()->regenerateToken();

        return redirect('/');
    }

    /**
     * Détermine la page de redirection après connexion selon le rôle et le statut.
     */
    protected function redirectPath(User $user): string
    {
        if (! $user->estActif()) {
            return route('compte.statut');
        }

        if ($user->hasAnyRole(['admin', 'super_admin'])) {
            return route('admin.dashboard');
        }

        return route('dashboard');
    }
}
