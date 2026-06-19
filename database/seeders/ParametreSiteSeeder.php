<?php

namespace Database\Seeders;

use App\Models\ParametreSite;
use Illuminate\Database\Seeder;

class ParametreSiteSeeder extends Seeder
{
    /**
     * Run the database seeds.
     */
    public function run(): void
    {
        $parametres = [
            ['cle' => 'logo',            'libelle' => 'Logo du site (header & footer)',  'valeur' => ''],
            ['cle' => 'email_contact',   'libelle' => 'E-mail de contact',               'valeur' => 'contact@faaci.ci'],
            ['cle' => 'telephone',       'libelle' => 'Téléphone',                       'valeur' => '+225 07 00 00 00 00'],
            ['cle' => 'adresse',         'libelle' => 'Adresse',                         'valeur' => "Plateau, Abidjan, Côte d'Ivoire"],
            ['cle' => 'facebook_url',    'libelle' => 'Lien Facebook',                   'valeur' => '#'],
            ['cle' => 'linkedin_url',    'libelle' => 'Lien LinkedIn',                   'valeur' => '#'],
            ['cle' => 'whatsapp_url',    'libelle' => 'Lien WhatsApp',                   'valeur' => '#'],
            ['cle' => 'footer_texte',    'libelle' => 'Texte du footer',                 'valeur' => "Le réseau des Alumni AIESEC en Côte d'Ivoire. Connecter, collaborer, bâtir ensemble."],
            ['cle' => 'footer_copyright','libelle' => 'Texte du copyright (l\'année est ajoutée automatiquement)', 'valeur' => 'Fondation AIESEC Alumni CI — FAACI. Tous droits réservés.'],
        ];

        foreach ($parametres as $ordre => $parametre) {
            ParametreSite::firstOrCreate(
                ['cle' => $parametre['cle']],
                [
                    'libelle' => $parametre['libelle'],
                    'valeur' => $parametre['valeur'],
                    'ordre' => $ordre,
                ]
            );
        }
    }
}
