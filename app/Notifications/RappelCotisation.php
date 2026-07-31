<?php

namespace App\Notifications;

use App\Models\TypeCotisation;
use Illuminate\Bus\Queueable;
use Illuminate\Notifications\Notification;

class RappelCotisation extends Notification
{
    use Queueable;

    public function __construct(public TypeCotisation $type) {}

    public function via(object $_notifiable): array
    {
        return ['database'];
    }

    public function toDatabase(object $_notifiable): array
    {
        return [
            'titre'   => 'Rappel cotisation — ' . $this->type->nom,
            'message' => 'Vous avez des mois non soldés pour la cotisation "'.$this->type->nom
                        .'". Pensez à déclarer votre paiement.',
            'lien'    => route('membre.cotisations.index'),
            'type'    => 'cotisation',
        ];
    }
}
