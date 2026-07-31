<?php

use App\Http\Controllers\Membre\AnnuaireController;
use App\Http\Controllers\Membre\CompetitionController as CompetitionMembreController;
use App\Http\Controllers\Membre\CotisationMembreController;
use App\Http\Controllers\Membre\DonController;
use App\Http\Controllers\Membre\EntrepriseController as EntrepriseMembreController;
use App\Http\Controllers\Membre\NotificationController as NotifMembreController;
use App\Http\Controllers\Membre\ContributionController;
use App\Http\Controllers\Membre\DashboardController;
use App\Http\Controllers\Membre\EvenementController as EvenementMembreController;
use App\Http\Controllers\Membre\MotDePasseController;
use App\Http\Controllers\Membre\OffreEmploiController as OffreEmploiMembreController;
use App\Http\Controllers\Membre\ProfilController;
use App\Http\Controllers\Membre\ProjetController;
use Illuminate\Support\Facades\Route;

Route::middleware(['auth', 'role:membre', 'statut:actif'])->prefix('espace-membre')->name('membre.')->group(function () {

    Route::get('/tableau-de-bord', [DashboardController::class, 'index'])->name('dashboard');

    // Profil personnel
    Route::get('/profil', [ProfilController::class, 'show'])->name('profil');
    Route::get('/profil/modifier', [ProfilController::class, 'edit'])->name('profil.edit');
    Route::put('/profil', [ProfilController::class, 'update'])->name('profil.update');
    Route::post('/profil/photo', [ProfilController::class, 'updatePhoto'])->name('profil.photo');
    Route::delete('/profil/photo', [ProfilController::class, 'deletePhoto'])->name('profil.photo.delete');

    // Mot de passe
    Route::get('/mot-de-passe', [MotDePasseController::class, 'edit'])->name('mot-de-passe.edit');
    Route::put('/mot-de-passe', [MotDePasseController::class, 'update'])->name('mot-de-passe.update');

    // Projets
    Route::get('/projets', [ProjetController::class, 'index'])->name('projets.index');
    Route::get('/projets/mes-projets', [ProjetController::class, 'mesProjets'])->name('projets.mes-projets');
    Route::get('/projets/nouveau', [ProjetController::class, 'create'])->name('projets.create');
    Route::post('/projets', [ProjetController::class, 'store'])->name('projets.store');
    Route::get('/projets/{projet}', [ProjetController::class, 'show'])->name('projets.show');
    Route::get('/projets/{projet}/modifier', [ProjetController::class, 'edit'])->name('projets.edit');
    Route::put('/projets/{projet}', [ProjetController::class, 'update'])->name('projets.update');
    Route::post('/projets/{projet}/soumettre', [ProjetController::class, 'soumettre'])->name('projets.soumettre');
    Route::delete('/projets/{projet}', [ProjetController::class, 'destroy'])->name('projets.destroy');

    // Contributions (financement)
    Route::post('/projets/{projet}/contribuer', [ContributionController::class, 'store'])->name('contributions.store');
    Route::get('/contributions', [ContributionController::class, 'index'])->name('contributions.index');
    Route::delete('/contributions/{contribution}/annuler', [ContributionController::class, 'annuler'])->name('contributions.annuler');

    // Notifications & annonces
    Route::get('/notifications', [NotifMembreController::class, 'index'])->name('notifications.index');
    Route::get('/notifications/count', [NotifMembreController::class, 'count'])->name('notifications.count');
    Route::get('/notifications/dropdown', [NotifMembreController::class, 'dropdown'])->name('notifications.dropdown');
    Route::patch('/notifications/tout-lire', [NotifMembreController::class, 'marquerToutesLues'])->name('notifications.tout-lire');
    Route::patch('/notifications/{id}/lue', [NotifMembreController::class, 'marquerLue'])->name('notifications.lue');

    // Annuaire des membres
    Route::get('/annuaire', [AnnuaireController::class, 'index'])->name('annuaire');
    Route::get('/annuaire/{membre}', [AnnuaireController::class, 'show'])->name('annuaire.show');

    // Annuaire entreprises Alumni
    Route::get('/entreprises', [EntrepriseMembreController::class, 'index'])->name('entreprises.index');
    Route::get('/entreprises/mes-entreprises', [EntrepriseMembreController::class, 'mesEntreprises'])->name('entreprises.mes-entreprises');
    Route::get('/entreprises/nouvelle', [EntrepriseMembreController::class, 'create'])->name('entreprises.create');
    Route::post('/entreprises', [EntrepriseMembreController::class, 'store'])->name('entreprises.store');
    Route::get('/entreprises/{entreprise}', [EntrepriseMembreController::class, 'show'])->name('entreprises.show');
    Route::get('/entreprises/{entreprise}/modifier', [EntrepriseMembreController::class, 'edit'])->name('entreprises.edit');
    Route::put('/entreprises/{entreprise}', [EntrepriseMembreController::class, 'update'])->name('entreprises.update');

    // Cotisations
    Route::get('/cotisations', [CotisationMembreController::class, 'index'])->name('cotisations.index');
    Route::post('/cotisations/paiement', [CotisationMembreController::class, 'store'])->name('cotisations.paiement.store');

    // Dons / Contributions à la fondation
    Route::get('/dons', [DonController::class, 'index'])->name('dons.index');
    Route::get('/dons/nouveau', [DonController::class, 'create'])->name('dons.create');
    Route::post('/dons', [DonController::class, 'store'])->name('dons.store');
    Route::post('/dons/{don}/annuler', [DonController::class, 'annuler'])->name('dons.annuler');

    // Événements
    Route::get('/evenements', [EvenementMembreController::class, 'index'])->name('evenements.index');
    Route::get('/evenements/calendrier-json', [EvenementMembreController::class, 'calendrierJson'])->name('evenements.calendrier-json');
    Route::get('/evenements/{evenement:slug}', [EvenementMembreController::class, 'show'])->name('evenements.show');
    Route::post('/evenements/{evenement:slug}/inscrire', [EvenementMembreController::class, 'inscrire'])->name('evenements.inscrire');
    Route::delete('/evenements/{evenement:slug}/desinscrire', [EvenementMembreController::class, 'desinscrire'])->name('evenements.desinscrire');

    // Compétitions Alumni
    Route::get('/competitions', [CompetitionMembreController::class, 'index'])->name('competitions.index');
    Route::get('/competitions/mes-candidatures', [CompetitionMembreController::class, 'mesCandidatures'])->name('competitions.mes-candidatures');
    Route::get('/competitions/{competition}', [CompetitionMembreController::class, 'show'])->name('competitions.show');
    Route::post('/competitions/{competition}/postuler', [CompetitionMembreController::class, 'postuler'])->name('competitions.postuler');

    // Offres d'emploi
    Route::get('/emplois', [OffreEmploiMembreController::class, 'index'])->name('emplois.index');
    Route::get('/emplois/mes-offres', [OffreEmploiMembreController::class, 'mesOffres'])->name('emplois.mes-offres');
    Route::get('/emplois/mes-candidatures', [OffreEmploiMembreController::class, 'mesCandidatures'])->name('emplois.mes-candidatures');
    Route::get('/emplois/publier', [OffreEmploiMembreController::class, 'create'])->name('emplois.create');
    Route::post('/emplois', [OffreEmploiMembreController::class, 'store'])->name('emplois.store');
    Route::get('/emplois/{offre:slug}', [OffreEmploiMembreController::class, 'show'])->name('emplois.show');
    Route::get('/emplois/{offre:slug}/modifier', [OffreEmploiMembreController::class, 'edit'])->name('emplois.edit');
    Route::put('/emplois/{offre:slug}', [OffreEmploiMembreController::class, 'update'])->name('emplois.update');
    Route::delete('/emplois/{offre:slug}', [OffreEmploiMembreController::class, 'destroy'])->name('emplois.destroy');
    Route::post('/emplois/{offre:slug}/postuler', [OffreEmploiMembreController::class, 'postuler'])->name('emplois.postuler');
});
