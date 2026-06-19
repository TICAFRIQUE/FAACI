<?php

namespace App\Http\Controllers\Admin;

use App\Http\Controllers\Controller;
use App\Http\Requests\Admin\AdministrateurRequest;
use App\Http\Requests\Admin\AdministrateurUpdateRequest;
use App\Http\Requests\Admin\MembreRoleRequest;
use App\Http\Requests\Admin\MembreSuspensionRequest;
use App\Mail\CompteAdministrateurCree;
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
use Illuminate\Support\Str;
use Illuminate\View\View;
use Yajra\DataTables\Facades\DataTables;

class AdministrateurController extends Controller
{
    public function index(Request $request): View|\Illuminate\Http\JsonResponse
    {
        if ($request->ajax()) {
            $query = User::role(['admin', 'super_admin']);

            if ($request->filled('statut') && $request->input('statut') !== 'tous') {
                $query->where('statut', $request->input('statut'));
            }

            return DataTables::of($query)
                ->addColumn('membre', fn (User $u) => view('admin.membres._cellule-membre', ['membre' => $u])->render())
                ->filterColumn('nom', function ($query, $keyword) {
                    $query->where(function ($q) use ($keyword) {
                        $q->where('prenom', 'like', "%{$keyword}%")
                            ->orWhere('nom', 'like', "%{$keyword}%")
                            ->orWhere('email', 'like', "%{$keyword}%")
                            ->orWhere('telephone', 'like', "%{$keyword}%");
                    });
                })
                ->addColumn('roles', fn (User $u) => $u->getRoleNames()->implode(', '))
                ->addColumn('statut_badge', fn (User $u) => view('admin.membres._badge-statut', ['statut' => $u->statut])->render())
                ->editColumn('created_at', fn (User $u) => $u->created_at->format('d/m/Y'))
                ->addColumn('actions', fn (User $u) => view('admin.administrateurs._actions', ['utilisateur' => $u])->render())
                ->rawColumns(['membre', 'statut_badge', 'actions'])
                ->make(true);
        }

        $base = User::role(['admin', 'super_admin']);

        $compteurs = [
            'tous'               => (clone $base)->count(),
            User::STATUT_ACTIF   => (clone $base)->where('statut', User::STATUT_ACTIF)->count(),
            User::STATUT_SUSPENDU => (clone $base)->where('statut', User::STATUT_SUSPENDU)->count(),
            User::STATUT_INACTIF  => (clone $base)->where('statut', User::STATUT_INACTIF)->count(),
        ];

        return view('admin.administrateurs.index', compact('compteurs'));
    }

    public function create(): View
    {
        return view('admin.administrateurs.create');
    }

    public function store(AdministrateurRequest $request): RedirectResponse
    {
        try {
            $utilisateur = User::create([
                'prenom'    => $request->validated('prenom'),
                'nom'       => $request->validated('nom'),
                'email'     => $request->validated('email'),
                'telephone' => $request->validated('telephone'),
                'password'  => Str::password(32),
                'statut'    => User::STATUT_ACTIF,
            ]);

            $utilisateur->email_verified_at = now();
            $utilisateur->date_validation   = now();
            $utilisateur->valide_par        = auth()->id();
            $utilisateur->save();

            $utilisateur->assignRole($request->validated('role'));

            $token = Password::createToken($utilisateur);
            $lien  = route('password.reset', ['token' => $token, 'email' => $utilisateur->email]);

            Mail::to($utilisateur->email)->queue(new CompteAdministrateurCree($utilisateur, $lien));

            return redirect()
                ->route('admin.administrateurs.show', $utilisateur)
                ->with('status', 'Le compte administrateur a été créé. Un e-mail a été envoyé pour définir le mot de passe.');
        } catch (\Throwable $e) {
            Log::error("Erreur lors de la création de l'administrateur : ".$e->getMessage());

            return back()->withInput()->with('error', 'Une erreur est survenue lors de la création du compte.');
        }
    }

    public function show(User $utilisateur): View|RedirectResponse
    {
        if (! $utilisateur->hasAnyRole(['admin', 'super_admin'])) {
            return redirect()->route('admin.membres.show', $utilisateur);
        }

        $historique = $utilisateur->activities()->latest()->get();

        return view('admin.administrateurs.show', compact('utilisateur', 'historique'));
    }

    public function suspendre(MembreSuspensionRequest $request, User $utilisateur): RedirectResponse
    {
        try {
            if ($utilisateur->id === auth()->id()) {
                return back()->with('error', 'Vous ne pouvez pas suspendre votre propre compte.');
            }

            if ($utilisateur->statut !== User::STATUT_ACTIF) {
                return back()->with('error', 'Seul un administrateur actif peut être suspendu.');
            }

            $utilisateur->statut            = User::STATUT_SUSPENDU;
            $utilisateur->motif_suspension  = $request->validated('motif_suspension');
            $utilisateur->date_suspension   = now();
            $utilisateur->save();

            Mail::to($utilisateur->email)->queue(new CompteSuspendu($utilisateur));

            return redirect()
                ->route('admin.administrateurs.show', $utilisateur)
                ->with('status', "L'administrateur a été suspendu.");
        } catch (\Throwable $e) {
            Log::error("Erreur lors de la suspension de l'administrateur #{$utilisateur->id} : ".$e->getMessage());

            return back()->with('error', 'Une erreur est survenue lors de la suspension.');
        }
    }

    public function reactiver(User $utilisateur): RedirectResponse
    {
        try {
            if (! in_array($utilisateur->statut, [User::STATUT_SUSPENDU, User::STATUT_INACTIF], true)) {
                return back()->with('error', 'Seul un administrateur suspendu ou inactif peut être réactivé.');
            }

            $utilisateur->statut           = User::STATUT_ACTIF;
            $utilisateur->motif_suspension = null;
            $utilisateur->save();

            Mail::to($utilisateur->email)->queue(new CompteReactive($utilisateur));

            return redirect()
                ->route('admin.administrateurs.show', $utilisateur)
                ->with('status', "L'administrateur a été réactivé.");
        } catch (\Throwable $e) {
            Log::error("Erreur lors de la réactivation de l'administrateur #{$utilisateur->id} : ".$e->getMessage());

            return back()->with('error', 'Une erreur est survenue lors de la réactivation.');
        }
    }

    public function desactiver(User $utilisateur): RedirectResponse
    {
        try {
            if ($utilisateur->id === auth()->id()) {
                return back()->with('error', 'Vous ne pouvez pas désactiver votre propre compte.');
            }

            if ($utilisateur->statut !== User::STATUT_ACTIF) {
                return back()->with('error', 'Seul un administrateur actif peut être désactivé.');
            }

            $utilisateur->statut             = User::STATUT_INACTIF;
            $utilisateur->date_inactivation  = now();
            $utilisateur->save();

            return redirect()
                ->route('admin.administrateurs.show', $utilisateur)
                ->with('status', "L'administrateur a été désactivé.");
        } catch (\Throwable $e) {
            Log::error("Erreur lors de la désactivation de l'administrateur #{$utilisateur->id} : ".$e->getMessage());

            return back()->with('error', 'Une erreur est survenue lors de la désactivation.');
        }
    }

    public function role(MembreRoleRequest $request, User $utilisateur): RedirectResponse
    {
        try {
            if ($utilisateur->id === auth()->id()) {
                return back()->with('error', 'Vous ne pouvez pas modifier votre propre rôle.');
            }

            $utilisateur->syncRoles([$request->validated('role')]);

            return redirect()
                ->route('admin.administrateurs.show', $utilisateur)
                ->with('status', 'Le rôle a été mis à jour.');
        } catch (\Throwable $e) {
            Log::error("Erreur lors de la mise à jour du rôle de l'administrateur #{$utilisateur->id} : ".$e->getMessage());

            return back()->with('error', 'Une erreur est survenue lors de la mise à jour du rôle.');
        }
    }

    public function edit(User $utilisateur): View|RedirectResponse
    {
        if (! $utilisateur->hasAnyRole(['admin', 'super_admin'])) {
            return redirect()->route('admin.membres.edit', $utilisateur);
        }

        return view('admin.administrateurs.edit', compact('utilisateur'));
    }

    public function update(AdministrateurUpdateRequest $request, User $utilisateur): RedirectResponse
    {
        try {
            $utilisateur->prenom    = $request->validated('prenom');
            $utilisateur->nom       = $request->validated('nom');
            $utilisateur->email     = $request->validated('email');
            $utilisateur->telephone = $request->validated('telephone');
            $utilisateur->save();

            return redirect()
                ->route('admin.administrateurs.show', $utilisateur)
                ->with('status', 'Les informations ont été mises à jour.');
        } catch (\Throwable $e) {
            Log::error("Erreur lors de la modification de l'administrateur #{$utilisateur->id} : ".$e->getMessage());

            return back()->withInput()->with('error', 'Une erreur est survenue lors de la mise à jour.');
        }
    }

    public function reinitialiserMotDePasse(User $utilisateur): RedirectResponse
    {
        try {
            $token = Password::createToken($utilisateur);
            $lien  = route('password.reset', ['token' => $token, 'email' => $utilisateur->email]);

            Mail::to($utilisateur->email)->queue(new LienReinitialisationMotDePasse($utilisateur, $lien));

            return redirect()
                ->route('admin.administrateurs.show', $utilisateur)
                ->with('status', "Un e-mail de réinitialisation a été envoyé à {$utilisateur->email}.")
                ->with('lien_reset', $lien);
        } catch (\Throwable $e) {
            Log::error("Erreur lors de la réinitialisation du mot de passe de l'administrateur #{$utilisateur->id} : ".$e->getMessage());

            return back()->with('error', 'Une erreur est survenue lors de la réinitialisation.');
        }
    }

    public function destroy(User $utilisateur): RedirectResponse
    {
        try {
            if ($utilisateur->id === auth()->id()) {
                return back()->with('error', 'Vous ne pouvez pas supprimer votre propre compte.');
            }

            $email = $utilisateur->email;

            // Nullifier les références avant suppression
            User::where('valide_par', $utilisateur->id)->update(['valide_par' => null]);

            $utilisateur->roles()->detach();
            $utilisateur->activities()->delete();
            DB::table('password_reset_tokens')->where('email', $email)->delete();
            $utilisateur->delete();

            return redirect()
                ->route('admin.administrateurs.index')
                ->with('status', "Le compte de {$email} a été supprimé.");
        } catch (\Throwable $e) {
            Log::error("Erreur lors de la suppression de l'administrateur #{$utilisateur->id} : ".$e->getMessage());

            return back()->with('error', 'Une erreur est survenue lors de la suppression.');
        }
    }
}
