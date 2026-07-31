<?php

namespace App\Notifications\Admin;

use App\Models\CandidatureCompetition;
use App\Models\User;
use Illuminate\Bus\Queueable;
use Illuminate\Notifications\Notification;

class NouvelleCandidatureCompetition extends Notification
{
    use Queueable;

    public function __construct(
        private readonly CandidatureCompetition $candidature,
        private readonly User $membre
    ) {}

    public function via(object $notifiable): array
    {
        return ['database'];
    }

    public function toArray(object $notifiable): array
    {
        return [
            'type'    => 'candidature_competition',
            'icon'    => 'bi-trophy',
            'titre'   => 'Nouvelle candidature compétition',
            'message' => $this->membre->nom_complet.' a soumis une candidature pour "'.$this->candidature->competition->titre.'".',
            'url'     => route('admin.competitions.show', $this->candidature->competition_id),
        ];
    }
}
