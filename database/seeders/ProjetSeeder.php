<?php

namespace Database\Seeders;

use App\Models\Contribution;
use App\Models\Projet;
use App\Models\User;
use Illuminate\Database\Seeder;
use Illuminate\Support\Str;

class ProjetSeeder extends Seeder
{
    public function run(): void
    {
        $membres = User::role('membre')->where('statut', User::STATUT_ACTIF)->get();

        if ($membres->isEmpty()) {
            $this->command->warn('Aucun membre actif trouvé. Lancez d\'abord MembreSeeder.');
            return;
        }

        $admin = User::role(['admin', 'super_admin'])->first();

        $projets = [
            [
                'titre'              => 'Plateforme de mise en relation agriculteurs-acheteurs',
                'description_courte' => 'Une application mobile pour connecter les producteurs locaux aux acheteurs professionnels en Côte d\'Ivoire.',
                'description'        => "Le secteur agricole ivoirien souffre d'un manque de transparence dans la chaîne de commercialisation. Les producteurs vendent souvent en dessous du prix du marché faute de visibilité sur la demande réelle.\n\nNotre projet consiste à développer une application mobile (Android & iOS) permettant aux agriculteurs de publier leurs stocks disponibles, leurs prix et leurs localisations. Les acheteurs (grossistes, supermarchés, restaurants) peuvent ainsi les contacter directement.\n\nL'application intégrera un système de notation pour renforcer la confiance, et un module de gestion des paiements mobiles.\n\nImpact attendu : 500 agriculteurs connectés en phase 1, réduction de 20% des intermédiaires, augmentation du revenu moyen des producteurs de 30%.",
                'type_financement'   => 'fixe',
                'montant_cible'      => 8500000,
                'statut'             => Projet::STATUT_EN_FINANCEMENT,
                'date_fin_financement' => now()->addDays(45),
                'date_debut'         => now()->addDays(60),
                'porteur_index'      => 4, // Sébastien N'Guessan - Technologie
            ],
            [
                'titre'              => 'École de code pour les jeunes de Yopougon',
                'description_courte' => 'Former 200 jeunes déscolarisés de Yopougon aux métiers du numérique en 6 mois.',
                'description'        => "Yopougon, avec ses plus de 2 millions d'habitants, est l'une des communes les plus peuplées d'Abidjan. Le taux de chômage des jeunes y dépasse 40%.\n\nNous proposons de créer un centre de formation au code informatique (développement web, mobile, et data science) destiné aux jeunes de 18 à 30 ans n'ayant pas pu accéder à l'enseignement supérieur.\n\nLe programme de 6 mois alternera théorie (40%) et projets pratiques (60%). À l'issue, chaque apprenant sera accompagné dans sa recherche d'emploi ou dans la création de son projet digital.\n\nBudget : équipement informatique (40%), formateurs (35%), locaux et fonctionnement (25%).",
                'type_financement'   => 'fixe',
                'montant_cible'      => 12000000,
                'statut'             => Projet::STATUT_EN_FINANCEMENT,
                'date_fin_financement' => now()->addDays(60),
                'date_debut'         => now()->addDays(90),
                'porteur_index'      => 7, // Awa Koné - Éducation
            ],
            [
                'titre'              => 'Unité de transformation de cajou à Korhogo',
                'description_courte' => 'Construire une unité de décorticage et de valorisation des noix de cajou pour 150 producteurs du nord.',
                'description'        => "La Côte d'Ivoire est le premier producteur mondial de noix de cajou brute mais exporte 90% de sa production sans transformation, perdant ainsi une valeur ajoutée considérable.\n\nNous souhaitons construire une unité de transformation semi-industrielle à Korhogo capable de traiter 500 tonnes de cajou par an. L'unité produira de l'amande de cajou grillée, de l'huile de cajou CNSL et des produits dérivés.\n\n150 agriculteurs membres de notre coopérative bénéficieront de débouchés sécurisés et d'un prix d'achat garanti 15% au-dessus du marché.\n\nLe projet créera 25 emplois permanents et 80 emplois saisonniers.",
                'type_financement'   => 'fixe',
                'montant_cible'      => 35000000,
                'statut'             => Projet::STATUT_EN_FINANCEMENT,
                'date_fin_financement' => now()->addDays(90),
                'date_debut'         => now()->addMonths(4),
                'porteur_index'      => 9, // Sylvie Ahoussou - Agroalimentaire
            ],
            [
                'titre'              => 'Centre de santé mobile pour les villages de l\'intérieur',
                'description_courte' => 'Acquérir un camion médicalisé pour offrir des consultations gratuites dans 20 villages sans centre de santé.',
                'description'        => "Dans de nombreux villages de l'intérieur de la Côte d'Ivoire, le centre de santé le plus proche est à plus de 50 km. Cela provoque des retards de diagnostic et des décès évitables, notamment pour les femmes enceintes et les enfants.\n\nNous allons acquérir et équiper un camion médicalisé avec un médecin, une sage-femme et un infirmier. Le camion desservira un circuit de 20 villages dans les régions du Bélier et du Moronou, à raison de 2 villages par semaine.\n\nServices proposés : consultations générales, suivi prénatal et postnatal, vaccinations, dépistage du paludisme et de la tuberculose, sensibilisation à la nutrition.\n\nObjectif : 5000 consultations gratuites la première année.",
                'type_financement'   => 'fixe',
                'montant_cible'      => 22000000,
                'statut'             => Projet::STATUT_EN_FINANCEMENT,
                'date_fin_financement' => now()->addDays(75),
                'date_debut'         => now()->addMonths(3),
                'porteur_index'      => 3, // Fatoumata Diallo - Santé
            ],
            [
                'titre'              => 'Kit solaire domestique pour 1000 ménages ruraux',
                'description_courte' => 'Distribuer des kits solaires avec financement participatif pour électrifier des ménages ruraux hors réseau.',
                'description'        => "Près de 40% des ménages ivoiriens n'ont pas accès à l'électricité, principalement en zone rurale. L'absence d'éclairage freine la scolarisation des enfants et l'activité économique nocturne.\n\nNous proposons de distribuer 1000 kits solaires domestiques (panneau 50W, batterie, 3 ampoules LED, chargeur USB) à prix subventionné dans les régions du Hambol et de la Vallée du Bandama.\n\nLe modèle économique repose sur une contribution mensuelle de 5000 FCFA pendant 24 mois, permettant de rembourser le kit. Passé ce délai, l'énergie est gratuite.\n\nCe financement couvre l'achat des 1000 premiers kits et les frais d'installation.",
                'type_financement'   => 'fixe',
                'montant_cible'      => 18000000,
                'statut'             => Projet::STATUT_EN_FINANCEMENT,
                'date_fin_financement' => now()->addDays(50),
                'date_debut'         => now()->addMonths(2),
                'porteur_index'      => 16, // Landry Gnagne - Énergie
            ],
            [
                'titre'              => 'Festival de la gastronomie ivoirienne — édition 2026',
                'description_courte' => 'Organiser le 1er festival national de la gastronomie ivoirienne à Abidjan, avec 30 restaurateurs et 5000 visiteurs attendus.',
                'description'        => "La cuisine ivoirienne est riche et variée — attiéké, foutou, kedjenou, aloco — mais reste peu valorisée sur la scène internationale et même localement.\n\nNous souhaitons organiser le premier Festival National de la Gastronomie Ivoirienne en octobre 2026 à Abidjan (Palais de la Culture). L'événement réunira 30 restaurateurs et chefs, des démonstrations culinaires en direct, des conférences sur l'alimentation durable et un concours du meilleur plat ivoirien.\n\n5000 visiteurs sont attendus sur 3 jours. L'événement sera diffusé en direct sur les réseaux sociaux.\n\nLe financement couvre : location du site, communication, logistique, prix et trophées.",
                'type_financement'   => 'fixe',
                'montant_cible'      => 6500000,
                'statut'             => Projet::STATUT_EN_FINANCEMENT,
                'date_fin_financement' => now()->addDays(40),
                'date_debut'         => now()->addMonths(4),
                'porteur_index'      => 23, // Estelle N'Dri - Restauration
            ],
            [
                'titre'              => 'Incubateur textile Alumni CI',
                'description_courte' => 'Créer un atelier partagé pour 15 créateurs de mode ivoiriens avec machines et formation au business.',
                'description'        => "La mode africaine est en plein essor mondial, mais les créateurs ivoiriens manquent d'infrastructure professionnelle pour passer de l'artisanat à l'industrie.\n\nNous allons créer un atelier partagé de 300m² équipé de machines industrielles (surjeteuses, coupeuses, tables de coupe) accessible à 15 créateurs membres. L'atelier sera complété par un espace showroom et un programme de formation en gestion de marque et en e-commerce.\n\nChaque créateur paiera un loyer mensuel symbolique de 50 000 FCFA pour accéder aux machines. Ce modèle permet à l'incubateur d'être auto-financé à partir de la 2e année.\n\nObjectif : aider 15 créateurs à tripler leur production et à exporter vers la diaspora africaine.",
                'type_financement'   => 'fixe',
                'montant_cible'      => 9000000,
                'statut'             => Projet::STATUT_EN_FINANCEMENT,
                'date_fin_financement' => now()->addDays(55),
                'date_debut'         => now()->addMonths(3),
                'porteur_index'      => 17, // Christelle Atchori - Mode
            ],
            [
                'titre'              => 'Bibliothèque numérique scolaire — 50 tablettes pour les enfants',
                'description_courte' => 'Doter 5 écoles primaires rurales de 10 tablettes chacune avec contenu éducatif hors ligne.',
                'description'        => "Dans les zones rurales, les enfants n'ont souvent accès ni à Internet ni à des bibliothèques physiques. Cela crée un fossé numérique qui hypothèque leur avenir scolaire.\n\nNous allons distribuer 50 tablettes préchargées avec des contenus éducatifs hors ligne (cours, exercices, livres, vidéos pédagogiques) dans 5 écoles primaires de la région du Gôh.\n\nChaque tablette sera protégée par une coque résistante et équipée d'une batterie longue durée rechargeable par panneau solaire. Des enseignants formateurs seront formés à l'utilisation.\n\nUn suivi trimestriel permettra de mesurer l'impact sur les résultats scolaires.",
                'type_financement'   => 'ouvert',
                'montant_cible'      => null,
                'statut'             => Projet::STATUT_EN_FINANCEMENT,
                'date_fin_financement' => now()->addDays(65),
                'date_debut'         => now()->addMonths(2),
                'porteur_index'      => 7, // Awa Koné - Éducation
            ],
            [
                'titre'              => 'Réseau d\'épargne numérique pour femmes entrepreneures',
                'description_courte' => 'Développer une application de tontine numérique sécurisée pour les groupements de femmes rurales.',
                'description'        => "La tontine est un mécanisme d'épargne communautaire très répandu en Côte d'Ivoire, mais elle souffre de problèmes de transparence et de sécurité (détournements, oublis, conflits).\n\nNous allons développer une application mobile de tontine numérique permettant à des groupes de femmes de gérer leur épargne collective de manière transparente et sécurisée. L'application notifiera chaque membre lors d'un dépôt, affichera le solde en temps réel et automatisera les tirages au sort.\n\nCible initiale : 50 groupements de femmes (environ 1000 femmes) dans les régions du Lôh-Djiboua et de la Nawa.\n\nLe modèle économique est basé sur une commission de 1% sur chaque cycle de tontine.",
                'type_financement'   => 'fixe',
                'montant_cible'      => 5500000,
                'statut'             => Projet::STATUT_EN_FINANCEMENT,
                'date_fin_financement' => now()->addDays(35),
                'date_debut'         => now()->addMonths(2),
                'porteur_index'      => 29, // Danielle Bah - Microfinance
            ],
            [
                'titre'              => 'Serre hydroponique à Abidjan — légumes frais toute l\'année',
                'description_courte' => 'Construire une serre hydroponique urbaine de 500m² pour produire des légumes frais sans pesticides à Abidjan.',
                'description'        => "La dépendance aux importations de légumes (tomates, laitue, poivrons) coûte des milliards à la Côte d'Ivoire chaque année. L'agriculture urbaine est une solution pour réduire cette dépendance et créer des emplois.\n\nNous allons construire une serre hydroponique de 500m² dans le quartier de Port-Bouët à Abidjan. La culture hors-sol permet de produire 3 à 4 fois plus par m² qu'en plein champ, sans pesticides et avec 90% moins d'eau.\n\nProduction cible : 8 tonnes de légumes par an vendus aux restaurants, hôtels et épiceries premium d'Abidjan.\n\nLe financement couvre : structure métallique, films plastiques, système d'irrigation, substrat, semences et formation.",
                'type_financement'   => 'fixe',
                'montant_cible'      => 14000000,
                'statut'             => Projet::STATUT_EN_ATTENTE,
                'date_fin_financement' => now()->addDays(120),
                'date_debut'         => now()->addMonths(5),
                'porteur_index'      => 2, // Yves Traoré - Agriculture
            ],
            [
                'titre'              => 'Podcast Alumni FAACI — Voix d\'entrepreneurs africains',
                'description_courte' => 'Lancer une émission podcast mensuelle donnant la parole aux entrepreneurs Alumni de AIESEC Côte d\'Ivoire.',
                'description'        => "Les histoires de succès des Alumni AIESEC CI sont peu connues du grand public et de la jeunesse ivoirienne. Un podcast permettrait de les valoriser et d'inspirer la prochaine génération.\n\nNous allons créer un podcast mensuel de 45 à 60 minutes, disponible sur Spotify, Apple Podcasts, et YouTube. Chaque épisode mettra en valeur le parcours d'un Alumni, ses défis, ses solutions et ses conseils.\n\nUn studio d'enregistrement professionnel sera équipé pour la production. Les 12 premiers épisodes sont déjà planifiés avec des invités confirmés.\n\nLe financement couvre : équipement audio, abonnements logiciels, distribution, identité graphique et promotion sur 1 an.",
                'type_financement'   => 'ouvert',
                'montant_cible'      => null,
                'statut'             => Projet::STATUT_EN_ATTENTE,
                'date_fin_financement' => now()->addDays(30),
                'date_debut'         => now()->addMonths(2),
                'porteur_index'      => 12, // Théodore Gbagbo - Médias
            ],
            [
                'titre'              => 'Résidence artistique des Alumni — Création & Innovation',
                'description_courte' => 'Organiser une semaine de résidence artistique pluridisciplinaire pour les créatifs du réseau FAACI.',
                'description'        => "Le réseau FAACI compte de nombreux créatifs (designers, musiciens, photographes, architectes) qui n'ont pas toujours l'occasion de collaborer entre eux.\n\nNous proposons d'organiser une résidence artistique d'une semaine réunissant 20 créatifs Alumni dans un cadre propice à la création (centre de retraite en dehors d'Abidjan). Chaque participant travaillera sur un projet collaboratif aboutissant à une exposition publique.\n\nLa résidence favorisera les collaborations inter-sectorielles et permettra des créations inédites à l'intersection de l'art, du design et de l'entrepreneuriat.\n\nUn livret documentant les œuvres sera distribué à 500 exemplaires.",
                'type_financement'   => 'fixe',
                'montant_cible'      => 3500000,
                'statut'             => Projet::STATUT_BROUILLON,
                'date_fin_financement' => null,
                'date_debut'         => null,
                'porteur_index'      => 17, // Christelle Atchori
            ],
        ];

        foreach ($projets as $data) {
            $porteurIndex = $data['porteur_index'];
            $porteur      = $membres->values()->get($porteurIndex) ?? $membres->first();

            $slug = Str::slug($data['titre']);
            $i    = 1;
            $base = $slug;
            while (Projet::where('slug', $slug)->exists()) {
                $slug = $base.'-'.$i++;
            }

            Projet::create([
                'utilisateur_id'       => $porteur->id,
                'titre'                => $data['titre'],
                'slug'                 => $slug,
                'description'          => $data['description'],
                'description_courte'   => $data['description_courte'],
                'type_financement'     => $data['type_financement'],
                'montant_cible'        => $data['montant_cible'],
                'montant_collecte'     => 0,
                'statut'               => $data['statut'],
                'valide_par'           => in_array($data['statut'], [Projet::STATUT_EN_FINANCEMENT, Projet::STATUT_EN_ATTENTE]) && $admin ? $admin->id : null,
                'date_validation'      => in_array($data['statut'], [Projet::STATUT_EN_FINANCEMENT]) ? now()->subDays(rand(3, 20)) : null,
                'date_fin_financement' => $data['date_fin_financement'],
                'date_debut'           => $data['date_debut'],
                'created_at'           => now()->subDays(rand(5, 40)),
            ]);
        }

        $this->command->info('12 projets créés avec succès.');

        // Contributions sur les projets en financement
        $this->seederContributions($membres);
    }

    private function seederContributions($membres): void
    {
        $projetsEnFinancement = Projet::where('statut', Projet::STATUT_EN_FINANCEMENT)->get();

        if ($projetsEnFinancement->isEmpty()) {
            return;
        }

        $moyens = ['cash', 'orange_money', 'wave', 'virement', 'autre'];

        $totalContribs = 0;

        foreach ($projetsEnFinancement as $projet) {
            // Entre 3 et 8 contributeurs par projet
            $nbContribs      = rand(3, 8);
            $contributeurs   = $membres->where('id', '!=', $projet->utilisateur_id)
                                       ->random(min($nbContribs, $membres->count() - 1));
            $montantCollecte = 0;

            foreach ($contributeurs as $contributeur) {
                // Montant promis : entre 50 000 et 500 000 FCFA
                $montantPromis = rand(1, 10) * 50000;

                // Statut aléatoire pondéré
                $rand   = rand(1, 10);
                $statut = match (true) {
                    $rand <= 3  => Contribution::STATUT_PAID,
                    $rand <= 6  => Contribution::STATUT_CONFIRMED,
                    $rand <= 8  => Contribution::STATUT_PENDING,
                    default     => Contribution::STATUT_PARTIAL,
                };

                $montantPaye = match ($statut) {
                    Contribution::STATUT_PAID     => $montantPromis,
                    Contribution::STATUT_PARTIAL  => (int) ($montantPromis * 0.5),
                    default                       => 0,
                };

                if ($statut === Contribution::STATUT_PAID || $statut === Contribution::STATUT_PARTIAL) {
                    $montantCollecte += $montantPaye;
                }

                Contribution::create([
                    'projet_id'                 => $projet->id,
                    'utilisateur_id'            => $contributeur->id,
                    'montant_promis'            => $montantPromis,
                    'montant_paye'              => $montantPaye,
                    'statut'                    => $statut,
                    'moyen_paiement'            => in_array($statut, [Contribution::STATUT_PAID, Contribution::STATUT_CONFIRMED, Contribution::STATUT_PARTIAL]) ? $moyens[array_rand($moyens)] : null,
                    'date_declaration_paiement' => in_array($statut, [Contribution::STATUT_PAID, Contribution::STATUT_CONFIRMED, Contribution::STATUT_PARTIAL]) ? now()->subDays(rand(1, 10)) : null,
                    'valide_par'                => $statut === Contribution::STATUT_PAID ? User::role(['admin', 'super_admin'])->first()?->id : null,
                    'date_validation'           => $statut === Contribution::STATUT_PAID ? now()->subDays(rand(1, 5)) : null,
                    'created_at'               => now()->subDays(rand(1, 15)),
                ]);

                $totalContribs++;
            }

            // Mettre à jour montant_collecte du projet
            $projet->update(['montant_collecte' => $montantCollecte]);
        }

        $this->command->info("{$totalContribs} contributions créées avec succès.");
    }
}
