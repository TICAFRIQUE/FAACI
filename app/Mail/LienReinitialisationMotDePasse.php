<?php

namespace App\Mail;

use App\Models\User;
use Illuminate\Bus\Queueable;
use Illuminate\Mail\Mailable;
use Illuminate\Mail\Mailables\Content;
use Illuminate\Mail\Mailables\Envelope;
use Illuminate\Queue\SerializesModels;

class LienReinitialisationMotDePasse extends Mailable
{
    use Queueable, SerializesModels;

    public function __construct(public User $user, public string $lienMotDePasse)
    {
    }

    public function envelope(): Envelope
    {
        return new Envelope(
            subject: 'Réinitialisation de votre mot de passe FAACI',
        );
    }

    public function content(): Content
    {
        return new Content(
            markdown: 'emails.lien-reinitialisation-mot-de-passe',
        );
    }
}
