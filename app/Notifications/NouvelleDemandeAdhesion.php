<?php

namespace App\Notifications;

use App\Models\User;
use Illuminate\Bus\Queueable;
use Illuminate\Notifications\Notification;

class NouvelleDemandeAdhesion extends Notification
{
    use Queueable;

    public function __construct(public User $candidat)
    {
    }

    /**
     * Get the notification's delivery channels.
     *
     * @return array<int, string>
     */
    public function via(object $notifiable): array
    {
        return ['database'];
    }

    /**
     * Get the array representation of the notification.
     *
     * @return array<string, mixed>
     */
    public function toArray(object $notifiable): array
    {
        return [
            'titre' => 'Nouvelle demande d\'adhésion',
            'message' => "{$this->candidat->nom_complet} a soumis une demande d'adhésion.",
            'url' => route('admin.membres.show', $this->candidat),
        ];
    }
}
