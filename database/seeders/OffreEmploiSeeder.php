<?php

namespace Database\Seeders;

use App\Models\OffreEmploi;
use App\Models\User;
use Illuminate\Database\Seeder;
use Illuminate\Support\Str;

class OffreEmploiSeeder extends Seeder
{
    public function run(): void
    {
        OffreEmploi::query()->delete();

        $auteur = User::where('statut', User::STATUT_ACTIF)->inRandomOrder()->first();
        $valideur = User::whereHas('roles', fn ($q) => $q->whereIn('name', ['admin', 'super_admin']))->first();

        if (! $auteur || ! $valideur) {
            $this->command->warn('Aucun utilisateur actif ou admin trouvé. Les offres d\'emploi ne seront pas créées.');

            return;
        }

        $offres = [
            [
                'titre'                => 'Directeur(trice) Marketing Digital',
                'description'          => '<p>Une startup Fintech ivoirienne en forte croissance, fondée par des Alumni AIESEC, recherche son <strong>Directeur Marketing Digital</strong> pour piloter sa stratégie d\'acquisition et de rétention client sur le marché ivoirien et sous-régional.</p><h3>Missions</h3><ul><li>Définir et exécuter la stratégie marketing digital (SEO, SEA, Social Media, CRM)</li><li>Gérer et animer une équipe de 4 personnes (social media manager, growth hacker, designer, content manager)</li><li>Analyser les KPIs et optimiser les campagnes en temps réel</li><li>Développer les partenariats media et influenceurs</li><li>Piloter le budget marketing annuel</li></ul><h3>Profil recherché</h3><ul><li>Bac+5 en Marketing, Commerce ou Communication</li><li>5 ans minimum d\'expérience en marketing digital (dont 2 en management)</li><li>Maîtrise des outils : Google Analytics, Meta Ads, HubSpot</li><li>Excellente maîtrise du français, l\'anglais est un plus</li><li>Connaissance du marché africain fortement souhaitée</li></ul>',
                'type_contrat'         => 'cdi',
                'localisation'         => 'Abidjan, Plateau',
                'salaire'              => '800 000 – 1 200 000 FCFA brut/mois',
                'competences_requises' => 'Marketing Digital, Google Analytics, Meta Ads, Management d\'équipe, SEO, CRM',
                'date_expiration'      => now()->addDays(45),
                'statut'               => OffreEmploi::STATUT_ACTIVE,
            ],
            [
                'titre'                => 'Développeur Full-Stack Laravel / Vue.js',
                'description'          => '<p>Une agence digitale fondée par des Alumni FAACI recherche un <strong>Développeur Full-Stack</strong> expérimenté pour rejoindre une équipe technique soudée et travailler sur des projets ambitieux pour des clients grands comptes en Afrique de l\'Ouest.</p><h3>Missions</h3><ul><li>Développer et maintenir des applications web (back-end Laravel, front-end Vue.js)</li><li>Participer à l\'architecture technique des projets</li><li>Code review et mentoring des développeurs juniors</li><li>Intégration d\'APIs tierces (Orange Money, Wave, MTN MoMo)</li><li>Rédaction de la documentation technique</li></ul><h3>Profil recherché</h3><ul><li>Bac+3/5 en Informatique ou équivalent</li><li>3+ ans d\'expérience en Laravel et Vue.js / React</li><li>Maîtrise de MySQL, Redis, Docker</li><li>Connaissance de Git et des méthodologies agiles</li><li>Rigueur, autonomie et esprit d\'équipe</li></ul>',
                'type_contrat'         => 'cdi',
                'localisation'         => 'Abidjan, Cocody (télétravail partiel possible)',
                'salaire'              => '600 000 – 900 000 FCFA brut/mois',
                'competences_requises' => 'Laravel, Vue.js, MySQL, Docker, API REST, Git',
                'date_expiration'      => now()->addDays(30),
                'statut'               => OffreEmploi::STATUT_ACTIVE,
            ],
            [
                'titre'                => 'Stage en Finance d\'Entreprise et Analyse d\'Investissement',
                'description'          => '<p>Un fonds d\'investissement spécialisé dans les PME africaines, cofondé par des Alumni FAACI, propose un <strong>stage de 6 mois</strong> en finance d\'entreprise et analyse d\'investissement.</p><h3>Missions</h3><ul><li>Participer à l\'instruction de dossiers d\'investissement (due diligence, business plans)</li><li>Construire des modèles financiers (DCF, comparables, LBO)</li><li>Rédiger des notes d\'investissement et des mémos de synthèse</li><li>Assurer le suivi du portefeuille de participations</li><li>Participer aux réunions avec les entrepreneurs en portefeuille</li></ul><h3>Profil recherché</h3><ul><li>Étudiant(e) Bac+4/5 en finance, gestion ou économie (INSCAE, INPHB, grandes écoles de commerce)</li><li>Maîtrise d\'Excel avancé (modélisation financière)</li><li>Intérêt marqué pour l\'entrepreneuriat et l\'investissement en Afrique</li><li>Rigueur analytique et excellentes capacités rédactionnelles</li></ul>',
                'type_contrat'         => 'stage',
                'localisation'         => 'Abidjan, Plateau',
                'salaire'              => '150 000 FCFA/mois + tickets restaurant',
                'competences_requises' => 'Modélisation financière, Excel, Analyse crédit, PowerPoint, Finance d\'entreprise',
                'date_expiration'      => now()->addDays(20),
                'statut'               => OffreEmploi::STATUT_ACTIVE,
            ],
            [
                'titre'                => 'Consultante / Consultant RH Senior — Mission 3 mois',
                'description'          => '<p>Un groupe industriel ivoirien en phase de transformation organisationnelle recherche un(e) <strong>Consultant(e) RH Senior en mission freelance</strong> pour accompagner sa direction RH pendant 3 mois.</p><h3>Mission principale</h3><p>Refonte du système d\'évaluation des performances et de la politique de rémunération variable pour une organisation de 500 personnes réparties sur 3 sites.</p><h3>Livrables attendus</h3><ul><li>Audit RH du système existant</li><li>Nouveau référentiel de compétences par famille de métiers</li><li>Système d\'évaluation 360° et guide du manager</li><li>Grille de rémunération variable par niveau</li><li>Plan de communication interne et formation des managers</li></ul><h3>Profil</h3><ul><li>7+ ans d\'expérience RH dont 3 en cabinet de conseil</li><li>Expertise en ingénierie de la rémunération et évaluation des performances</li><li>Disponibilité immédiate</li></ul>',
                'type_contrat'         => 'freelance',
                'localisation'         => 'Abidjan (2-3 jours/semaine sur site)',
                'salaire'              => 'Sur devis (budget indicatif : 2,5 – 4M FCFA/mois)',
                'competences_requises' => 'Conseil RH, Ingénierie de la rémunération, Évaluation des performances, Management',
                'date_expiration'      => now()->addDays(15),
                'statut'               => OffreEmploi::STATUT_ACTIVE,
            ],
            [
                'titre'                => 'Ingénieur(e) Agronome — Développement de projets cacao',
                'description'          => '<p>Une coopérative agricole Alumni FAACI spécialisée dans la filière cacao recrute un(e) <strong>Ingénieur(e) Agronome</strong> pour renforcer son équipe technique et développer ses projets de certification internationale.</p><h3>Missions</h3><ul><li>Accompagner les planteurs partenaires (500 familles) en bonnes pratiques agricoles</li><li>Piloter le processus de certification UTZ / Rainforest Alliance</li><li>Développer des indicateurs de suivi de la productivité et de la qualité</li><li>Collaborer avec les ONG et organismes de développement (USAID, AFD)</li><li>Rédiger les rapports techniques et les dossiers de financement</li></ul><h3>Profil</h3><ul><li>Bac+5 en agronomie tropicale (ENSA, INP-HB ou équivalent)</li><li>2+ ans d\'expérience en filières agricoles tropicales</li><li>Connaissance de la certification durable (UTZ, Rainforest, bio)</li><li>Permis B et mobilité terrain (déplacements fréquents hors Abidjan)</li></ul>',
                'type_contrat'         => 'cdd',
                'localisation'         => 'San-Pédro / Soubré (logement fourni)',
                'salaire'              => '450 000 – 600 000 FCFA brut/mois + avantages',
                'competences_requises' => 'Agronomie tropicale, Certification UTZ, Gestion de projets agricoles, Rapport de terrain',
                'date_expiration'      => now()->addDays(40),
                'statut'               => OffreEmploi::STATUT_ACTIVE,
            ],
            [
                'titre'                => 'Chargé(e) de Communication et Relations Presse',
                'description'          => '<p>Une entreprise de beauté et cosmétiques naturels à base de plantes africaines, fondée par une Alumni FAACI et présente dans 4 pays, recherche son/sa <strong>Chargé(e) de Communication et Relations Presse</strong>.</p><h3>Missions</h3><ul><li>Gérer la présence sur les réseaux sociaux (Instagram, TikTok, LinkedIn, Facebook)</li><li>Produire du contenu créatif (photos, vidéos, stories, reels)</li><li>Entretenir les relations avec les journalistes et influenceurs beauté</li><li>Coordonner la communication autour des lancements produits</li><li>Rédiger les communiqués de presse et dossiers médias</li><li>Organiser et animer les événements de la marque (pop-ups, séances de démo)</li></ul><h3>Profil</h3><ul><li>Bac+3/4 en Communication, Journalisme ou Marketing</li><li>2+ ans d\'expérience en communication de marque ou RP</li><li>Sens esthétique développé et passion pour la beauté/cosmétique</li><li>Maîtrise des outils : Canva, CapCut, Meta Business Suite</li></ul>',
                'type_contrat'         => 'cdi',
                'localisation'         => 'Abidjan, Cocody',
                'salaire'              => '350 000 – 500 000 FCFA brut/mois',
                'competences_requises' => 'Community management, Relations presse, Création de contenu, Canva, Instagram, TikTok',
                'date_expiration'      => now()->addDays(25),
                'statut'               => OffreEmploi::STATUT_ACTIVE,
            ],
        ];

        foreach ($offres as $data) {
            $data['utilisateur_id'] = $auteur->id;
            $data['valide_par']     = $valideur->id;
            $data['date_validation'] = now()->subDays(rand(1, 10));

            $base = Str::slug($data['titre']);
            $slug = $base;
            $i    = 2;
            while (OffreEmploi::where('slug', $slug)->exists()) {
                $slug = $base . '-' . $i++;
            }
            $data['slug'] = $slug;

            OffreEmploi::create($data);
        }

        $this->command->info('6 offres d\'emploi créées avec succès.');
    }
}
