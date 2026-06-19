@extends('layouts.admin')

@section('title', $utilisateur->nom_complet)
@section('page-title', 'Détail de l\'administrateur')

@php
    $userClass = \App\Models\User::class;

    $labelsChamps = [
        'prenom'            => 'Prénom',
        'nom'               => 'Nom',
        'email'             => 'Email',
        'telephone'         => 'Téléphone',
        'statut'            => 'Statut',
        'motif_suspension'  => 'Motif de suspension',
    ];
@endphp

@section('content')
    <a href="{{ route('admin.administrateurs.index') }}" class="d-inline-flex align-items-center gap-1 text-decoration-none mb-3">
        <i class="bi bi-arrow-left"></i> Retour à la liste
    </a>

    @if (session('status'))
        <div class="alert alert-success">{{ session('status') }}</div>
    @endif
    @if (session('lien_reset'))
        <div class="alert alert-info">
            <strong>Lien de réinitialisation :</strong>
            <a href="{{ session('lien_reset') }}" target="_blank" class="text-break">{{ session('lien_reset') }}</a>
            <div class="small text-muted mt-1">Valable {{ config('auth.passwords.users.expire', 60) }} minutes. Partagez-le manuellement si l'e-mail n'est pas reçu.</div>
        </div>
    @endif
    @if (session('error'))
        <div class="alert alert-danger">{{ session('error') }}</div>
    @endif

    <div class="row g-4">
        {{-- Colonne principale --}}
        <div class="col-lg-8">
            {{-- Carte profil --}}
            <div class="card border-0 shadow-sm mb-4">
                <div class="card-body">
                    <div class="d-flex justify-content-between align-items-start flex-wrap gap-3">
                        <div class="d-flex align-items-center gap-3">
                            <div class="bg-faaci-gray rounded-circle d-flex align-items-center justify-content-center fw-semibold text-faaci-navy flex-shrink-0"
                                 style="width:64px; height:64px; font-size:1.3rem;">
                                {{ mb_strtoupper(mb_substr($utilisateur->prenom, 0, 1).mb_substr($utilisateur->nom, 0, 1)) }}
                            </div>
                            <div>
                                <h2 class="h5 fw-bold mb-1">{{ $utilisateur->nom_complet }}</h2>
                                <div class="text-muted small">{{ $utilisateur->email }}</div>
                                <div class="text-muted small">{{ $utilisateur->telephone ?? 'Téléphone non renseigné' }}</div>
                            </div>
                        </div>
                        <div class="text-end">
                            @include('admin.membres._badge-statut', ['statut' => $utilisateur->statut])
                            <div class="text-muted small mt-1">Créé le {{ $utilisateur->created_at->format('d/m/Y') }}</div>
                            <div class="text-muted small">Rôle : {{ $utilisateur->getRoleNames()->implode(', ') ?: '—' }}</div>
                        </div>
                    </div>

                    @if ($utilisateur->statut === $userClass::STATUT_SUSPENDU && $utilisateur->motif_suspension)
                        <div class="alert alert-secondary mt-3 mb-0">
                            <strong>Motif de suspension :</strong> {{ $utilisateur->motif_suspension }}
                        </div>
                    @endif

                    @if ($utilisateur->validateur && $utilisateur->date_validation)
                        <div class="text-muted small mt-3">
                            Créé par {{ $utilisateur->validateur->nom_complet }}
                            le {{ $utilisateur->date_validation->format('d/m/Y à H:i') }}
                        </div>
                    @endif

                    {{-- Actions super_admin : modifier, réinitialiser mot de passe, supprimer --}}
                    <div class="d-flex flex-wrap gap-2 mt-4 pt-3 border-top">
                        <a href="{{ route('admin.administrateurs.edit', $utilisateur) }}" class="btn btn-outline-primary btn-sm">
                            <i class="bi bi-pencil me-1"></i>Modifier
                        </a>
                        <form method="POST" action="{{ route('admin.administrateurs.reinitialiser-mot-de-passe', $utilisateur) }}"
                              onsubmit="return confirm('Envoyer un lien de réinitialisation du mot de passe ?');">
                            @csrf
                            <button type="submit" class="btn btn-outline-warning btn-sm">
                                <i class="bi bi-key me-1"></i>Réinitialiser le mot de passe
                            </button>
                        </form>
                        @if ($utilisateur->id !== auth()->id())
                            <form method="POST" action="{{ route('admin.administrateurs.destroy', $utilisateur) }}"
                                  onsubmit="return confirm('Supprimer définitivement ce compte ? Cette action est irréversible.');">
                                @csrf
                                @method('DELETE')
                                <button type="submit" class="btn btn-outline-danger btn-sm">
                                    <i class="bi bi-trash me-1"></i>Supprimer
                                </button>
                            </form>
                        @endif
                    </div>
                </div>
            </div>

            @if ($utilisateur->id !== auth()->id())
                {{-- Carte actions --}}
                <div class="card border-0 shadow-sm mb-4">
                    <div class="card-body">
                        <h3 class="h6 fw-bold mb-3">Actions</h3>
                        <div class="d-flex flex-wrap gap-2">
                            @if ($utilisateur->statut === $userClass::STATUT_ACTIF)
                                <button type="button" class="btn btn-secondary" data-bs-toggle="modal" data-bs-target="#modalSuspension">
                                    <i class="bi bi-pause-circle me-1"></i>Suspendre
                                </button>
                                <form method="POST" action="{{ route('admin.administrateurs.desactiver', $utilisateur) }}"
                                      onsubmit="return confirm('Désactiver ce compte ?');">
                                    @csrf
                                    @method('PATCH')
                                    <button type="submit" class="btn btn-outline-dark">
                                        <i class="bi bi-slash-circle me-1"></i>Désactiver
                                    </button>
                                </form>
                            @elseif (in_array($utilisateur->statut, [$userClass::STATUT_SUSPENDU, $userClass::STATUT_INACTIF]))
                                <form method="POST" action="{{ route('admin.administrateurs.reactiver', $utilisateur) }}"
                                      onsubmit="return confirm('Réactiver ce compte ?');">
                                    @csrf
                                    @method('PATCH')
                                    <button type="submit" class="btn btn-success">
                                        <i class="bi bi-arrow-counterclockwise me-1"></i>Réactiver
                                    </button>
                                </form>
                            @else
                                <span class="text-muted small">Aucune action disponible pour ce statut.</span>
                            @endif
                        </div>
                    </div>
                </div>

                {{-- Carte rôle --}}
                <div class="card border-0 shadow-sm mb-4">
                    <div class="card-body">
                        <h3 class="h6 fw-bold mb-3">Rôle</h3>
                        <form method="POST" action="{{ route('admin.administrateurs.role', $utilisateur) }}"
                              class="d-flex gap-2 flex-wrap">
                            @csrf
                            @method('PATCH')
                            <select name="role" class="form-select" style="max-width:220px;">
                                @foreach (['membre', 'admin', 'super_admin'] as $role)
                                    <option value="{{ $role }}" @selected($utilisateur->hasRole($role))>
                                        {{ ucfirst(str_replace('_', ' ', $role)) }}
                                    </option>
                                @endforeach
                            </select>
                            <button type="submit" class="btn btn-faaci-navy">Mettre à jour</button>
                        </form>
                        <p class="text-muted small mt-2 mb-0">
                            Attribuer le rôle <em>membre</em> déplacera cet utilisateur vers la liste des membres.
                        </p>
                    </div>
                </div>
            @else
                <div class="alert alert-info">
                    <i class="bi bi-info-circle me-1"></i>
                    Vous ne pouvez pas modifier le statut ou le rôle de votre propre compte.
                </div>
            @endif
        </div>

        {{-- Colonne historique --}}
        <div class="col-lg-4">
            <div class="card border-0 shadow-sm">
                <div class="card-body">
                    <h3 class="h6 fw-bold mb-3">Historique</h3>
                    @forelse ($historique as $activite)
                        <div class="border-bottom pb-2 mb-2">
                            <div class="small text-muted mb-1">
                                {{ $activite->created_at->format('d/m/Y à H:i') }}
                                — {{ $activite->causer?->nom_complet ?? 'Système' }}
                            </div>
                            @foreach (($activite->properties['attributes'] ?? []) as $champ => $valeur)
                                @continue($champ !== 'statut' && ($valeur === null || $valeur === ''))
                                <div class="small">
                                    <strong>{{ $labelsChamps[$champ] ?? $champ }}</strong> :
                                    @if ($champ === 'statut')
                                        @php $ancienneValeur = $activite->properties['old'][$champ] ?? null; @endphp
                                        @if ($ancienneValeur)
                                            @include('admin.membres._badge-statut', ['statut' => $ancienneValeur])
                                            <i class="bi bi-arrow-right small"></i>
                                        @endif
                                        @include('admin.membres._badge-statut', ['statut' => $valeur])
                                    @else
                                        {{ $valeur }}
                                    @endif
                                </div>
                            @endforeach
                        </div>
                    @empty
                        <p class="text-muted small mb-0">Aucun changement enregistré pour le moment.</p>
                    @endforelse
                </div>
            </div>
        </div>
    </div>

    @if ($utilisateur->statut === $userClass::STATUT_ACTIF && $utilisateur->id !== auth()->id())
        <div class="modal fade" id="modalSuspension" tabindex="-1" aria-hidden="true">
            <div class="modal-dialog">
                <div class="modal-content">
                    <form method="POST" action="{{ route('admin.administrateurs.suspendre', $utilisateur) }}">
                        @csrf
                        @method('PATCH')
                        <div class="modal-header">
                            <h5 class="modal-title">Suspendre ce compte</h5>
                            <button type="button" class="btn-close" data-bs-dismiss="modal" aria-label="Fermer"></button>
                        </div>
                        <div class="modal-body">
                            <label class="form-label">Motif de la suspension</label>
                            <textarea name="motif_suspension" class="form-control" rows="3" required></textarea>
                        </div>
                        <div class="modal-footer">
                            <button type="button" class="btn btn-outline-secondary" data-bs-dismiss="modal">Annuler</button>
                            <button type="submit" class="btn btn-secondary">Suspendre</button>
                        </div>
                    </form>
                </div>
            </div>
        </div>
    @endif
@endsection
