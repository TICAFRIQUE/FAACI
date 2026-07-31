<?php

namespace App\Http\Controllers\Admin;

use App\Http\Controllers\Controller;
use Illuminate\Http\RedirectResponse;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Log;
use Illuminate\View\View;

class NotificationController extends Controller
{
    public function index(Request $request): View
    {
        $notifications = $request->user()
            ->notifications()
            ->latest()
            ->paginate(30);

        return view('admin.notifications.index', compact('notifications'));
    }

    /**
     * Marque comme lue et redirige vers le lien de la notification.
     */
    public function marquerLue(Request $request, string $notification): RedirectResponse
    {
        try {
            $notif = $request->user()->notifications()->findOrFail($notification);
            $notif->markAsRead();

            return redirect($notif->data['url'] ?? route('admin.dashboard'));
        } catch (\Throwable $e) {
            Log::error('Erreur lors du marquage de la notification : '.$e->getMessage());

            return back();
        }
    }

    /**
     * Marque comme lue et reste sur la page notifications (sans naviguer vers l'url).
     */
    public function marquerLueSeulement(Request $request, string $notification): RedirectResponse
    {
        try {
            $notif = $request->user()->notifications()->findOrFail($notification);
            $notif->markAsRead();
        } catch (\Throwable $e) {
            Log::error('Erreur marquage notification : '.$e->getMessage());
        }

        return redirect()->route('admin.notifications.index');
    }

    /**
     * Marque toutes les notifications de l'utilisateur connecté comme lues.
     */
    public function marquerToutesLues(Request $request): RedirectResponse
    {
        $request->user()->unreadNotifications->markAsRead();

        return back();
    }
}
