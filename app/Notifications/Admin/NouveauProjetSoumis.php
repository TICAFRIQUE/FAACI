<?php

namespace App\Notifications\Admin;

use App\Models\Projet;
use App\Models\User;
use Illuminate\Bus\Queueable;
use Illuminate\Notifications\Notification;

class NouveauProjetSoumis extends Notification
{
    use Queueable;

    public function __construct(
        private readonly Projet $projet,
        private readonly User $membre
    ) {}

    public function via(object $notifiable): array
    {
        return ['database'];
    }

    public function toArray(object $notifiable): array
    {
        return [
            'type'    => 'projet_soumis',
            'icon'    => 'bi-lightbulb',
            'titre'   => 'Nouveau projet à valider',
            'message' => $this->membre->nom_complet.' a soumis le projet "'.$this->projet->titre.'" pour validation.',
            'url'     => route('admin.projets.show', $this->projet),
        ];
    }
}
