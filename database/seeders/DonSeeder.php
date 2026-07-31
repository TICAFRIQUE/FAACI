<?php

namespace Database\Seeders;

use App\Models\Don;
use App\Models\User;
use Illuminate\Database\Seeder;

class DonSeeder extends Seeder
{
    public function run(): void
    {
        $membres = User::role('membre')->where('statut', User::STATUT_ACTIF)->get();

        if ($membres->isEmpty()) {
            $this->command->warn('Aucun membre actif trouvé. Lancez d\'abord MembreSeeder.');
            return;
        }

        $admin = User::role(['admin', 'super_admin'])->first();

        $dons = [
            // Dons financiers confirmés
            [
                'porteur_index'  => 0,  // Kofi Asante - Finance
                'nature'         => 'argent',
                'libelle'        => 'Contribution pour l\'événement annuel FAACI 2026',
                'montant'        => 150000,
                'moyen_paiement' => 'orange_money',
                'description'    => 'Don pour couvrir une partie des frais d\'organisation de la journée annuelle des Alumni.',
                'valeur_estimee' => null,
                'statut'         => Don::STATUT_CONFIRME,
            ],
            [
                'porteur_index'  => 3,  // Fatoumata Diallo - Santé
                'nature'         => 'argent',
                'libelle'        => 'Soutien au fonds de solidarité FAACI',
                'montant'        => 75000,
                'moyen_paiement' => 'wave',
                'description'    => 'Don au fonds de solidarité destiné aux membres en difficulté.',
                'valeur_estimee' => null,
                'statut'         => Don::STATUT_CONFIRME,
            ],
            [
                'porteur_index'  => 6,  // Ibrahim Ouédraogo - Immobilier
                'nature'         => 'argent',
                'libelle'        => 'Don pour la bourse étudiante Alumni 2026',
                'montant'        => 300000,
                'moyen_paiement' => 'virement',
                'description'    => 'Financement partiel d\'une bourse d\'études pour un jeune étudiant recommandé par la fondation.',
                'valeur_estimee' => null,
                'statut'         => Don::STATUT_CONFIRME,
            ],
            [
                'porteur_index'  => 18, // Hervé Brou - Banque
                'nature'         => 'argent',
                'libelle'        => 'Contribution au projet de réhabilitation du local FAACI',
                'montant'        => 500000,
                'moyen_paiement' => 'virement',
                'description'    => 'Don pour la rénovation et l\'aménagement du local associatif de la FAACI.',
                'valeur_estimee' => null,
                'statut'         => Don::STATUT_CONFIRME,
            ],
            [
                'porteur_index'  => 20, // Armand Koffi - Assurance
                'nature'         => 'argent',
                'libelle'        => 'Don anniversaire 10 ans FAACI',
                'montant'        => 100000,
                'moyen_paiement' => 'cash',
                'description'    => 'Don symbolique à l\'occasion des 10 ans de la fondation.',
                'valeur_estimee' => null,
                'statut'         => Don::STATUT_CONFIRME,
            ],
            [
                'porteur_index'  => 5,  // Marie Bamba
                'nature'         => 'argent',
                'libelle'        => 'Soutien programme mentorat jeunes',
                'montant'        => 200000,
                'moyen_paiement' => 'orange_money',
                'description'    => 'Don pour financer les ateliers de mentorat organisés par la FAACI pour les jeunes diplômés.',
                'valeur_estimee' => null,
                'statut'         => Don::STATUT_CONFIRME,
            ],

            // Dons matériels confirmés
            [
                'porteur_index'  => 22, // Wilfried Dago - Informatique
                'nature'         => 'materiel',
                'libelle'        => 'Don de matériel informatique pour le secrétariat',
                'montant'        => null,
                'moyen_paiement' => null,
                'description'    => 'Remise de 2 ordinateurs portables reconditionnés et d\'une imprimante laser pour équiper le secrétariat de la FAACI.',
                'valeur_estimee' => '2 laptops + 1 imprimante (~350 000 FCFA)',
                'statut'         => Don::STATUT_CONFIRME,
            ],
            [
                'porteur_index'  => 17, // Christelle Atchori - Mode
                'nature'         => 'materiel',
                'libelle'        => 'Don de tenues pour l\'équipe dirigeante',
                'montant'        => null,
                'moyen_paiement' => null,
                'description'    => 'Confection et don de 15 tenues officielles aux couleurs de la FAACI pour les membres du bureau.',
                'valeur_estimee' => '15 tenues (~225 000 FCFA)',
                'statut'         => Don::STATUT_CONFIRME,
            ],
            [
                'porteur_index'  => 23, // Estelle N'Dri - Restauration
                'nature'         => 'materiel',
                'libelle'        => 'Traiteur offert pour l\'AG annuelle',
                'montant'        => null,
                'moyen_paiement' => null,
                'description'    => 'Prestation traiteur complète offerte pour l\'assemblée générale annuelle de la fondation (80 personnes).',
                'valeur_estimee' => 'Repas pour 80 personnes (~400 000 FCFA)',
                'statut'         => Don::STATUT_CONFIRME,
            ],

            // Dons "autre" confirmés
            [
                'porteur_index'  => 11, // Clarisse Ehui - Droit
                'nature'         => 'autre',
                'libelle'        => 'Assistance juridique bénévole pour la FAACI',
                'montant'        => null,
                'moyen_paiement' => null,
                'description'    => 'Mise à disposition gratuite des services juridiques du cabinet pour la révision des statuts et du règlement intérieur de la fondation. Durée estimée : 8 heures de travail.',
                'valeur_estimee' => '8h de conseil juridique (~240 000 FCFA)',
                'statut'         => Don::STATUT_CONFIRME,
            ],
            [
                'porteur_index'  => 12, // Théodore Gbagbo - Médias
                'nature'         => 'autre',
                'libelle'        => 'Production vidéo institutionnelle offerte',
                'montant'        => null,
                'moyen_paiement' => null,
                'description'    => 'Réalisation bénévole d\'un film institutionnel de 5 minutes présentant la FAACI et ses missions pour les réseaux sociaux et le site web.',
                'valeur_estimee' => 'Production vidéo 5 min (~500 000 FCFA)',
                'statut'         => Don::STATUT_CONFIRME,
            ],

            // Dons en attente de validation
            [
                'porteur_index'  => 1,  // Aminata Coulibaly - Marketing
                'nature'         => 'argent',
                'libelle'        => 'Don pour les frais de communication annuels',
                'montant'        => 80000,
                'moyen_paiement' => 'wave',
                'description'    => 'Contribution pour couvrir une partie des abonnements aux outils de communication digitale de la fondation.',
                'valeur_estimee' => null,
                'statut'         => Don::STATUT_EN_ATTENTE,
            ],
            [
                'porteur_index'  => 14, // Romuald Tape - Logistique
                'nature'         => 'materiel',
                'libelle'        => 'Don de mobilier de bureau',
                'montant'        => null,
                'moyen_paiement' => null,
                'description'    => 'Remise de 4 chaises de bureau et d\'une étagère pour compléter l\'équipement du local.',
                'valeur_estimee' => '4 chaises + 1 étagère (~120 000 FCFA)',
                'statut'         => Don::STATUT_EN_ATTENTE,
            ],
            [
                'porteur_index'  => 15, // Nadia Meité - RH
                'nature'         => 'autre',
                'libelle'        => 'Animation d\'un atelier de développement personnel',
                'montant'        => null,
                'moyen_paiement' => null,
                'description'    => 'Proposition d\'animer bénévolement un atelier de 3 heures sur la gestion du stress et le leadership pour les membres de la FAACI.',
                'valeur_estimee' => 'Atelier 3h (~120 000 FCFA)',
                'statut'         => Don::STATUT_EN_ATTENTE,
            ],
            [
                'porteur_index'  => 25, // Ines Kouakou - Pharmaceutique
                'nature'         => 'argent',
                'libelle'        => 'Soutien fonds de santé FAACI',
                'montant'        => 50000,
                'moyen_paiement' => 'cash',
                'description'    => 'Don pour alimenter le fonds d\'aide médicale d\'urgence destiné aux membres.',
                'valeur_estimee' => null,
                'statut'         => Don::STATUT_EN_ATTENTE,
            ],
        ];

        $crees = 0;

        foreach ($dons as $data) {
            $donateur = $membres->values()->get($data['porteur_index']) ?? $membres->first();

            Don::create([
                'utilisateur_id'  => $donateur->id,
                'nature'          => $data['nature'],
                'libelle'         => $data['libelle'],
                'montant'         => $data['montant'],
                'moyen_paiement'  => $data['moyen_paiement'],
                'description'     => $data['description'],
                'valeur_estimee'  => $data['valeur_estimee'],
                'statut'          => $data['statut'],
                'valide_par'      => $data['statut'] === Don::STATUT_CONFIRME ? $admin?->id : null,
                'date_validation' => $data['statut'] === Don::STATUT_CONFIRME ? now()->subDays(rand(2, 20)) : null,
                'created_at'      => now()->subDays(rand(5, 90)),
            ]);

            $crees++;
        }

        $this->command->info("{$crees} dons créés avec succès.");
    }
}
