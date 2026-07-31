<?php

namespace App\Notifications\Admin;

use App\Models\Candidature;
use App\Models\User;
use Illuminate\Bus\Queueable;
use Illuminate\Notifications\Notification;

class NouvelleCandidatureEmploi extends Notification
{
    use Queueable;

    public function __construct(
        private readonly Candidature $candidature,
        private readonly User $membre
    ) {}

    public function via(object $notifiable): array
    {
        return ['database'];
    }

    public function toArray(object $notifiable): array
    {
        return [
            'type'    => 'candidature_emploi',
            'icon'    => 'bi-person-check',
            'titre'   => 'Nouvelle candidature emploi',
            'message' => $this->membre->nom_complet.' a postulé à l\'offre "'.$this->candidature->offre->titre.'".',
            'url'     => route('admin.emplois.show', $this->candidature->offre_emploi_id),
        ];
    }
}
