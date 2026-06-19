<?php

namespace App\Console\Commands;

use App\Models\Evenement;
use App\Models\User;
use App\Notifications\EvenementAVenir;
use Illuminate\Console\Command;
use Illuminate\Support\Facades\Notification;

class NotifierEvenementsProchains extends Command
{
    protected $signature   = 'notifier:evenements';
    protected $description = 'Envoie des rappels aux membres pour les événements J-7 et J-1.';

    public function handle(): int
    {
        $membres = User::role('membre')->where('statut', User::STATUT_ACTIF)->get();

        if ($membres->isEmpty()) {
            $this->info('Aucun membre actif.');
            return self::SUCCESS;
        }

        foreach ([7, 1] as $jours) {
            $debut  = now()->addDays($jours)->startOfDay();
            $fin    = now()->addDays($jours)->endOfDay();

            $evenements = Evenement::where('statut', Evenement::STATUT_PUBLIE)
                ->whereBetween('date_debut', [$debut, $fin])
                ->get();

            foreach ($evenements as $evenement) {
                Notification::send($membres, new EvenementAVenir($evenement, $jours));
                $this->info("Rappel J-{$jours} envoyé pour : {$evenement->titre}");
            }
        }

        $this->info('Notifications événements envoyées.');
        return self::SUCCESS;
    }
}
