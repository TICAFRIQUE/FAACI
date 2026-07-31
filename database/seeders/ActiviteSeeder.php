<?php

namespace Database\Seeders;

use App\Models\Activite;
use Illuminate\Database\Seeder;

class ActiviteSeeder extends Seeder
{
    public function run(): void
    {
        if (Activite::count() > 0) return;

        Activite::query()->delete();

        $activites = [
            [
                'icone'       => 'bi-people-fill',
                'titre'       => 'Annuaire des membres',
                'description' => "Retrouvez tous les Alumni AIESEC CI : profils enrichis, expertises, secteurs d'activité et contacts professionnels. Construisez votre réseau en toute simplicité.",
                'ordre'       => 1,
                'actif'       => true,
            ],
            [
                'icone'       => 'bi-buildings',
                'titre'       => 'Entreprises Alumni',
                'description' => "Base centralisée des sociétés fondées ou dirigées par des membres FAACI. Un annuaire économique unique pour valoriser l'entrepreneuriat Alumni.",
                'ordre'       => 2,
                'actif'       => true,
            ],
            [
                'icone'       => 'bi-lightbulb-fill',
                'titre'       => 'Projets & Financement',
                'description' => "Soumettez vos projets à financement communautaire. Les membres soutiennent les initiatives portées par leurs pairs, sans paiement en ligne.",
                'ordre'       => 3,
                'actif'       => true,
            ],
            [
                'icone'       => 'bi-graph-up-arrow',
                'titre'       => "Opportunités d'affaires",
                'description' => "Appels d'offres, partenariats, sous-traitance, recherche de fournisseurs : publiez et consultez les opportunités en interne.",
                'ordre'       => 4,
                'actif'       => true,
            ],
            [
                'icone'       => 'bi-briefcase-fill',
                'titre'       => "Offres d'emploi",
                'description' => "Recrutez ou trouvez votre prochaine opportunité professionnelle au sein du réseau Alumni. CDI, CDD, stage, freelance et alternance.",
                'ordre'       => 5,
                'actif'       => true,
            ],
            [
                'icone'       => 'bi-calendar-event-fill',
                'titre'       => 'Événements & Pitchs',
                'description' => "Calendrier complet : rencontres réseau, webinaires, sessions de pitch, assemblées générales et événements communautaires.",
                'ordre'       => 6,
                'actif'       => true,
            ],
        ];

        foreach ($activites as $data) {
            Activite::create($data);
        }
    }
}
