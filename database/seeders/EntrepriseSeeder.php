<?php

namespace Database\Seeders;

use App\Models\Entreprise;
use App\Models\User;
use Illuminate\Database\Seeder;

class EntrepriseSeeder extends Seeder
{
    public function run(): void
    {
        $membres = User::role('membre')->where('statut', User::STATUT_ACTIF)->get();

        if ($membres->isEmpty()) {
            $this->command->warn('Aucun membre actif trouvé. Lancez d\'abord MembreSeeder.');
            return;
        }

        $admin = User::role(['admin', 'super_admin'])->first();

        // Chaque entrée : porteur_index correspond à l'index dans la liste des membres
        $entreprises = [
            [
                'porteur_index'  => 4,  // Sébastien N'Guessan - Technologie
                'nom'            => 'TechConnect CI',
                'secteur'        => 'Technologie',
                'description'    => 'Startup fintech spécialisée dans les solutions de paiement mobile et de gestion financière pour les PME ivoiriennes. Fondée en 2020, TechConnect CI accompagne plus de 200 entreprises dans leur transition numérique.',
                'localisation'   => 'Abidjan, Plateau',
                'site_web'       => 'https://techconnect-ci.example.com',
                'telephone'      => '+225 27 20 11 22 33',
                'email_contact'  => 'contact@techconnect-ci.example.com',
                'annee_creation' => '2020',
                'statut'         => Entreprise::STATUT_ACTIF,
            ],
            [
                'porteur_index'  => 2,  // Yves Traoré - Agriculture
                'nom'            => 'CoopCacao Centre-CI',
                'secteur'        => 'Agriculture',
                'description'    => 'Coopérative agricole regroupant 350 producteurs de cacao dans la région de Bouaké. Nous proposons des services de formation, de certification bio et de mise en marché directe avec les exportateurs.',
                'localisation'   => 'Bouaké, Vallée du Bandama',
                'site_web'       => null,
                'telephone'      => '+225 07 05 33 44 55',
                'email_contact'  => 'coop.cacao.centre@email.com',
                'annee_creation' => '2017',
                'statut'         => Entreprise::STATUT_ACTIF,
            ],
            [
                'porteur_index'  => 9,  // Sylvie Ahoussou - Agroalimentaire
                'nom'            => 'Saveurs d\'Ivoire SARL',
                'secteur'        => 'Agroalimentaire',
                'description'    => 'PME de transformation et conditionnement de produits locaux ivoiriens : noix de cajou grillées, huile de coco vierge, attiéké déshydraté. Distribution nationale et export vers la diaspora africaine en Europe.',
                'localisation'   => 'Daloa, Haut-Sassandra',
                'site_web'       => 'https://saveurs-ivoire.example.com',
                'telephone'      => '+225 05 07 00 11 22',
                'email_contact'  => 'saveurs.ivoire@email.com',
                'annee_creation' => '2019',
                'statut'         => Entreprise::STATUT_ACTIF,
            ],
            [
                'porteur_index'  => 16, // Landry Gnagne - Énergies renouvelables
                'nom'            => 'SolarAfrik',
                'secteur'        => 'Énergies renouvelables',
                'description'    => 'Entreprise d\'installation et de maintenance de systèmes solaires photovoltaïques pour les ménages ruraux et les entreprises. Partenaire officiel de plusieurs programmes d\'électrification rurale en Côte d\'Ivoire.',
                'localisation'   => 'Abidjan, Yopougon',
                'site_web'       => 'https://solarafrik.example.com',
                'telephone'      => '+225 07 07 77 99 11',
                'email_contact'  => 'info@solarafrik.example.com',
                'annee_creation' => '2018',
                'statut'         => Entreprise::STATUT_ACTIF,
            ],
            [
                'porteur_index'  => 8,  // Patrick Zoro - BTP
                'nom'            => 'Bâti-Durable CI',
                'secteur'        => 'BTP',
                'description'    => 'Bureau d\'études et entreprise de construction spécialisée dans les bâtiments à faible impact environnemental. Utilisation de matériaux locaux et techniques de construction passive pour réduire la consommation énergétique.',
                'localisation'   => 'Abidjan, Cocody',
                'site_web'       => null,
                'telephone'      => '+225 07 07 99 00 11',
                'email_contact'  => 'bati.durable@email.com',
                'annee_creation' => '2017',
                'statut'         => Entreprise::STATUT_ACTIF,
            ],
            [
                'porteur_index'  => 7,  // Awa Koné - Éducation
                'nom'            => 'École Bilingue Les Étoiles',
                'secteur'        => 'Éducation',
                'description'    => 'École primaire bilingue français-anglais accueillant 450 élèves de la maternelle au CM2. Programme enrichi avec des cours d\'informatique dès la CP et un partenariat avec des écoles britanniques.',
                'localisation'   => 'Abidjan, Cocody',
                'site_web'       => 'https://ecoile-les-etoiles.example.com',
                'telephone'      => '+225 01 01 88 99 00',
                'email_contact'  => 'inscription@ecole-etoiles.example.com',
                'annee_creation' => '2021',
                'statut'         => Entreprise::STATUT_ACTIF,
            ],
            [
                'porteur_index'  => 17, // Christelle Atchori - Mode & Textile
                'nom'            => 'Atelier Nzassa',
                'secteur'        => 'Mode & Textile',
                'description'    => 'Marque de prêt-à-porter africaine contemporaine alliant wax, bogolan et kente dans des créations modernes. Collections disponibles en boutique à Abidjan et en ligne avec livraison internationale.',
                'localisation'   => 'Abidjan, Marcory',
                'site_web'       => 'https://nzassa.example.com',
                'telephone'      => '+225 05 05 88 00 22',
                'email_contact'  => 'atelier@nzassa.example.com',
                'annee_creation' => '2021',
                'statut'         => Entreprise::STATUT_ACTIF,
            ],
            [
                'porteur_index'  => 5,  // Marie Bamba - Commerce International
                'nom'            => 'Bamba Logistics & Transit',
                'secteur'        => 'Commerce International',
                'description'    => 'Agence de transit et de logistique internationale basée au port de San-Pédro. Spécialisée dans le dédouanement, le transport de marchandises et la gestion de fret pour les exportateurs de produits agricoles.',
                'localisation'   => 'San-Pédro',
                'site_web'       => null,
                'telephone'      => '+225 05 05 66 77 88',
                'email_contact'  => 'bamba.logistics@email.com',
                'annee_creation' => '2018',
                'statut'         => Entreprise::STATUT_ACTIF,
            ],
            [
                'porteur_index'  => 22, // Wilfried Dago - Informatique
                'nom'            => 'CyberShield Africa',
                'secteur'        => 'Informatique',
                'description'    => 'Cabinet de cybersécurité et d\'audit informatique pour les entreprises et institutions financières d\'Afrique de l\'Ouest. Services : tests d\'intrusion, audit ISO 27001, formation des équipes IT.',
                'localisation'   => 'Abidjan, Plateau',
                'site_web'       => 'https://cybershield-africa.example.com',
                'telephone'      => '+225 07 07 33 66 99',
                'email_contact'  => 'contact@cybershield-africa.example.com',
                'annee_creation' => '2016',
                'statut'         => Entreprise::STATUT_ACTIF,
            ],
            [
                'porteur_index'  => 23, // Estelle N'Dri - Restauration
                'nom'            => 'Makossa Kitchen',
                'secteur'        => 'Restauration',
                'description'    => 'Restaurant gastronomique ivoirien proposant une cuisine traditionnelle revisitée dans un cadre moderne. Carte composée à 100% de produits locaux sourcés directement auprès de producteurs partenaires.',
                'localisation'   => 'Abidjan, Zone 4',
                'site_web'       => 'https://makossa-kitchen.example.com',
                'telephone'      => '+225 01 01 44 77 00',
                'email_contact'  => 'reservation@makossa-kitchen.example.com',
                'annee_creation' => '2022',
                'statut'         => Entreprise::STATUT_ACTIF,
            ],
            [
                'porteur_index'  => 11, // Clarisse Ehui - Droit
                'nom'            => 'Cabinet Ehui & Associés',
                'secteur'        => 'Droit',
                'description'    => 'Cabinet d\'avocats spécialisé en droit des affaires OHADA, droit fiscal et arbitrage commercial international. Accompagnement des PME et grandes entreprises dans leurs opérations juridiques en Afrique francophone.',
                'localisation'   => 'Abidjan, Plateau',
                'site_web'       => null,
                'telephone'      => '+225 27 20 11 22 44',
                'email_contact'  => 'cabinet@ehui-associes.example.com',
                'annee_creation' => '2018',
                'statut'         => Entreprise::STATUT_ACTIF,
            ],
            [
                'porteur_index'  => 28, // Danielle Bah - Microfinance
                'nom'            => 'FinciCoop',
                'secteur'        => 'Microfinance',
                'description'    => 'Institution de microfinance proposant des produits d\'épargne et de crédit adaptés aux femmes entrepreneures et aux groupements d\'agriculteurs. Plus de 3 000 membres actifs dans les régions du Lôh-Djiboua et de la Nawa.',
                'localisation'   => 'Abidjan, Treichville',
                'site_web'       => null,
                'telephone'      => '+225 05 05 00 33 66',
                'email_contact'  => 'fincicoop@email.com',
                'annee_creation' => '2019',
                'statut'         => Entreprise::STATUT_ACTIF,
            ],
            [
                'porteur_index'  => 26, // Cécile Aboua - Beauté & Cosmétique
                'nom'            => 'Natura CI',
                'secteur'        => 'Beauté & Cosmétique',
                'description'    => 'Gamme de cosmétiques naturels et biologiques formulés à base de plantes médicinales africaines (karité, monoï, hibiscus). Produits disponibles en ligne et dans 45 points de vente à Abidjan.',
                'localisation'   => 'Abidjan, Riviera',
                'site_web'       => 'https://natura-ci.example.com',
                'telephone'      => '+225 01 01 88 11 44',
                'email_contact'  => 'bonjour@natura-ci.example.com',
                'annee_creation' => '2020',
                'statut'         => Entreprise::STATUT_ACTIF,
            ],
            // Entreprises en attente de validation
            [
                'porteur_index'  => 14, // Romuald Tape - Logistique
                'nom'            => 'AfriTransit Hub',
                'secteur'        => 'Logistique',
                'description'    => 'Plateforme digitale de mise en relation entre chargeurs et transporteurs pour optimiser les flux logistiques en Afrique de l\'Ouest. Solution SaaS avec suivi GPS en temps réel.',
                'localisation'   => 'Abidjan, Treichville',
                'site_web'       => 'https://afritransit.example.com',
                'telephone'      => '+225 07 07 55 77 99',
                'email_contact'  => 'hello@afritransit.example.com',
                'annee_creation' => '2023',
                'statut'         => Entreprise::STATUT_EN_ATTENTE,
            ],
            [
                'porteur_index'  => 25, // Ines Kouakou - Pharmaceutique
                'nom'            => 'PharmaCom CI',
                'secteur'        => 'Pharmaceutique',
                'description'    => 'Réseau de pharmacies communautaires spécialisées dans la distribution de médicaments essentiels à prix abordables dans les quartiers périurbains d\'Abidjan. 3 officines ouvertes, objectif 10 d\'ici 2026.',
                'localisation'   => 'Abidjan, Yopougon',
                'site_web'       => null,
                'telephone'      => '+225 05 05 66 99 22',
                'email_contact'  => 'pharmacom.ci@email.com',
                'annee_creation' => '2022',
                'statut'         => Entreprise::STATUT_EN_ATTENTE,
            ],
        ];

        $creees = 0;

        foreach ($entreprises as $data) {
            $porteur = $membres->values()->get($data['porteur_index']) ?? $membres->first();

            // Éviter les doublons si relancé
            if (Entreprise::where('nom', $data['nom'])->exists()) {
                continue;
            }

            Entreprise::create([
                'utilisateur_id'  => $porteur->id,
                'nom'             => $data['nom'],
                'secteur'         => $data['secteur'],
                'description'     => $data['description'],
                'localisation'    => $data['localisation'],
                'site_web'        => $data['site_web'],
                'telephone'       => $data['telephone'],
                'email_contact'   => $data['email_contact'],
                'annee_creation'  => $data['annee_creation'],
                'statut'          => $data['statut'],
                'valide_par'      => $data['statut'] === Entreprise::STATUT_ACTIF ? $admin?->id : null,
                'date_validation' => $data['statut'] === Entreprise::STATUT_ACTIF ? now()->subDays(rand(5, 30)) : null,
                'created_at'      => now()->subDays(rand(10, 60)),
            ]);

            $creees++;
        }

        $this->command->info("{$creees} entreprises créées avec succès.");
    }
}
