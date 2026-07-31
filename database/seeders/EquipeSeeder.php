<?php

namespace Database\Seeders;

use App\Models\MembreEquipe;
use Illuminate\Database\Seeder;

class EquipeSeeder extends Seeder
{
    public function run(): void
    {
        if (MembreEquipe::count() > 0) return;

        MembreEquipe::query()->delete();

        $membres = [
            [
                'prenom'    => 'Madeleine',
                'nom'       => 'Kouassi-Adjoumani',
                'fonction'  => 'Présidente de la FAACI',
                'bio'       => '<p>Ancienne Vice-Présidente d\'AIESEC Abidjan (promotion 2012), Madeleine est Directrice Générale d\'un groupe d\'assurance panafricain. Elle cofonde la FAACI en 2015 avec l\'ambition de structurer et pérenniser le réseau Alumni AIESEC en Côte d\'Ivoire.</p><p>Titulaire d\'un MBA de l\'INSEAD, elle est également membre du conseil d\'administration de la Chambre de Commerce et d\'Industrie de Côte d\'Ivoire.</p>',
                'ordre'     => 1,
                'actif'     => true,
                'image_url' => 'https://picsum.photos/seed/equipe-presidente/400/400',
            ],
            [
                'prenom'    => 'Arnaud',
                'nom'       => 'Dembélé',
                'fonction'  => 'Vice-Président Exécutif',
                'bio'       => '<p>Arnaud est Directeur Associé dans un cabinet de conseil en stratégie à Abidjan. Ancien Président Local d\'AIESEC Abidjan (promotion 2013), il supervise les opérations et les partenariats stratégiques de la FAACI.</p><p>Passionné de développement économique africain, il anime régulièrement des conférences sur l\'entrepreneuriat et l\'investissement en Afrique subsaharienne.</p>',
                'ordre'     => 2,
                'actif'     => true,
                'image_url' => 'https://picsum.photos/seed/equipe-vp/400/400',
            ],
            [
                'prenom'    => 'Sylvie',
                'nom'       => 'Aka-Anghui',
                'fonction'  => 'Secrétaire Générale',
                'bio'       => '<p>Juriste d\'entreprise spécialisée en droit OHADA, Sylvie assure la gouvernance et la conformité de la FAACI. Ancienne membre d\'AIESEC Bouaké (promotion 2014), elle est également coordinatrice d\'un programme d\'accompagnement des femmes entrepreneures à Abidjan.</p>',
                'ordre'     => 3,
                'actif'     => true,
                'image_url' => 'https://picsum.photos/seed/equipe-sg/400/400',
            ],
            [
                'prenom'    => 'Rodrigue',
                'nom'       => 'Fofana',
                'fonction'  => 'Trésorier',
                'bio'       => '<p>Expert-comptable certifié et associé dans un cabinet d\'audit, Rodrigue gère les finances et la transparence de la FAACI. Promotion AIESEC 2013, il est un défenseur actif de la gouvernance financière des associations à but non lucratif en Côte d\'Ivoire.</p>',
                'ordre'     => 4,
                'actif'     => true,
                'image_url' => 'https://picsum.photos/seed/equipe-tresorier/400/400',
            ],
            [
                'prenom'    => 'Laeticia',
                'nom'       => 'Boni',
                'fonction'  => 'Chargée de Communication & Réseaux',
                'bio'       => '<p>Directrice d\'une agence de communication digitale, Laeticia pilote la stratégie de visibilité de la FAACI sur les réseaux sociaux et dans les médias. Ancienne membre d\'AIESEC Abidjan (promotion 2017), elle est reconnue comme l\'une des 30 entrepreneuses les plus influentes de Côte d\'Ivoire.</p>',
                'ordre'     => 5,
                'actif'     => true,
                'image_url' => 'https://picsum.photos/seed/equipe-comm/400/400',
            ],
            [
                'prenom'    => 'Stéphane',
                'nom'       => 'Coulibaly',
                'fonction'  => 'Chargé des Relations Alumni',
                'bio'       => '<p>Manager RH dans un groupe industriel ivoirien, Stéphane coordonne l\'animation du réseau Alumni et l\'accueil des nouveaux membres. Promotion AIESEC 2016, il est également mentor pour les jeunes diplômés dans le cadre du programme FAACI Mentoring.</p>',
                'ordre'     => 6,
                'actif'     => true,
                'image_url' => 'https://picsum.photos/seed/equipe-alumni/400/400',
            ],
        ];

        foreach ($membres as $data) {
            $imageUrl = $data['image_url'];
            unset($data['image_url']);

            $membre = MembreEquipe::create($data);

            try {
                $membre->addMediaFromUrl($imageUrl)
                    ->usingFileName('equipe-' . $membre->id . '.jpg')
                    ->toMediaCollection('photo');
            } catch (\Throwable $e) {
                $this->command->warn("Photo {$membre->prenom} non chargée : {$e->getMessage()}");
            }
        }

        $this->command->info('6 membres de l\'équipe créés avec succès.');
    }
}
