<?php

namespace App\Http\Controllers\Membre;

use App\Http\Controllers\Controller;
use App\Models\Annonce;
use App\Models\User;
use Illuminate\Http\JsonResponse;
use Illuminate\Http\RedirectResponse;
use Illuminate\Support\Facades\Auth;
use Illuminate\View\View;

class NotificationController extends Controller
{
    public function index(): View
    {
        /** @var User $user */
        $user = Auth::user();

        $notifications = $user->notifications()
            ->where(function ($q) {
                $q->whereNull('read_at')
                  ->orWhere('read_at', '>', now()->subDays(7));
            })
            ->latest()
            ->get();

        $annonces = Annonce::actives()
            ->where(function ($q) use ($user) {
                $seuil = $user->annonces_lues_at;
                if ($seuil) {
                    $q->where('publiee_at', '>', $seuil)
                      ->orWhere('publiee_at', '>', now()->subDays(7));
                }
            })
            ->latest('publiee_at')
            ->get();

        $user->unreadNotifications()->update(['read_at' => now()]);
        $user->forceFill(['annonces_lues_at' => now()])->save();

        return view('membre.notifications.index', compact('notifications', 'annonces'));
    }

    public function dropdown(): \Illuminate\Contracts\View\View
    {
        /** @var User $user */
        $user = Auth::user();

        $notifications = $user->unreadNotifications()->latest()->take(5)->get();
        $annonces      = Annonce::actives()
            ->when($user->annonces_lues_at, fn ($q) => $q->where('publiee_at', '>', $user->annonces_lues_at))
            ->latest('publiee_at')->take(5)->get();

        return view('membre.notifications._dropdown', compact('notifications', 'annonces'));
    }

    public function count(): JsonResponse
    {
        /** @var User $user */
        $user       = Auth::user();
        $nbNotifs   = $user->unreadNotifications()->count();
        $nbAnnonces = Annonce::actives()
            ->when($user->annonces_lues_at, fn ($q) => $q->where('publiee_at', '>', $user->annonces_lues_at))
            ->count();

        return response()->json(['total' => $nbNotifs + $nbAnnonces]);
    }

    public function marquerLue(string $id): RedirectResponse
    {
        /** @var User $user */
        $user  = Auth::user();
        $notif = $user->notifications()->findOrFail($id);
        $notif->markAsRead();

        return redirect($notif->data['url'] ?? route('membre.dashboard'));
    }

    public function marquerToutesLues(): RedirectResponse
    {
        /** @var User $user */
        $user = Auth::user();
        $user->unreadNotifications()->update(['read_at' => now()]);
        $user->forceFill(['annonces_lues_at' => now()])->save();

        return back()->with('status', 'Toutes les notifications ont été marquées comme lues.');
    }
}
