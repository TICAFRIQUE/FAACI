<?php

namespace App\Notifications\Admin;

use App\Models\Don;
use App\Models\User;
use Illuminate\Bus\Queueable;
use Illuminate\Notifications\Notification;

class NouveauDon extends Notification
{
    use Queueable;

    public function __construct(
        private readonly Don $don,
        private readonly User $membre
    ) {}

    public function via(object $notifiable): array
    {
        return ['database'];
    }

    public function toArray(object $notifiable): array
    {
        $nature  = match ($this->don->nature) {
            'argent'   => 'un don en argent de '.number_format($this->don->montant ?? 0, 0, ',', ' ').' FCFA',
            'materiel' => 'un don matériel',
            default    => 'un don',
        };

        return [
            'type'    => 'nouveau_don',
            'icon'    => 'bi-gift',
            'titre'   => 'Nouveau don à confirmer',
            'message' => $this->membre->nom_complet.' a soumis '.$nature.'.',
            'url'     => route('admin.dons.show', $this->don),
        ];
    }
}
