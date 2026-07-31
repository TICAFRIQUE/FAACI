<?php

namespace App\Notifications\Admin;

use App\Models\Contribution;
use App\Models\User;
use Illuminate\Bus\Queueable;
use Illuminate\Notifications\Notification;

class NouvellePromesseInvestissement extends Notification
{
    use Queueable;

    public function __construct(
        private readonly Contribution $contribution,
        private readonly User $membre
    ) {}

    public function via(object $notifiable): array
    {
        return ['database'];
    }

    public function toArray(object $notifiable): array
    {
        $montant = number_format($this->contribution->montant_promis, 0, ',', ' ');

        return [
            'type'    => 'promesse_investissement',
            'icon'    => 'bi-cash-stack',
            'titre'   => 'Nouvelle promesse d\'investissement',
            'message' => $this->membre->nom_complet.' a promis '.$montant.' FCFA sur le projet "'.$this->contribution->projet->titre.'".',
            'url'     => route('admin.contributions.show', $this->contribution),
        ];
    }
}
