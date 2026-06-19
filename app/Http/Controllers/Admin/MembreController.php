<?php

namespace App\Http\Controllers\Admin;

use App\Http\Controllers\Controller;
use App\Http\Requests\Admin\MembreRefusRequest;
use App\Http\Requests\Admin\MembreRoleRequest;
use App\Http\Requests\Admin\MembreSuspensionRequest;
use App\Http\Requests\Admin\MembreUpdateRequest;
use App\Mail\AdhesionRefusee;
use App\Mail\AdhesionValidee;
use App\Mail\CompteReactive;
use App\Mail\CompteSuspendu;
use App\Mail\LienReinitialisationMotDePasse;
use App\Models\User;
use Illuminate\Http\RedirectResponse;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Log;
use Illuminate\Support\Facades\Mail;
use Illuminate\Support\Facades\Password;
use Illuminate\View\View;
use Yajra\DataTables\Facades\DataTables;

class MembreController extends Controller
{
    /**
     * Liste des membres avec filtres par statut (Yajra DataTables).
     */
    public function index(Request $request): View|\Illuminate\Http\JsonResponse
    {
        if ($request->ajax()) {
            $query = User::role('membre');

            if ($request->filled('statut') && $request->get('statut') !== 'tous') {
                $query->where('statut', $request->get('statut'));
            }

            return DataTables::of($query)
                ->addColumn('membre', fn (User $membre) => view('admin.membres._cellule-membre', compact('membre'))->render())
                ->filterColumn('nom', function ($query, $keyword) {
                    $query->where(function ($q) use ($keyword) {
                        $q->where('prenom', 'like', "%{$keyword}%")
                            ->orWhere('nom', 'like', "%{$keyword}%")
                            ->orWhere('email', 'like', "%{$keyword}%")
                            ->orWhere('telephone', 'like', "%{$keyword}%");
                    });
                })
                ->addColumn('roles', fn (User $membre) => $membre->getRoleNames()->implode(', '))
                ->addColumn('statut_badge', fn (User $membre) => view('admin.membres._badge-statut', ['statut' => $membre->statut])->render())
                ->editColumn('created_at', fn (User $membre) => $membre->created_at->format('d/m/Y'))
                ->addColumn('actions', fn (User $membre) => view('admin.membres._actions', compact('membre'))->render())
                ->rawColumns(['membre', 'statut_badge', 'actions'])
                ->make(true);
        }

        $base = User::role('membre');

        $compteurs = [
            'tous' => (clone $base)->count(),
            User::STATUT_EN_ATTENTE => (clone $base)->where('statut', User::STATUT_EN_ATTENTE)->count(),
            User::STATUT_ACTIF => (clone $base)->where('statut', User::STATUT_ACTIF)->count(),
            User::STATUT_SUSPENDU => (clone $base)->where('statut', User::STATUT_SUSPENDU)->count(),
            User::STATUT_INACTIF => (clone $base)->where('statut', User::STATUT_INACTIF)->count(),
            User::STATUT_REJETE => (clone $base)->where('statut', User::STATUT_REJETE)->count(),
        ];

        return view('admin.membres.index', compact('compteurs'));
    }

    /**
     * Détail d'un membre : profil, statut, rôles et historique des changements.
     */
    public function show(User $membre): View|RedirectResponse
    {
        if ($membre->hasAnyRole(['admin', 'super_admin'])) {
            return redirect()->route('admin.administrateurs.show', $membre);
        }

        $historique = $membre->activities()->latest()->get();

        return view('admin.membres.show', compact('membre', 'historique'));
    }

    /**
     * Valide une demande d'adhésion : en_attente → actif.
     */
    public function valider(User $membre): RedirectResponse
    {
        try {
            if ($membre->statut !== User::STATUT_EN_ATTENTE) {
                return back()->with('error', "Ce membre n'est pas en attente de validation.");
            }

            $membre->statut = User::STATUT_ACTIF;
            $membre->date_validation = now();
            $membre->valide_par = auth()->id();
            $membre->save();

            Mail::to($membre->email)->queue(new AdhesionValidee($membre));

            return redirect()->route('admin.membres.show', $membre)->with('status', 'La demande a été validée. Le membre est désormais actif.');
        } catch (\Throwable $e) {
            Log::error("Erreur lors de la validation du membre #{$membre->id} : ".$e->getMessage());

            return back()->with('error', "Une erreur est survenue lors de la validation.");
        }
    }

    /**
     * Refuse une demande d'adhésion : en_attente → rejete (avec motif).
     */
    public function refuser(MembreRefusRequest $request, User $membre): RedirectResponse
    {
        try {
            if ($membre->statut !== User::STATUT_EN_ATTENTE) {
                return back()->with('error', "Ce membre n'est pas en attente de validation.");
            }

            $membre->statut = User::STATUT_REJETE;
            $membre->motif_rejet = $request->validated('motif_rejet');
            $membre->save();

            Mail::to($membre->email)->queue(new AdhesionRefusee($membre));

            return redirect()->route('admin.membres.show', $membre)->with('status', 'La demande a été refusée.');
        } catch (\Throwable $e) {
            Log::error("Erreur lors du refus du membre #{$membre->id} : ".$e->getMessage());

            return back()->with('error', 'Une erreur est survenue lors du refus.');
        }
    }

    /**
     * Suspend un membre actif : actif → suspendu (avec motif).
     */
    public function suspendre(MembreSuspensionRequest $request, User $membre): RedirectResponse
    {
        try {
            if ($membre->statut !== User::STATUT_ACTIF) {
                return back()->with('error', 'Seul un membre actif peut être suspendu.');
            }

            $membre->statut = User::STATUT_SUSPENDU;
            $membre->motif_suspension = $request->validated('motif_suspension');
            $membre->date_suspension = now();
            $membre->save();

            Mail::to($membre->email)->queue(new CompteSuspendu($membre));

            return redirect()->route('admin.membres.show', $membre)->with('status', 'Le membre a été suspendu.');
        } catch (\Throwable $e) {
            Log::error("Erreur lors de la suspension du membre #{$membre->id} : ".$e->getMessage());

            return back()->with('error', 'Une erreur est survenue lors de la suspension.');
        }
    }

    /**
     * Réactive un membre suspendu ou inactif : suspendu|inactif → actif.
     */
    public function reactiver(User $membre): RedirectResponse
    {
        try {
            if (! in_array($membre->statut, [User::STATUT_SUSPENDU, User::STATUT_INACTIF], true)) {
                return back()->with('error', 'Seul un membre suspendu ou inactif peut être réactivé.');
            }

            $membre->statut = User::STATUT_ACTIF;
            $membre->motif_suspension = null;
            $membre->save();

            Mail::to($membre->email)->queue(new CompteReactive($membre));

            return redirect()->route('admin.membres.show', $membre)->with('status', 'Le membre a été réactivé.');
        } catch (\Throwable $e) {
            Log::error("Erreur lors de la réactivation du membre #{$membre->id} : ".$e->getMessage());

            return back()->with('error', 'Une erreur est survenue lors de la réactivation.');
        }
    }

    /**
     * Désactive un membre actif : actif → inactif.
     */
    public function desactiver(User $membre): RedirectResponse
    {
        try {
            if ($membre->statut !== User::STATUT_ACTIF) {
                return back()->with('error', 'Seul un membre actif peut être désactivé.');
            }

            $membre->statut = User::STATUT_INACTIF;
            $membre->date_inactivation = now();
            $membre->save();

            return redirect()->route('admin.membres.show', $membre)->with('status', 'Le membre a été désactivé.');
        } catch (\Throwable $e) {
            Log::error("Erreur lors de la désactivation du membre #{$membre->id} : ".$e->getMessage());

            return back()->with('error', 'Une erreur est survenue lors de la désactivation.');
        }
    }

    /**
     * Attribue ou retire un rôle (admin, super_admin) — réservé au super_admin.
     */
    public function role(MembreRoleRequest $request, User $membre): RedirectResponse
    {
        try {
            if ($membre->id === auth()->id()) {
                return back()->with('error', 'Vous ne pouvez pas modifier votre propre rôle.');
            }

            $membre->syncRoles([$request->validated('role')]);

            return redirect()->route('admin.membres.show', $membre)->with('status', 'Le rôle du membre a été mis à jour.');
        } catch (\Throwable $e) {
            Log::error("Erreur lors de la mise à jour du rôle du membre #{$membre->id} : ".$e->getMessage());

            return back()->with('error', 'Une erreur est survenue lors de la mise à jour du rôle.');
        }
    }

    public function edit(User $membre): View|RedirectResponse
    {
        if ($membre->hasAnyRole(['admin', 'super_admin'])) {
            return redirect()->route('admin.administrateurs.edit', $membre);
        }

        return view('admin.membres.edit', compact('membre'));
    }

    public function update(MembreUpdateRequest $request, User $membre): RedirectResponse
    {
        try {
            $membre->prenom    = $request->validated('prenom');
            $membre->nom       = $request->validated('nom');
            $membre->email     = $request->validated('email');
            $membre->telephone = $request->validated('telephone');
            $membre->save();

            return redirect()->route('admin.membres.show', $membre)->with('status', 'Les informations ont été mises à jour.');
        } catch (\Throwable $e) {
            Log::error("Erreur lors de la modification du membre #{$membre->id} : ".$e->getMessage());

            return back()->withInput()->with('error', 'Une erreur est survenue lors de la mise à jour.');
        }
    }

    public function reinitialiserMotDePasse(User $membre): RedirectResponse
    {
        try {
            $token = Password::createToken($membre);
            $lien  = route('password.reset', ['token' => $token, 'email' => $membre->email]);

            Mail::to($membre->email)->queue(new LienReinitialisationMotDePasse($membre, $lien));

            return redirect()
                ->route('admin.membres.show', $membre)
                ->with('status', "Un e-mail de réinitialisation a été envoyé à {$membre->email}.")
                ->with('lien_reset', $lien);
        } catch (\Throwable $e) {
            Log::error("Erreur lors de la réinitialisation du mot de passe du membre #{$membre->id} : ".$e->getMessage());

            return back()->with('error', 'Une erreur est survenue lors de la réinitialisation.');
        }
    }

    public function destroy(User $membre): RedirectResponse
    {
        try {
            $email = $membre->email;

            User::where('valide_par', $membre->id)->update(['valide_par' => null]);

            $membre->roles()->detach();
            $membre->activities()->delete();
            DB::table('password_reset_tokens')->where('email', $email)->delete();
            $membre->delete();

            return redirect()
                ->route('admin.membres.index')
                ->with('status', "Le compte de {$email} a été supprimé.");
        } catch (\Throwable $e) {
            Log::error("Erreur lors de la suppression du membre #{$membre->id} : ".$e->getMessage());

            return back()->with('error', 'Une erreur est survenue lors de la suppression.');
        }
    }
}
