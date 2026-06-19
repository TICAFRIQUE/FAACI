<?php

namespace Database\Seeders;

use App\Models\Slide;
use Illuminate\Database\Seeder;

class SlideSeeder extends Seeder
{
    public function run(): void
    {
        Slide::query()->delete();

        $slides = [
            [
                'titre'            => 'Bienvenue dans le réseau FAACI',
                'sous_titre'       => 'Fondation AIESEC Alumni Côte d\'Ivoire',
                'description'      => 'Rejoignez une communauté de plus de 250 Alumni engagés, entrepreneurs et professionnels qui façonnent l\'avenir de la Côte d\'Ivoire.',
                'libelle_bouton_1' => 'Devenir membre',
                'lien_bouton_1'    => '/adhesion',
                'libelle_bouton_2' => 'En savoir plus',
                'lien_bouton_2'    => '/apropos/mission',
                'ordre'            => 1,
                'actif'            => true,
                'image_url'        => 'https://picsum.photos/seed/faaci-hero/1600/900',
            ],
            [
                'titre'            => 'Ensemble pour un impact durable',
                'sous_titre'       => 'Entrepreneuriat · Finance · Innovation',
                'description'      => 'La FAACI connecte les talents Alumni pour créer des opportunités d\'affaires, financer des projets et bâtir une économie ivoirienne plus forte.',
                'libelle_bouton_1' => 'Nos projets',
                'lien_bouton_1'    => '/connexion',
                'libelle_bouton_2' => 'Découvrir',
                'lien_bouton_2'    => '/activites',
                'ordre'            => 2,
                'actif'            => true,
                'image_url'        => 'https://picsum.photos/seed/faaci-impact/1600/900',
            ],
            [
                'titre'            => 'Vos opportunités vous attendent',
                'sous_titre'       => 'Emploi · Affaires · Partenariat',
                'description'      => 'Accédez à l\'annuaire Alumni, aux offres d\'emploi exclusives, aux appels d\'offres et aux opportunités de partenariat au sein du réseau.',
                'libelle_bouton_1' => 'Se connecter',
                'lien_bouton_1'    => '/connexion',
                'libelle_bouton_2' => 'Nos activités',
                'lien_bouton_2'    => '/activites',
                'ordre'            => 3,
                'actif'            => true,
                'image_url'        => 'https://picsum.photos/seed/faaci-opport/1600/900',
            ],
            [
                'titre'            => 'Événements & Pitchs Alumni',
                'sous_titre'       => 'Networking · Webinaires · Assemblées',
                'description'      => 'Ne manquez aucun événement du réseau : sessions de networking, pitchs de projets, webinaires thématiques et assemblées générales.',
                'libelle_bouton_1' => 'Voir les événements',
                'lien_bouton_1'    => '/evenements',
                'libelle_bouton_2' => 'Rejoindre',
                'lien_bouton_2'    => '/adhesion',
                'ordre'            => 4,
                'actif'            => true,
                'image_url'        => 'https://picsum.photos/seed/faaci-event/1600/900',
            ],
        ];

        foreach ($slides as $data) {
            $imageUrl = $data['image_url'];
            unset($data['image_url']);

            $slide = Slide::create($data);

            try {
                $slide->addMediaFromUrl($imageUrl)
                    ->usingFileName('slide-' . $slide->ordre . '.jpg')
                    ->toMediaCollection('image');
            } catch (\Throwable $e) {
                $this->command->warn("Image slide {$slide->ordre} non chargée : {$e->getMessage()}");
            }
        }

        $this->command->info('4 slides créées avec succès.');
    }
}
