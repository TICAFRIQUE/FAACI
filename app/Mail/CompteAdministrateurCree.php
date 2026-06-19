<?php

namespace App\Mail;

use App\Models\User;
use Illuminate\Bus\Queueable;
use Illuminate\Mail\Mailable;
use Illuminate\Mail\Mailables\Content;
use Illuminate\Mail\Mailables\Envelope;
use Illuminate\Queue\SerializesModels;

class CompteAdministrateurCree extends Mailable
{
    use Queueable, SerializesModels;

    public function __construct(public User $user, public string $lienMotDePasse)
    {
    }

    public function envelope(): Envelope
    {
        return new Envelope(
            subject: 'Votre compte administrateur FAACI a été créé',
        );
    }

    public function content(): Content
    {
        return new Content(
            markdown: 'emails.compte-administrateur-cree',
        );
    }
}
