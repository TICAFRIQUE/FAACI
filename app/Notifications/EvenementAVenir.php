<?php

namespace App\Notifications;

use App\Models\Evenement;
use Illuminate\Bus\Queueable;
use Illuminate\Notifications\Notification;

class EvenementAVenir extends Notification
{
    use Queueable;

    public function __construct(public readonly Evenement $evenement, public readonly int $joursRestants = 0) {}

    public function via(object $notifiable): array
    {
        return ['database'];
    }

    public function toArray(object $notifiable): array
    {
        $message = match (true) {
            $this->joursRestants === 1 => "L'événement « {$this->evenement->titre} » a lieu demain.",
            $this->joursRestants === 7 => "Rappel : l'événement « {$this->evenement->titre} » est dans 7 jours.",
            default                    => "Nouvel événement publié : « {$this->evenement->titre} ».",
        };

        return [
            'type'         => 'evenement',
            'message'      => $message,
            'evenement_id' => $this->evenement->id,
            'titre'        => $this->evenement->titre,
            'date_debut'   => $this->evenement->date_debut->toIso8601String(),
            'lieu'         => $this->evenement->lieu,
            'url'          => route('membre.evenements.show', $this->evenement),
        ];
    }
}
