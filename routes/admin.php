<?php

use App\Http\Controllers\Admin\AccueilController;
use App\Http\Controllers\Admin\ActiviteController;
use App\Http\Controllers\Admin\ContributionController;
use App\Http\Controllers\Admin\ProjetController as ProjetAdminController;
use App\Http\Controllers\Admin\AlbumGalerieController;
use App\Http\Controllers\Admin\AProposController;
use App\Http\Controllers\Admin\ArticleController;
use App\Http\Controllers\Admin\DashboardController;
use App\Http\Controllers\Admin\EvenementController;
use App\Http\Controllers\Admin\OffreEmploiController as OffreEmploiAdminController;
use App\Http\Controllers\Admin\AdministrateurController;
use App\Http\Controllers\Admin\MembreController;
use App\Http\Controllers\Admin\MembreEquipeController;
use App\Http\Controllers\Admin\AnnonceController;
use App\Http\Controllers\Admin\NotificationController;
use App\Http\Controllers\Admin\ParametreSiteController;
use App\Http\Controllers\Admin\SlideController;
use App\Http\Controllers\Admin\ValeurController;
use Illuminate\Support\Facades\Route;

Route::prefix('admin')
    ->name('admin.')
    ->middleware(['auth', 'role:admin|super_admin', 'statut:actif'])
    ->group(function () {
        Route::get('/', [DashboardController::class, 'index'])->name('dashboard');

        // Gestion des membres
        Route::get('membres', [MembreController::class, 'index'])->name('membres.index');
        Route::get('membres/{membre}', [MembreController::class, 'show'])->name('membres.show');
        Route::patch('membres/{membre}/valider', [MembreController::class, 'valider'])->name('membres.valider');
        Route::patch('membres/{membre}/refuser', [MembreController::class, 'refuser'])->name('membres.refuser');
        Route::patch('membres/{membre}/suspendre', [MembreController::class, 'suspendre'])->name('membres.suspendre');
        Route::patch('membres/{membre}/reactiver', [MembreController::class, 'reactiver'])->name('membres.reactiver');
        Route::patch('membres/{membre}/desactiver', [MembreController::class, 'desactiver'])->name('membres.desactiver');
        Route::patch('membres/{membre}/role', [MembreController::class, 'role'])->name('membres.role')->middleware('role:super_admin');
        // Actions réservées au super_admin
        Route::middleware('role:super_admin')->group(function () {
            Route::get('membres/{membre}/modifier', [MembreController::class, 'edit'])->name('membres.edit');
            Route::put('membres/{membre}', [MembreController::class, 'update'])->name('membres.update');
            Route::post('membres/{membre}/reinitialiser-mot-de-passe', [MembreController::class, 'reinitialiserMotDePasse'])->name('membres.reinitialiser-mot-de-passe');
            Route::delete('membres/{membre}', [MembreController::class, 'destroy'])->name('membres.destroy');
        });

        // Gestion des administrateurs (super_admin uniquement)
        Route::middleware('role:super_admin')->group(function () {
            Route::get('administrateurs', [AdministrateurController::class, 'index'])->name('administrateurs.index');
            Route::get('administrateurs/creer', [AdministrateurController::class, 'create'])->name('administrateurs.create');
            Route::post('administrateurs', [AdministrateurController::class, 'store'])->name('administrateurs.store');
            Route::get('administrateurs/{utilisateur}', [AdministrateurController::class, 'show'])->name('administrateurs.show');
            Route::get('administrateurs/{utilisateur}/modifier', [AdministrateurController::class, 'edit'])->name('administrateurs.edit');
            Route::put('administrateurs/{utilisateur}', [AdministrateurController::class, 'update'])->name('administrateurs.update');
            Route::post('administrateurs/{utilisateur}/reinitialiser-mot-de-passe', [AdministrateurController::class, 'reinitialiserMotDePasse'])->name('administrateurs.reinitialiser-mot-de-passe');
            Route::delete('administrateurs/{utilisateur}', [AdministrateurController::class, 'destroy'])->name('administrateurs.destroy');
            Route::patch('administrateurs/{utilisateur}/suspendre', [AdministrateurController::class, 'suspendre'])->name('administrateurs.suspendre');
            Route::patch('administrateurs/{utilisateur}/reactiver', [AdministrateurController::class, 'reactiver'])->name('administrateurs.reactiver');
            Route::patch('administrateurs/{utilisateur}/desactiver', [AdministrateurController::class, 'desactiver'])->name('administrateurs.desactiver');
            Route::patch('administrateurs/{utilisateur}/role', [AdministrateurController::class, 'role'])->name('administrateurs.role');
        });

        // Notifications internes
        Route::patch('notifications/tout-lire', [NotificationController::class, 'marquerToutesLues'])->name('notifications.tout-lire');
        Route::patch('notifications/{notification}/lue', [NotificationController::class, 'marquerLue'])->name('notifications.lue');

        // Annonces membres
        Route::resource('annonces', AnnonceController::class)->except(['show']);

        // Slider (hero)
        Route::resource('slides', SlideController::class)->except(['show']);
        Route::patch('slides/{slide}/basculer', [SlideController::class, 'basculer'])->name('slides.basculer');
        Route::patch('slides/{slide}/deplacer', [SlideController::class, 'deplacer'])->name('slides.deplacer');

        // Valeurs
        Route::resource('valeurs', ValeurController::class)->except(['show']);
        Route::patch('valeurs/{valeur}/deplacer', [ValeurController::class, 'deplacer'])->name('valeurs.deplacer');

        // Activités (domaines d'action)
        Route::resource('activites', ActiviteController::class)->except(['show']);
        Route::patch('activites/{activite}/basculer', [ActiviteController::class, 'basculer'])->name('activites.basculer');
        Route::patch('activites/{activite}/deplacer', [ActiviteController::class, 'deplacer'])->name('activites.deplacer');

        // Équipe dirigeante
        Route::resource('equipe', MembreEquipeController::class)->except(['show']);
        Route::patch('equipe/{equipe}/basculer', [MembreEquipeController::class, 'basculer'])->name('equipe.basculer');
        Route::patch('equipe/{equipe}/deplacer', [MembreEquipeController::class, 'deplacer'])->name('equipe.deplacer');

        // Page d'accueil (contenus éditables)
        Route::get('accueil', [AccueilController::class, 'edit'])->name('accueil.edit');
        Route::put('accueil', [AccueilController::class, 'update'])->name('accueil.update');

        // À propos (Mission / Vision / Histoire)
        Route::get('a-propos', [AProposController::class, 'edit'])->name('apropos.edit');
        Route::put('a-propos', [AProposController::class, 'update'])->name('apropos.update');

        // Paramètres du site
        Route::get('parametres', [ParametreSiteController::class, 'edit'])->name('parametres.edit');
        Route::put('parametres', [ParametreSiteController::class, 'update'])->name('parametres.update');

        // Actualités
        Route::resource('articles', ArticleController::class)->except(['show']);
        Route::delete('articles/{article}/photos/{media}', [ArticleController::class, 'supprimerPhoto'])->name('articles.photos.destroy');

        // Événements
        Route::resource('evenements', EvenementController::class);
        Route::patch('evenements/{evenement}/basculer', [EvenementController::class, 'basculerStatut'])->name('evenements.basculer');

        // Offres d'emploi
        Route::get('emplois', [OffreEmploiAdminController::class, 'index'])->name('emplois.index');
        Route::get('emplois/creer', [OffreEmploiAdminController::class, 'create'])->name('emplois.create');
        Route::post('emplois', [OffreEmploiAdminController::class, 'store'])->name('emplois.store');
        Route::get('emplois/{offre}', [OffreEmploiAdminController::class, 'show'])->name('emplois.show');
        Route::patch('emplois/{offre}/valider', [OffreEmploiAdminController::class, 'valider'])->name('emplois.valider');
        Route::patch('emplois/{offre}/rejeter', [OffreEmploiAdminController::class, 'rejeter'])->name('emplois.rejeter');
        Route::get('emplois/{offre}/candidatures', [OffreEmploiAdminController::class, 'candidatures'])->name('emplois.candidatures');
        Route::patch('candidatures/{candidature}/statut', [OffreEmploiAdminController::class, 'majCandidature'])->name('candidatures.maj');

        // Projets & financement
        Route::get('projets', [ProjetAdminController::class, 'index'])->name('projets.index');
        Route::get('projets/{projet}', [ProjetAdminController::class, 'show'])->name('projets.show');
        Route::patch('projets/{projet}/valider', [ProjetAdminController::class, 'valider'])->name('projets.valider');
        Route::patch('projets/{projet}/rejeter', [ProjetAdminController::class, 'rejeter'])->name('projets.rejeter');
        Route::patch('projets/{projet}/statut', [ProjetAdminController::class, 'changerStatut'])->name('projets.statut');

        Route::get('contributions', [ContributionController::class, 'index'])->name('contributions.index');
        Route::get('contributions/{contribution}', [ContributionController::class, 'show'])->name('contributions.show');
        Route::patch('contributions/{contribution}/valider', [ContributionController::class, 'valider'])->name('contributions.valider');
        Route::patch('contributions/{contribution}/rejeter', [ContributionController::class, 'rejeter'])->name('contributions.rejeter');

        // Galerie (albums + images)
        Route::resource('galerie', AlbumGalerieController::class)->except(['show']);
        Route::patch('galerie/{galerie}/basculer', [AlbumGalerieController::class, 'basculer'])->name('galerie.basculer');
        Route::patch('galerie/{galerie}/deplacer', [AlbumGalerieController::class, 'deplacer'])->name('galerie.deplacer');
        Route::post('galerie/{galerie}/images', [AlbumGalerieController::class, 'storeImages'])->name('galerie.images.store');
        Route::delete('galerie/{galerie}/images/{image}', [AlbumGalerieController::class, 'destroyImage'])->name('galerie.images.destroy');
        Route::patch('galerie/{galerie}/images/{image}/deplacer', [AlbumGalerieController::class, 'deplacerImage'])->name('galerie.images.deplacer');
    });
