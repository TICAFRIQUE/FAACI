<?php

namespace App\Observers;

use App\Models\Evenement;
use App\Models\User;
use App\Notifications\EvenementAVenir;
use App\Support\Slug;
use Illuminate\Support\Facades\Cache;
use Illuminate\Support\Facades\Notification;

class EvenementObserver
{
    public function creating(Evenement $evenement): void
    {
        $evenement->slug = Slug::unique(Evenement::class, $evenement->slug ?: $evenement->titre);
    }

    public function updating(Evenement $evenement): void
    {
        if ($evenement->isDirty('slug')) {
            $evenement->slug = Slug::unique(Evenement::class, $evenement->slug, $evenement->id);
        }
    }

    public function saved(Evenement $evenement): void
    {
        Cache::forget(Evenement::CACHE_KEY_PROCHAINS);
        Cache::forget('evenements.prochains_membres');

        // Notifier les membres actifs quand un événement passe en "publie"
        if ($evenement->wasChanged('statut') && $evenement->statut === Evenement::STATUT_PUBLIE) {
            $membres = User::role('membre')->where('statut', User::STATUT_ACTIF)->get();
            Notification::send($membres, new EvenementAVenir($evenement));
        }
    }

    public function deleted(Evenement $evenement): void
    {
        Cache::forget(Evenement::CACHE_KEY_PROCHAINS);
    }
}
