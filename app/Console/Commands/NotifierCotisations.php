<?php

namespace App\Console\Commands;

use App\Models\PaiementCotisation;
use App\Models\TypeCotisation;
use App\Models\User;
use App\Notifications\RappelCotisation;
use Carbon\Carbon;
use Illuminate\Console\Command;

class NotifierCotisations extends Command
{
    protected $signature   = 'notifier:cotisations';
    protected $description = 'Envoie des rappels aux membres ayant des mois non soldés.';

    public function handle(): void
    {
        $types   = TypeCotisation::where('actif', true)->whereNotNull('date_debut')->get();
        $today   = Carbon::now()->startOfMonth();
        $membres = User::role('membre')->where('statut', User::STATUT_ACTIF)->get();
        $total   = 0;

        foreach ($types as $type) {
            $debut = Carbon::parse($type->date_debut)->startOfMonth();
            $fin   = $type->date_fin ? Carbon::parse($type->date_fin)->endOfMonth() : $today->copy();

            foreach ($membres as $membre) {
                // Collecter les mois déjà couverts par des paiements validés
                $moisPayes = PaiementCotisation::where('utilisateur_id', $membre->id)
                    ->where('type_cotisation_id', $type->id)
                    ->where('statut', PaiementCotisation::STATUT_VALIDE)
                    ->get()
                    ->flatMap(fn($p) => $p->mois_couverts ?? [])
                    ->unique()
                    ->all();

                // Vérifier si un mois échu est non payé
                $current = $debut->copy();
                $aRetard = false;

                while ($current <= min($fin, $today)) {
                    $moisStr = $current->format('Y-m');
                    if (!in_array($moisStr, $moisPayes)) {
                        $aRetard = true;
                        break;
                    }
                    $current->addMonth();
                }

                if ($aRetard) {
                    $membre->notify(new RappelCotisation($type));
                    $total++;
                }
            }
        }

        $this->info("Rappels envoyés : {$total} membre(s).");
    }
}
