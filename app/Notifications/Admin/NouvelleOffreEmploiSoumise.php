<?php

namespace App\Notifications\Admin;

use App\Models\OffreEmploi;
use App\Models\User;
use Illuminate\Bus\Queueable;
use Illuminate\Notifications\Notification;

class NouvelleOffreEmploiSoumise extends Notification
{
    use Queueable;

    public function __construct(
        private readonly OffreEmploi $offre,
        private readonly User $membre
    ) {}

    public function via(object $notifiable): array
    {
        return ['database'];
    }

    public function toArray(object $notifiable): array
    {
        return [
            'type'    => 'offre_emploi_soumise',
            'icon'    => 'bi-briefcase',
            'titre'   => 'Nouvelle offre d\'emploi à valider',
            'message' => $this->membre->nom_complet.' a soumis l\'offre "'.$this->offre->titre.'".',
            'url'     => route('admin.emplois.show', $this->offre),
        ];
    }
}
