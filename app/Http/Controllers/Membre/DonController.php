<?php

namespace App\Http\Controllers\Membre;

use App\Http\Controllers\Controller;
use App\Http\Requests\Membre\DonRequest;
use App\Models\Don;
use App\Models\User;
use App\Notifications\Admin\NouveauDon;
use Illuminate\Http\RedirectResponse;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\Log;
use Illuminate\Support\Facades\Notification;
use Illuminate\View\View;

class DonController extends Controller
{
    public function index(): View
    {
        $dons = Don::where('utilisateur_id', Auth::id())
            ->latest()
            ->get();

        return view('membre.dons.index', compact('dons'));
    }

    public function create(): View
    {
        return view('membre.dons.create');
    }

    public function store(DonRequest $request): RedirectResponse
    {
        try {
            $data = $request->validated();
            $data['utilisateur_id'] = Auth::id();
            $data['statut']         = Don::STATUT_EN_ATTENTE;

            $don = Don::create($data);

            if ($request->hasFile('preuve')) {
                $don->addMediaFromRequest('preuve')->toMediaCollection('preuves');
            }

            $admins = User::role(['admin', 'super_admin'])->get();
            Notification::send($admins, new NouveauDon($don, Auth::user()));

            return redirect()->route('membre.dons.index')
                ->with('status', 'Votre don a bien été enregistré. Il sera traité par notre équipe.');
        } catch (\Throwable $e) {
            Log::error('Erreur création don membre #'.Auth::id().' : '.$e->getMessage());

            return back()->withInput()->with('error', 'Une erreur est survenue.');
        }
    }

    public function annuler(Don $don): RedirectResponse
    {
        abort_unless($don->utilisateur_id === Auth::id(), 403);
        abort_unless($don->statut === Don::STATUT_EN_ATTENTE, 403);

        try {
            $don->update(['statut' => Don::STATUT_ANNULE]);

            return redirect()->route('membre.dons.index')
                ->with('status', 'Don annulé.');
        } catch (\Throwable $e) {
            Log::error('Erreur annulation don #'.$don->id.' : '.$e->getMessage());

            return back()->with('error', 'Une erreur est survenue.');
        }
    }
}
