<?php

namespace App\Http\Controllers\Auth;

use App\Http\Controllers\Controller;
use App\Mail\AdhesionRecue;
use App\Models\User;
use App\Notifications\NouvelleDemandeAdhesion;
use Illuminate\Auth\Events\Registered;
use Illuminate\Http\RedirectResponse;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\Log;
use Illuminate\Support\Facades\Mail;
use Illuminate\Support\Facades\Notification;
use Illuminate\Validation\Rules;
use Illuminate\View\View;

class RegisteredUserController extends Controller
{
    /**
     * Affiche le formulaire de demande d'adhésion.
     */
    public function create(): View
    {
        return view('auth.register');
    }

    /**
     * Traite une demande d'adhésion : crée le compte avec le rôle "membre"
     * et le statut "en_attente", en attente de validation par un admin.
     */
    public function store(Request $request): RedirectResponse
    {
        $validated = $request->validate([
            'prenom' => ['required', 'string', 'max:100'],
            'nom' => ['required', 'string', 'max:100'],
            'email' => ['required', 'string', 'email', 'max:255', 'unique:users,email'],
            'telephone' => ['nullable', 'string', 'max:30'],
            'password' => ['required', 'confirmed', Rules\Password::defaults()],
        ]);

        $user = User::create([
            'prenom' => $validated['prenom'],
            'nom' => $validated['nom'],
            'email' => $validated['email'],
            'telephone' => $validated['telephone'] ?? null,
            'password' => $validated['password'],
            'statut' => User::STATUT_EN_ATTENTE,
        ]);

        $user->assignRole('membre');

        event(new Registered($user));

        try {
            Mail::to($user->email)->queue(new AdhesionRecue($user));

            $admins = User::role(['admin', 'super_admin'])->get();
            Notification::send($admins, new NouvelleDemandeAdhesion($user));
        } catch (\Throwable $e) {
            Log::error('Erreur lors de la notification de la nouvelle demande d\'adhésion : '.$e->getMessage());
        }

        Auth::login($user);

        return redirect()->route('compte.statut');
    }
}
