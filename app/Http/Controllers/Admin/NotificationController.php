<?php

namespace App\Http\Controllers\Admin;

use App\Http\Controllers\Controller;
use Illuminate\Http\RedirectResponse;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Log;

class NotificationController extends Controller
{
    /**
     * Marque une notification comme lue puis redirige vers son lien.
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
     * Marque toutes les notifications de l'utilisateur connecté comme lues.
     */
    public function marquerToutesLues(Request $request): RedirectResponse
    {
        $request->user()->unreadNotifications->markAsRead();

        return back();
    }
}
