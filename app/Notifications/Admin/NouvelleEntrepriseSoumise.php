<?php

namespace App\Notifications\Admin;

use App\Models\Entreprise;
use App\Models\User;
use Illuminate\Bus\Queueable;
use Illuminate\Notifications\Notification;

class NouvelleEntrepriseSoumise extends Notification
{
    use Queueable;

    public function __construct(
        private readonly Entreprise $entreprise,
        private readonly User $membre,
        private readonly bool $modification = false
    ) {}

    public function via(object $notifiable): array
    {
        return ['database'];
    }

    public function toArray(object $notifiable): array
    {
        $action = $this->modification ? 'modifié l\'entreprise' : 'soumis l\'entreprise';

        return [
            'type'    => 'entreprise_soumise',
            'icon'    => 'bi-building',
            'titre'   => $this->modification ? 'Entreprise modifiée — re-validation' : 'Nouvelle entreprise à valider',
            'message' => $this->membre->nom_complet.' a '.$action.' "'.$this->entreprise->nom.'" pour validation.',
            'url'     => route('admin.entreprises.show', $this->entreprise),
        ];
    }
}
