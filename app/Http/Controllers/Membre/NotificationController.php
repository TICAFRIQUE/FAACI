<?php

namespace App\Http\Controllers\Membre;

use App\Http\Controllers\Controller;
use App\Models\Annonce;
use Illuminate\Http\RedirectResponse;
use Illuminate\View\View;

class NotificationController extends Controller
{
    public function index(): View
    {
        $user          = auth()->user();
        $notifications = $user->notifications()->latest()->paginate(20);
        $annonces      = Annonce::actives()->latest('publiee_at')->get();

        $user->update(['annonces_lues_at' => now()]);
        $user->unreadNotifications->markAsRead();

        return view('membre.notifications.index', compact('notifications', 'annonces'));
    }

    public function marquerLue(string $id): RedirectResponse
    {
        $notif = auth()->user()->notifications()->findOrFail($id);
        $notif->markAsRead();

        return redirect($notif->data['url'] ?? route('membre.dashboard'));
    }

    public function marquerToutesLues(): RedirectResponse
    {
        auth()->user()->unreadNotifications->markAsRead();
        auth()->user()->update(['annonces_lues_at' => now()]);

        return back()->with('status', 'Toutes les notifications ont été marquées comme lues.');
    }
}
