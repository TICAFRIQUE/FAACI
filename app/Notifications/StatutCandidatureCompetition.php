<?php

namespace App\Notifications;

use App\Models\CandidatureCompetition;
use Illuminate\Bus\Queueable;
use Illuminate\Notifications\Notification;

class StatutCandidatureCompetition extends Notification
{
    use Queueable;

    public function __construct(public readonly CandidatureCompetition $candidature) {}

    public function via(object $_notifiable): array
    {
        return ['database'];
    }

    public function toArray(object $_notifiable): array
    {
        $titre      = $this->candidature->competition?->titre ?? 'Compétition';
        $projetTitre = $this->candidature->titre_projet;

        $message = match ($this->candidature->statut) {
            'selectionnee' => "Félicitations ! Votre projet « {$projetTitre} » a été sélectionné pour la compétition « {$titre} ».",
            'gagnante'     => "🏆 Votre projet « {$projetTitre} » est lauréat de la compétition « {$titre} ». Toutes nos félicitations !",
            'eliminee'     => "Votre candidature pour la compétition « {$titre} » (projet : {$projetTitre}) n'a pas été retenue. Merci pour votre participation.",
            default        => "Le statut de votre candidature pour « {$titre} » a été mis à jour.",
        };

        return [
            'type'           => 'competition',
            'message'        => $message,
            'statut'         => $this->candidature->statut,
            'competition_id' => $this->candidature->competition_id,
            'titre_projet'   => $projetTitre,
            'note_jury'      => $this->candidature->note_jury,
            'url'            => route('membre.competitions.show', $this->candidature->competition_id),
        ];
    }
}
