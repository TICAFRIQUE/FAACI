<?php

namespace Database\Seeders;

use App\Models\Article;
use App\Support\Slug;
use Illuminate\Database\Seeder;

class ArticleSeeder extends Seeder
{
    public function run(): void
    {
        Article::query()->delete();

        $articles = [
            [
                'titre'            => 'La FAACI lance sa plateforme numérique dédiée aux Alumni',
                'categorie'        => 'Communiqué',
                'extrait'          => 'La Fondation AIESEC Alumni Côte d\'Ivoire franchit une étape majeure avec le lancement officiel de sa plateforme numérique, un hub exclusif pour les membres du réseau.',
                'contenu'          => '<p>La <strong>Fondation AIESEC Alumni Côte d\'Ivoire (FAACI)</strong> est fière d\'annoncer le lancement officiel de sa plateforme numérique, un espace 100 % dédié aux anciens membres d\'AIESEC en Côte d\'Ivoire.</p><p>Cette plateforme constitue un véritable hub de services exclusifs : annuaire des membres, base des entreprises Alumni, module de financement communautaire, opportunités d\'affaires, offres d\'emploi et calendrier des événements du réseau.</p><h3>Un projet né des besoins du réseau</h3><p>Depuis la création de la FAACI en 2015, le réseau comptait sur des outils informels (groupes WhatsApp, emails) pour maintenir ses liens. La nouvelle plateforme répond à un besoin exprimé par les membres lors de l\'Assemblée Générale 2024 : disposer d\'un espace structuré, sécurisé et professionnel.</p><p>« Cette plateforme est l\'expression concrète de notre engagement à construire un réseau Alumni vivant, utile et tourné vers l\'impact économique », déclare Madeleine Kouassi-Adjoumani, Présidente de la FAACI.</p><h3>Accès réservé aux membres actifs</h3><p>Pour garantir la qualité et la confidentialité des échanges, l\'accès à la plateforme est réservé aux membres actifs de la FAACI. Tout Alumni AIESEC CI peut soumettre une demande d\'adhésion depuis la page publique du site.</p>',
                'statut'           => Article::STATUT_PUBLIE,
                'date_publication' => now()->subDays(5),
                'image_url'        => 'https://picsum.photos/seed/article-plateforme/800/500',
            ],
            [
                'titre'            => 'Alumni Night 2025 : une soirée de networking inoubliable',
                'categorie'        => 'Événement',
                'extrait'          => 'Plus de 120 Alumni réunis au Sofitel Abidjan pour la grande soirée annuelle du réseau FAACI. Retour sur une nuit riche en rencontres et en opportunités.',
                'contenu'          => '<p>Le vendredi 14 mars 2025, le rooftop du Sofitel Abidjan Hôtel Ivoire a accueilli la <strong>3e édition de l\'Alumni Night</strong>, la soirée annuelle phare de la FAACI.</p><p>Plus de 120 Alumni issus de toutes les promotions et de tous les secteurs d\'activité se sont retrouvés pour une soirée mêlant networking informel, pitchs de projets et célébration des réussites du réseau.</p><h3>Les temps forts de la soirée</h3><ul><li><strong>Pitch Slam</strong> : 5 membres ont présenté leurs projets d\'entreprise devant leurs pairs en 5 minutes chacun</li><li><strong>Prix Alumni de l\'Année</strong> : décerné à Sébastien N\'Guessan pour le lancement de sa startup fintech</li><li><strong>Annonce officielle</strong> de la plateforme numérique FAACI</li></ul><h3>Témoignages</h3><p>« C\'est ici que j\'ai rencontré mon associé il y a deux ans. Ces soirées changent des vies », confie Aminata Coulibaly, membre depuis 2019.</p><p>La prochaine Alumni Night est prévue pour mars 2026. Les inscriptions ouvriront en janvier sur la plateforme.</p>',
                'statut'           => Article::STATUT_PUBLIE,
                'date_publication' => now()->subDays(30),
                'image_url'        => 'https://picsum.photos/seed/article-alumni-night/800/500',
            ],
            [
                'titre'            => 'Trois Alumni FAACI figurent au Forbes 30 Under 30 Africa 2025',
                'categorie'        => 'Prix & Distinctions',
                'extrait'          => 'Une fierté pour toute la communauté : Sébastien N\'Guessan, Christelle Atchori et Landry Gnagne sont classés parmi les 30 jeunes leaders africains les plus influents selon Forbes Africa.',
                'contenu'          => '<p>La FAACI célèbre une distinction exceptionnelle : <strong>trois de ses membres</strong> figurent dans la liste <em>Forbes 30 Under 30 Africa 2025</em>, publiée en février.</p><h3>Les lauréats</h3><p><strong>Sébastien N\'Guessan</strong> (Tech & Fintech) — Fondateur de PayEasy CI, une solution de paiement mobile qui compte déjà 200 000 utilisateurs actifs en Côte d\'Ivoire. Ancien membre AIESEC Abidjan (promotion 2016).</p><p><strong>Christelle Atchori</strong> (Fashion & Design) — Fondatrice de la marque AKWABA, présente dans 8 pays africains et sélectionnée pour la Fashion Week de Lagos. Promotion AIESEC 2021.</p><p><strong>Landry Gnagne</strong> (Energy & Environment) — CEO de SolarMi, startup d\'énergie solaire hors-réseau qui dessert 15 000 foyers ruraux. Promotion AIESEC 2018.</p><h3>La réaction de la FAACI</h3><p>« Ces distinctions illustrent parfaitement la qualité et l\'ambition de nos membres. La FAACI est fière d\'accompagner ces entrepreneurs dans leurs parcours », a déclaré Madeleine Kouassi-Adjoumani, Présidente de la FAACI.</p>',
                'statut'           => Article::STATUT_PUBLIE,
                'date_publication' => now()->subDays(60),
                'image_url'        => 'https://picsum.photos/seed/article-forbes/800/500',
            ],
            [
                'titre'            => 'Webinaire : Financement et investissement pour les entreprises africaines',
                'categorie'        => 'Formation',
                'extrait'          => 'La FAACI organise un webinaire exclusif sur les mécanismes de financement disponibles pour les entrepreneurs Alumni : venture capital, dette privée, subventions et financement communautaire.',
                'contenu'          => '<p>Dans le cadre de sa mission de renforcement des capacités des membres, la FAACI organise le <strong>15 avril 2025</strong> un webinaire premium sur le financement des entreprises africaines.</p><h3>Programme</h3><ul><li><strong>09h00 – 09h30</strong> : Introduction — l\'écosystème du financement en Afrique de l\'Ouest en 2025</li><li><strong>09h30 – 10h15</strong> : Le venture capital : comment pitcher et lever des fonds (intervenant : Ibrahim Ouédraogo, investisseur FAACI)</li><li><strong>10h15 – 11h00</strong> : La dette privée et les banques de développement : opportunités méconnues</li><li><strong>11h00 – 11h45</strong> : Le financement communautaire FAACI : mode d\'emploi et cas pratiques</li><li><strong>11h45 – 12h00</strong> : Questions / Réponses</li></ul><h3>Intervenants</h3><p>Le webinaire réunit trois Alumni experts : un directeur de fonds d\'investissement, un conseiller en financement de la BEI et la Trésorière de la FAACI.</p><p>Inscription obligatoire via la plateforme. Réservé aux membres actifs.</p>',
                'statut'           => Article::STATUT_PUBLIE,
                'date_publication' => now()->subDays(15),
                'image_url'        => 'https://picsum.photos/seed/article-webinaire/800/500',
            ],
            [
                'titre'            => 'Partenariat stratégique entre la FAACI et Orange Côte d\'Ivoire',
                'categorie'        => 'Partenariat',
                'extrait'          => 'La FAACI et Orange Côte d\'Ivoire signent un accord de partenariat pour soutenir les startups et PME fondées par des Alumni du réseau à travers des solutions digitales et un accompagnement dédié.',
                'contenu'          => '<p>La <strong>FAACI</strong> et <strong>Orange Côte d\'Ivoire</strong> ont signé le 20 janvier 2025 un accord de partenariat stratégique visant à soutenir le développement numérique des entreprises Alumni.</p><h3>Les engagements du partenariat</h3><p>Dans le cadre de cet accord, Orange Côte d\'Ivoire s\'engage à :</p><ul><li>Offrir des tarifs préférentiels sur les solutions mobile money et paiement en ligne aux entreprises Alumni</li><li>Accompagner 10 startups Alumni par an dans le cadre du programme Orange Fab</li><li>Co-organiser des ateliers de digitalisation pour les PME du réseau</li><li>Mettre à disposition une ligne dédiée de support technique pour les membres FAACI</li></ul><h3>Un partenariat gagnant-gagnant</h3><p>« Les Alumni AIESEC représentent l\'élite entrepreneuriale de demain. Ce partenariat nous permet de soutenir des talents qui transforment déjà l\'économie ivoirienne », souligne le Directeur Général d\'Orange Côte d\'Ivoire.</p><p>Ce partenariat est le premier d\'une série de collaborations que la FAACI entend nouer avec les grands acteurs économiques du pays.</p>',
                'statut'           => Article::STATUT_PUBLIE,
                'date_publication' => now()->subDays(90),
                'image_url'        => 'https://picsum.photos/seed/article-partenariat/800/500',
            ],
            [
                'titre'            => 'Témoignage : Comment AIESEC a transformé mon parcours professionnel',
                'categorie'        => 'Témoignage',
                'extrait'          => 'Nadia Meité, consultante RH et coach certifiée, partage son parcours depuis son passage dans AIESEC jusqu\'à la création de son cabinet de conseil, en s\'appuyant sur le réseau FAACI.',
                'contenu'          => '<p><em>Nadia Meité, Alumni AIESEC 2019, est fondatrice du cabinet de coaching et conseil RH Talents Africa CI. Elle revient sur son parcours.</em></p><p>« Quand j\'ai rejoint AIESEC en 2017 à Abidjan, j\'étais étudiante en gestion à l\'INPHB et je ne savais pas vraiment quelle direction prendre. AIESEC m\'a appris à gérer des équipes, à pitcher des projets, à travailler avec des personnes de cultures différentes. Ces deux ans ont été les plus formateurs de ma vie.</p><p>Après mon diplôme, j\'ai intégré le département RH d\'un groupe international. Mais c\'est grâce au réseau FAACI que tout a vraiment décollé. J\'ai rencontré mon mentor ici, j\'ai trouvé mes premiers clients ici, et c\'est lors d\'un événement FAACI que j\'ai décidé de créer mon propre cabinet en 2022.</p><h3>Ce que la FAACI m\'a apporté</h3><ul><li>Un réseau de confiance : j\'ai recruté mes trois premières collaboratrices parmi les Alumni</li><li>Un financement communautaire de 2,5 millions CFA pour lancer mon premier programme de formation</li><li>Un accompagnement continu via le programme de mentorat FAACI</li></ul><p>Aujourd\'hui, Talents Africa CI accompagne 40 entreprises et j\'ai formé plus de 300 professionnels. Je suis convaincue que sans AIESEC et sans la FAACI, ce parcours n\'aurait pas été possible aussi rapidement. »</p>',
                'statut'           => Article::STATUT_PUBLIE,
                'date_publication' => now()->subDays(45),
                'image_url'        => 'https://picsum.photos/seed/article-temoignage/800/500',
            ],
        ];

        foreach ($articles as $data) {
            $imageUrl = $data['image_url'];
            unset($data['image_url']);

            $data['slug'] = Slug::unique(Article::class, $data['titre']);

            $article = Article::create($data);

            try {
                $article->addMediaFromUrl($imageUrl)
                    ->usingFileName('article-' . $article->id . '.jpg')
                    ->toMediaCollection('image');
            } catch (\Throwable $e) {
                $this->command->warn("Image article \"{$article->titre}\" non chargée : {$e->getMessage()}");
            }
        }

        $this->command->info('6 articles créés avec succès.');
    }
}
