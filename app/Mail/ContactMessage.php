<?php

namespace App\Mail;

use Illuminate\Bus\Queueable;
use Illuminate\Mail\Mailable;
use Illuminate\Mail\Mailables\Address;
use Illuminate\Mail\Mailables\Content;
use Illuminate\Mail\Mailables\Envelope;
use Illuminate\Queue\SerializesModels;

class ContactMessage extends Mailable
{
    use Queueable, SerializesModels;

    public function __construct(
        public readonly string $nomExpediteur,
        public readonly string $emailExpediteur,
        public readonly string $sujet,
        public readonly string $corps,
    ) {}

    public function envelope(): Envelope
    {
        return new Envelope(
            replyTo: [new Address($this->emailExpediteur, $this->nomExpediteur)],
            subject: '[Contact FAACI] '.$this->sujet,
        );
    }

    public function content(): Content
    {
        return new Content(
            view: 'emails.contact',
        );
    }
}
