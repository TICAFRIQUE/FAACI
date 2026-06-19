<?php

namespace App\Http\Controllers;

use App\Mail\ContactMessage;
use App\Models\ParametreSite;
use Illuminate\Http\RedirectResponse;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Mail;

class ContactController extends Controller
{
    public function send(Request $request): RedirectResponse
    {
        $validated = $request->validate([
            'nom' => ['required', 'string', 'max:255'],
            'email' => ['required', 'email', 'max:255'],
            'sujet' => ['required', 'string', 'max:255'],
            'message' => ['required', 'string', 'max:5000'],
        ]);

        $destinataire = ParametreSite::valeur('email_contact');

        if ($destinataire) {
            Mail::to($destinataire)->queue(new ContactMessage(
                nomExpediteur:   $validated['nom'],
                emailExpediteur: $validated['email'],
                sujet:           $validated['sujet'],
                corps:           $validated['message'],
            ));
        }

        return redirect(route('accueil').'#contact')
            ->with('status', 'Votre message a bien été envoyé. Nous vous répondrons dans les meilleurs délais.');
    }
}
