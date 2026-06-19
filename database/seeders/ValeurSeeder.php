<?php

namespace Database\Seeders;

use App\Models\Valeur;
use Illuminate\Database\Seeder;

class ValeurSeeder extends Seeder
{
    public function run(): void
    {
        Valeur::query()->delete();

        $valeurs = [
            [
                'icone'       => 'bi-star-fill',
                'titre'       => 'Leadership',
                'description' => 'Nous croyons en la capacité de chaque Alumni à inspirer, motiver et guider. La FAACI forme et valorise des leaders qui agissent avec vision et responsabilité pour le bien collectif.',
                'ordre'       => 1,
            ],
            [
                'icone'       => 'bi-heart-fill',
                'titre'       => 'Engagement',
                'description' => 'Chaque membre de la FAACI s\'engage activement dans la vie du réseau et dans la société. Cet engagement nourrit la cohésion, la dynamique collective et l\'impact réel sur notre communauté.',
                'ordre'       => 2,
            ],
            [
                'icone'       => 'bi-lightbulb-fill',
                'titre'       => 'Innovation',
                'description' => 'Face aux défis du continent africain, nous encourageons la créativité, l\'esprit entrepreneurial et les solutions nouvelles. L\'innovation est au cœur de notre démarche pour un avenir prospère.',
                'ordre'       => 3,
            ],
            [
                'icone'       => 'bi-shield-check',
                'titre'       => 'Intégrité',
                'description' => 'La confiance se construit sur la transparence et l\'honnêteté. Tous les membres et administrateurs de la FAACI agissent avec droiture et responsabilité dans l\'intérêt commun.',
                'ordre'       => 4,
            ],
            [
                'icone'       => 'bi-people-fill',
                'titre'       => 'Solidarité',
                'description' => 'La force de notre réseau repose sur l\'entraide. Nous soutenons les projets de nos pairs, partageons nos expertises et créons ensemble des opportunités mutuellement bénéfiques.',
                'ordre'       => 5,
            ],
            [
                'icone'       => 'bi-trophy-fill',
                'titre'       => 'Excellence',
                'description' => 'Héritiers de la culture AIESEC, nous visons l\'excellence dans tout ce que nous entreprendons — qu\'il s\'agisse de nos carrières, de nos projets ou de notre contribution à la société ivoirienne.',
                'ordre'       => 6,
            ],
        ];

        foreach ($valeurs as $data) {
            Valeur::create($data);
        }

        $this->command->info('6 valeurs créées avec succès.');
    }
}
