<?php

namespace Database\Seeders;

use App\Models\ContenuSection;
use Illuminate\Database\Seeder;

class ContenuSectionSeeder extends Seeder
{
    /**
     * Run the database seeds.
     */
    public function run(): void
    {
        $groupes = [
            'accueil_about' => [
                ['cle' => 'about_eyebrow',  'libelle' => 'Accroche',    'type' => ContenuSection::TYPE_TEXTE,    'valeur' => 'À propos de la FAACI'],
                ['cle' => 'about_titre',    'libelle' => 'Titre',       'type' => ContenuSection::TYPE_TEXTE,    'valeur' => "Une communauté d'Alumni engagés"],
                ['cle' => 'about_texte_1',  'libelle' => 'Paragraphe 1','type' => ContenuSection::TYPE_TEXTAREA, 'valeur' => "La Fondation AIESEC Alumni Côte d'Ivoire (FAACI) regroupe les anciens membres d'AIESEC en Côte d'Ivoire. Notre mission : maintenir et renforcer les liens du réseau, partager les opportunités économiques et professionnelles, et porter ensemble des initiatives à fort impact."],
                ['cle' => 'about_texte_2',  'libelle' => 'Paragraphe 2','type' => ContenuSection::TYPE_TEXTAREA, 'valeur' => "La FAACI est un espace exclusif réservé à ses membres : annuaire, entreprises Alumni, opportunités d'affaires, offres d'emploi et événements y vivent à l'abri du grand public."],
                ['cle' => 'about_image',       'libelle' => 'Image illustrative (section À propos)', 'type' => ContenuSection::TYPE_IMAGE,    'valeur' => ''],
                ['cle' => 'about_badge_nombre','libelle' => 'Badge — nombre',                        'type' => ContenuSection::TYPE_TEXTE,    'valeur' => '10+'],
                ['cle' => 'about_badge_label', 'libelle' => 'Badge — texte',                         'type' => ContenuSection::TYPE_TEXTAREA, 'valeur' => "années d'engagement"],
            ],
            'accueil_stats' => [
                ['cle' => 'stat_1_nombre', 'libelle' => 'Statistique 1 — nombre', 'type' => ContenuSection::TYPE_NOMBRE, 'valeur' => '247'],
                ['cle' => 'stat_1_label',  'libelle' => 'Statistique 1 — libellé','type' => ContenuSection::TYPE_TEXTE,   'valeur' => 'Membres actifs'],
                ['cle' => 'stat_2_nombre', 'libelle' => 'Statistique 2 — nombre', 'type' => ContenuSection::TYPE_NOMBRE, 'valeur' => '38'],
                ['cle' => 'stat_2_label',  'libelle' => 'Statistique 2 — libellé','type' => ContenuSection::TYPE_TEXTE,   'valeur' => 'Événements / an'],
                ['cle' => 'stat_3_nombre', 'libelle' => 'Statistique 3 — nombre', 'type' => ContenuSection::TYPE_NOMBRE, 'valeur' => '62'],
                ['cle' => 'stat_3_label',  'libelle' => 'Statistique 3 — libellé','type' => ContenuSection::TYPE_TEXTE,   'valeur' => 'Entreprises Alumni'],
                ['cle' => 'stat_4_nombre', 'libelle' => 'Statistique 4 — nombre', 'type' => ContenuSection::TYPE_NOMBRE, 'valeur' => '12'],
                ['cle' => 'stat_4_label',  'libelle' => 'Statistique 4 — libellé','type' => ContenuSection::TYPE_TEXTE,   'valeur' => 'Pays représentés'],
                ['cle' => 'stat_5_nombre', 'libelle' => 'Statistique 5 — nombre', 'type' => ContenuSection::TYPE_NOMBRE, 'valeur' => '10'],
                ['cle' => 'stat_5_label',  'libelle' => 'Statistique 5 — libellé','type' => ContenuSection::TYPE_TEXTE,   'valeur' => "Ans d'engagement"],
            ],
            'accueil_activites' => [
                ['cle' => 'activites_eyebrow', 'libelle' => 'Accroche', 'type' => ContenuSection::TYPE_TEXTE, 'valeur' => 'Ce que la FAACI offre'],
                ['cle' => 'activites_titre', 'libelle' => 'Titre', 'type' => ContenuSection::TYPE_TEXTE, 'valeur' => 'Nos activités'],
                ['cle' => 'activites_texte', 'libelle' => 'Texte d\'introduction', 'type' => ContenuSection::TYPE_TEXTAREA, 'valeur' => "Une plateforme complète, exclusivement réservée aux Alumni AIESEC de Côte d'Ivoire."],
            ],
            'accueil_rejoindre' => [
                ['cle' => 'rejoindre_eyebrow', 'libelle' => 'Accroche', 'type' => ContenuSection::TYPE_TEXTE, 'valeur' => 'Comment rejoindre'],
                ['cle' => 'rejoindre_titre', 'libelle' => 'Titre', 'type' => ContenuSection::TYPE_TEXTE, 'valeur' => 'Devenir membre en 4 étapes'],
            ],
            'accueil_cta' => [
                ['cle' => 'cta_eyebrow', 'libelle' => 'Accroche', 'type' => ContenuSection::TYPE_TEXTE, 'valeur' => 'Rejoignez-nous'],
                ['cle' => 'cta_titre', 'libelle' => 'Titre', 'type' => ContenuSection::TYPE_TEXTE, 'valeur' => 'Prêt à faire partie du réseau FAACI ?'],
                ['cle' => 'cta_texte', 'libelle' => 'Texte', 'type' => ContenuSection::TYPE_TEXTAREA, 'valeur' => "Devenez membre et accédez à l'annuaire des Alumni, aux opportunités d'affaires, aux offres d'emploi et à tous les événements du réseau."],
            ],
            'apropos_mission' => [
                ['cle' => 'mission_titre', 'libelle' => 'Titre', 'type' => ContenuSection::TYPE_TEXTE, 'valeur' => 'Notre mission'],
                ['cle' => 'mission_contenu', 'libelle' => 'Contenu', 'type' => ContenuSection::TYPE_RICHTEXT, 'valeur' => "Maintenir et renforcer les liens du réseau des Alumni AIESEC en Côte d'Ivoire, partager les opportunités économiques et professionnelles entre membres, et porter ensemble des initiatives à fort impact pour la communauté."],
            ],
            'apropos_vision' => [
                ['cle' => 'vision_titre', 'libelle' => 'Titre', 'type' => ContenuSection::TYPE_TEXTE, 'valeur' => 'Notre vision'],
                ['cle' => 'vision_contenu', 'libelle' => 'Contenu', 'type' => ContenuSection::TYPE_RICHTEXT, 'valeur' => "Devenir la référence du réseau Alumni en Côte d'Ivoire : un écosystème où les anciens membres d'AIESEC collaborent, investissent et grandissent ensemble, au service du développement économique du pays et du continent."],
            ],
            'apropos_histoire' => [
                ['cle' => 'histoire_titre', 'libelle' => 'Titre', 'type' => ContenuSection::TYPE_TEXTE, 'valeur' => 'Notre histoire'],
                ['cle' => 'histoire_contenu', 'libelle' => 'Contenu', 'type' => ContenuSection::TYPE_RICHTEXT, 'valeur' => "La Fondation AIESEC Alumni Côte d'Ivoire est née de la volonté d'anciens membres d'AIESEC de garder vivant l'esprit du réseau après leur passage dans l'organisation, en créant un cadre structuré pour continuer à se rencontrer, s'entraider et entreprendre ensemble."],
            ],
        ];

        foreach ($groupes as $groupe => $contenus) {
            foreach ($contenus as $ordre => $contenu) {
                ContenuSection::firstOrCreate(
                    ['cle' => $contenu['cle']],
                    [
                        'groupe' => $groupe,
                        'libelle' => $contenu['libelle'],
                        'type' => $contenu['type'],
                        'valeur' => $contenu['valeur'],
                        'ordre' => $ordre,
                    ]
                );
            }
        }
    }
}
