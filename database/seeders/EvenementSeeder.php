<?php

namespace Database\Seeders;

use App\Models\Evenement;
use App\Support\Slug;
use Illuminate\Database\Seeder;

class EvenementSeeder extends Seeder
{
    public function run(): void
    {
        Evenement::query()->delete();

        $evenements = [
            [
                'titre'       => 'Alumni Networking Night — Abidjan Plateau',
                'description' => '<p>La grande soirée de networking du réseau FAACI revient pour une 4e édition au cœur du Plateau. Une soirée pour rencontrer vos pairs Alumni, partager vos actualités professionnelles et créer de nouvelles synergies.</p><p><strong>Programme :</strong></p><ul><li>18h30 : Accueil & cocktail</li><li>19h00 : Mots de la Présidente</li><li>19h15 : Speed networking (3 tours de 10 min)</li><li>20h30 : Pitch Slam Alumni (5 projets)</li><li>21h30 : Soirée libre & networking</li></ul><p>Dress code : Smart casual. Places limitées à 150 personnes.</p>',
                'type'        => 'networking',
                'date_debut'  => now()->addDays(20)->setTime(18, 30),
                'date_fin'    => now()->addDays(20)->setTime(23, 0),
                'lieu'        => 'Sofitel Abidjan Hôtel Ivoire, Plateau',
                'capacite_max' => 150,
                'est_public'  => true,
                'statut'      => Evenement::STATUT_PUBLIE,
                'image_url'   => 'https://picsum.photos/seed/event-networking/800/500',
            ],
            [
                'titre'       => 'Pitch Day Alumni — Saison 3',
                'description' => '<p>Le Pitch Day est l\'événement phare de l\'écosystème entrepreneurial FAACI. 8 projets d\'entreprise sélectionnés par le comité de la FAACI présentent leur vision devant un jury de 5 investisseurs et entrepreneurs Alumni.</p><p><strong>Les projets en compétition :</strong></p><ul><li>Une solution d\'agriculture de précision pour les planteurs de cacao</li><li>Une plateforme de e-learning en langues africaines</li><li>Un réseau de micro-dépôts pharmaceutiques ruraux</li><li>Une fintech de transferts d\'argent intra-africains</li><li>Et 4 autres projets innovants…</li></ul><p>Les 3 meilleurs projets bénéficieront d\'un accompagnement FAACI et pourront accéder au module de financement communautaire.</p>',
                'type'        => 'pitch',
                'date_debut'  => now()->addDays(35)->setTime(9, 0),
                'date_fin'    => now()->addDays(35)->setTime(17, 0),
                'lieu'        => 'CGECI Academy, Abidjan Plateau',
                'capacite_max' => 200,
                'est_public'  => true,
                'statut'      => Evenement::STATUT_PUBLIE,
                'image_url'   => 'https://picsum.photos/seed/event-pitch/800/500',
            ],
            [
                'titre'       => 'Webinaire : Stratégie de croissance pour les PME ivoiriennes',
                'description' => '<p>Dans ce webinaire interactif, trois experts Alumni partagent leurs stratégies éprouvées pour accélérer la croissance des PME en Côte d\'Ivoire dans un contexte économique en mutation rapide.</p><p><strong>Thèmes abordés :</strong></p><ul><li>Digitaliser son business sans se ruiner</li><li>Lever des fonds auprès des investisseurs locaux et régionaux</li><li>Exporter dans la sous-région CEDEAO : mode d\'emploi pratique</li><li>Fidéliser ses talents dans un marché du travail concurrentiel</li></ul><p>Le webinaire se tient sur Zoom. Le lien de connexion sera envoyé aux inscrits 24h avant l\'événement.</p>',
                'type'        => 'webinaire',
                'date_debut'  => now()->addDays(10)->setTime(10, 0),
                'date_fin'    => now()->addDays(10)->setTime(12, 30),
                'lieu'        => null,
                'lien_visio'  => 'https://zoom.us/j/faaci-webinaire-pme',
                'capacite_max' => 300,
                'est_public'  => false,
                'statut'      => Evenement::STATUT_PUBLIE,
                'image_url'   => 'https://picsum.photos/seed/event-webinaire/800/500',
            ],
            [
                'titre'       => 'Assemblée Générale Ordinaire FAACI 2025',
                'description' => '<p>La FAACI convoque tous ses membres actifs à l\'Assemblée Générale Ordinaire 2025. Conformément aux statuts de la fondation, la présence ou la représentation de chaque membre est vivement souhaitée.</p><p><strong>Ordre du jour :</strong></p><ol><li>Approbation du procès-verbal de l\'AGO 2024</li><li>Rapport moral de la Présidente</li><li>Rapport financier du Trésorier et approbation des comptes 2024</li><li>Présentation du plan stratégique 2025–2027</li><li>Présentation et vote du budget prévisionnel 2025</li><li>Questions diverses</li></ol><p>Les documents de séance (rapports, comptes, plan stratégique) sont disponibles en téléchargement sur la plateforme membre 15 jours avant l\'assemblée.</p>',
                'type'        => 'ag',
                'date_debut'  => now()->addDays(60)->setTime(9, 0),
                'date_fin'    => now()->addDays(60)->setTime(13, 0),
                'lieu'        => 'Chambre de Commerce et d\'Industrie de Côte d\'Ivoire, Plateau',
                'capacite_max' => null,
                'est_public'  => false,
                'statut'      => Evenement::STATUT_PUBLIE,
                'image_url'   => 'https://picsum.photos/seed/event-ag/800/500',
            ],
            [
                'titre'       => 'Réunion du Conseil d\'Administration — T2 2025',
                'description' => '<p>Réunion trimestrielle du Conseil d\'Administration de la FAACI, ouverte à l\'observation des membres actifs qui souhaitent suivre la gouvernance de la fondation.</p><p><strong>Points à l\'ordre du jour :</strong></p><ul><li>Revue des actions du T1 2025</li><li>État d\'avancement de la plateforme numérique</li><li>Bilan des partenariats en cours (Orange CI, CGECI…)</li><li>Préparation de l\'AGO 2025</li><li>Examen des nouvelles demandes d\'adhésion</li><li>Divers</li></ul>',
                'type'        => 'reunion',
                'date_debut'  => now()->addDays(7)->setTime(15, 0),
                'date_fin'    => now()->addDays(7)->setTime(18, 0),
                'lieu'        => 'Siège de la FAACI, Cocody Riviera',
                'capacite_max' => 30,
                'est_public'  => false,
                'statut'      => Evenement::STATUT_PUBLIE,
                'image_url'   => 'https://picsum.photos/seed/event-reunion/800/500',
            ],
        ];

        foreach ($evenements as $data) {
            $imageUrl = $data['image_url'];
            unset($data['image_url']);

            $data['slug'] = Slug::unique(Evenement::class, $data['titre']);

            $evenement = Evenement::create($data);

            try {
                $evenement->addMediaFromUrl($imageUrl)
                    ->usingFileName('evenement-' . $evenement->id . '.jpg')
                    ->toMediaCollection('image');
            } catch (\Throwable $e) {
                $this->command->warn("Image événement \"{$evenement->titre}\" non chargée : {$e->getMessage()}");
            }
        }

        $this->command->info('5 événements créés avec succès.');
    }
}
