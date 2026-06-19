<?php

namespace App\Http\Controllers\Membre;

use App\Http\Controllers\Controller;
use Illuminate\Http\RedirectResponse;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Hash;
use Illuminate\Support\Facades\Log;
use Illuminate\Validation\Rules\Password;
use Illuminate\View\View;

class MotDePasseController extends Controller
{
    public function edit(): View
    {
        return view('membre.mot-de-passe.edit');
    }

    public function update(Request $request): RedirectResponse
    {
        $request->validate([
            'current_password'      => ['required', 'string'],
            'password'              => ['required', 'string', 'confirmed', Password::min(8)],
        ], [
            'current_password.required' => 'Veuillez saisir votre mot de passe actuel.',
            'password.required'         => 'Veuillez saisir un nouveau mot de passe.',
            'password.confirmed'        => 'La confirmation ne correspond pas au nouveau mot de passe.',
            'password.min'              => 'Le mot de passe doit contenir au moins 8 caractères.',
        ]);

        $user = auth()->user();

        if (! Hash::check($request->current_password, $user->password)) {
            return back()->withErrors(['current_password' => 'Le mot de passe actuel est incorrect.']);
        }

        try {
            $user->update(['password' => $request->password]);

            return redirect()->route('membre.mot-de-passe.edit')
                ->with('status', 'Mot de passe modifié avec succès.');
        } catch (\Throwable $e) {
            Log::error('Erreur changement mot de passe #'.auth()->id().' : '.$e->getMessage());

            return back()->with('error', 'Une erreur est survenue. Veuillez réessayer.');
        }
    }
}
