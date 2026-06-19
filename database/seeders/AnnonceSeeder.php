<?php

namespace Database\Seeders;

use App\Models\Annonce;
use App\Models\User;
use Illuminate\Database\Seeder;

class AnnonceSeeder extends Seeder
{
    public function run(): void
    {
        $admin = User::role(['admin', 'super_admin'])->first();

        if (! $admin) {
            $this->command->warn('Aucun admin trouvé — AnnonceSeeder ignoré.');
            return;
        }

        $annonces = [
            // ── Publiées actives ──────────────────────────────────────
            [
                'titre'      => 'Bienvenue sur la plateforme FAACI !',
                'contenu'    => 'Chers membres, nous sommes ravis de vous accueillir sur la nouvelle plateforme numérique de la Fondation AIESEC Alumni CI. Explorez l\'annuaire, les projets et les opportunités disponibles. N\'hésitez pas à compléter votre profil pour être mieux visible dans la communauté.',
                'type'       => 'info',
                'statut'     => Annonce::STATUT_PUBLIEE,
                'publiee_at' => now()->subDays(5),
                'expire_at'  => null,
            ],
            [
                'titre'      => 'Cotisations annuelles 2026 — Rappel',
                'contenu'    => 'Merci de régulariser votre cotisation annuelle 2026 avant le 31 juillet. Le non-paiement entraînera la suspension temporaire de votre accès à la plateforme. Pour toute question, contactez l\'administration.',
                'type'       => 'warning',
                'statut'     => Annonce::STATUT_PUBLIEE,
                'publiee_at' => now()->subDays(3),
                'expire_at'  => now()->addDays(40),
            ],
            [
                'titre'      => 'Assemblée Générale Ordinaire — Save the date',
                'contenu'    => 'L\'Assemblée Générale Ordinaire de la FAACI se tiendra le samedi 26 juillet 2026 à Abidjan. Inscrivez-vous via la section Événements de la plateforme. La présence est fortement recommandée pour tous les membres actifs.',
                'type'       => 'urgent',
                'statut'     => Annonce::STATUT_PUBLIEE,
                'publiee_at' => now()->subDay(),
                'expire_at'  => now()->addDays(37),
            ],
            [
                'titre'      => 'Nouveau module Opportunités disponible',
                'contenu'    => 'Le module Opportunités d\'affaires est désormais disponible. Publiez vos appels d\'offres, partenariats et besoins en sous-traitance. Les annonces sont visibles par tous les membres actifs et modérées par l\'équipe admin.',
                'type'       => 'success',
                'statut'     => Annonce::STATUT_PUBLIEE,
                'publiee_at' => now()->subDays(2),
                'expire_at'  => null,
            ],

            // ── Brouillon ─────────────────────────────────────────────
            [
                'titre'      => 'Formation leadership — À venir',
                'contenu'    => 'Une formation sur le leadership et la gestion de projet sera organisée prochainement pour les membres. Les détails seront communiqués dès confirmation des intervenants.',
                'type'       => 'info',
                'statut'     => Annonce::STATUT_BROUILLON,
                'publiee_at' => null,
                'expire_at'  => null,
            ],

            // ── Archivée ──────────────────────────────────────────────
            [
                'titre'      => 'Lancement de la plateforme — Phase bêta terminée',
                'contenu'    => 'La phase bêta de la plateforme FAACI est officiellement terminée. Merci à tous les membres testeurs pour leurs retours précieux. La version finale est maintenant en ligne.',
                'type'       => 'success',
                'statut'     => Annonce::STATUT_ARCHIVEE,
                'publiee_at' => now()->subMonths(2),
                'expire_at'  => now()->subMonth(),
            ],
        ];

        foreach ($annonces as $data) {
            Annonce::create(array_merge($data, ['auteur_id' => $admin->id]));
        }

        $this->command->info('AnnonceSeeder : '.count($annonces).' annonces créées.');
    }
}
