<?php

namespace App\Providers;

use App\Models\Activite;
use App\Models\AlbumGalerie;
use App\Models\Article;
use App\Models\ContenuSection;
use App\Models\Evenement;
use App\Models\ImageGalerie;
use App\Models\MembreEquipe;
use App\Models\ParametreSite;
use App\Models\Slide;
use App\Models\User;
use App\Models\Valeur;
use App\Observers\ActiviteObserver;
use App\Observers\AlbumGalerieObserver;
use App\Observers\ArticleObserver;
use App\Observers\ContenuSectionObserver;
use App\Observers\EvenementObserver;
use App\Observers\ImageGalerieObserver;
use App\Observers\MembreEquipeObserver;
use App\Observers\ParametreSiteObserver;
use App\Observers\SlideObserver;
use App\Observers\ValeurObserver;
use Illuminate\Auth\Middleware\RedirectIfAuthenticated;
use Illuminate\Pagination\Paginator;
use Illuminate\Support\ServiceProvider;

class AppServiceProvider extends ServiceProvider
{
    /**
     * Register any application services.
     */
    public function register(): void
    {
        //
    }

    /**
     * Bootstrap any application services.
     */
    public function boot(): void
    {
        Activite::observe(ActiviteObserver::class);
        Slide::observe(SlideObserver::class);
        Valeur::observe(ValeurObserver::class);
        MembreEquipe::observe(MembreEquipeObserver::class);
        ContenuSection::observe(ContenuSectionObserver::class);
        ParametreSite::observe(ParametreSiteObserver::class);
        Article::observe(ArticleObserver::class);
        Evenement::observe(EvenementObserver::class);
        AlbumGalerie::observe(AlbumGalerieObserver::class);
        ImageGalerie::observe(ImageGalerieObserver::class);

        Paginator::useBootstrapFive();

        // Redirige un utilisateur déjà authentifié qui accède aux pages
        // "connexion" / "adhésion" vers l'espace qui correspond à son
        // rôle et son statut, pour éviter un 403 sur /dashboard.
        RedirectIfAuthenticated::redirectUsing(function ($request) {
            $user = $request->user();

            if ($user?->hasAnyRole(['admin', 'super_admin'])) {
                return route('admin.dashboard');
            }

            if ($user?->statut !== User::STATUT_ACTIF) {
                return route('compte.statut');
            }

            return route('dashboard');
        });
    }
}
