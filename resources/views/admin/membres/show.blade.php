@extends('layouts.admin')

@section('title', $membre->nom_complet)
@section('page-title', 'Détail du membre')

@php
    $userClass = \App\Models\User::class;

    $labelsChamps = [
        'prenom' => 'Prénom',
        'nom' => 'Nom',
        'email' => 'Email',
        'telephone' => 'Téléphone',
        'statut' => 'Statut',
        'motif_rejet' => 'Motif de refus',
        'motif_suspension' => 'Motif de suspension',
    ];
@endphp

@section('content')
    <div class="d-flex align-items-center justify-content-between mb-3">
        <a href="{{ route('admin.membres.index') }}" class="d-inline-flex align-items-center gap-1 text-decoration-none">
            <i class="bi bi-arrow-left"></i> Retour à la liste
        </a>
        <a href="{{ route('admin.cotisations.membre', $membre) }}"
           class="btn btn-faaci-navy btn-sm">
            <i class="bi bi-wallet2 me-1"></i> Cotisations
        </a>
    </div>

    @if (session('status'))
        <div class="alert alert-success">{{ session('status') }}</div>
    @endif
    @if (session('lien_reset'))
        <div class="alert alert-info">
            <strong>Lien de réinitialisation :</strong>
            <a href="{{ session('lien_reset') }}" target="_blank" class="text-break">{{ session('lien_reset') }}</a>
            <div class="small text-muted mt-1">Valable {{ config('auth.passwords.users.expire', 60) }} minutes.</div>
        </div>
    @endif
    @if (session('error'))
        <div class="alert alert-danger">{{ session('error') }}</div>
    @endif

    <div class="row g-4">
        <div class="col-lg-8">
            <div class="card border-0 shadow-sm mb-4">
                <div class="card-body">
                    <div class="d-flex justify-content-between align-items-start flex-wrap gap-3">
                        <div class="d-flex align-items-center gap-3">
                            <div class="bg-faaci-gray rounded-circle d-flex align-items-center justify-content-center fw-semibold text-faaci-navy flex-shrink-0" style="width:64px; height:64px; font-size:1.3rem;">
                                {{ mb_strtoupper(mb_substr($membre->prenom, 0, 1).mb_substr($membre->nom, 0, 1)) }}
                            </div>
                            <div>
                                <h2 class="h5 fw-bold mb-1">{{ $membre->nom_complet }}</h2>
                                <div class="text-muted small">{{ $membre->email }}</div>
                                <div class="text-muted small">{{ $membre->telephone ?? 'Téléphone non renseigné' }}</div>
                            </div>
                        </div>
                        <div class="text-end">
                            @include('admin.membres._badge-statut', ['statut' => $membre->statut])
                            <div class="text-muted small mt-1">Inscrit le {{ $membre->created_at->format('d/m/Y') }}</div>
                            <div class="text-muted small">Rôle(s) : {{ $membre->getRoleNames()->implode(', ') ?: '—' }}</div>
                        </div>
                    </div>

                    @if ($membre->statut === $userClass::STATUT_SUSPENDU && $membre->motif_suspension)
                        <div class="alert alert-secondary mt-3 mb-0">
                            <strong>Motif de suspension :</strong> {{ $membre->motif_suspension }}
                        </div>
                    @endif

                    @if ($membre->statut === $userClass::STATUT_REJETE && $membre->motif_rejet)
                        <div class="alert alert-danger mt-3 mb-0">
                            <strong>Motif de refus :</strong> {{ $membre->motif_rejet }}
                        </div>
                    @endif

                    @if ($membre->validateur && $membre->date_validation)
                        <div class="text-muted small mt-3">
                            Validé par {{ $membre->validateur->nom_complet }} le {{ $membre->date_validation->format('d/m/Y à H:i') }}
                        </div>
                    @endif

                    @hasrole('super_admin')
                    <div class="d-flex flex-wrap gap-2 mt-4 pt-3 border-top">
                        <a href="{{ route('admin.membres.edit', $membre) }}" class="btn btn-outline-primary btn-sm">
                            <i class="bi bi-pencil me-1"></i>Modifier
                        </a>
                        <form method="POST" action="{{ route('admin.membres.reinitialiser-mot-de-passe', $membre) }}"
                              data-confirm="Envoyer un lien de réinitialisation du mot de passe ?">
                            @csrf
                            <button type="submit" class="btn btn-outline-warning btn-sm">
                                <i class="bi bi-key me-1"></i>Réinitialiser le mot de passe
                            </button>
                        </form>
                        <form method="POST" action="{{ route('admin.membres.destroy', $membre) }}"
                              data-confirm="Supprimer définitivement ce membre ? Cette action est irréversible.">
                            @csrf
                            @method('DELETE')
                            <button type="submit" class="btn btn-outline-danger btn-sm">
                                <i class="bi bi-trash me-1"></i>Supprimer
                            </button>
                        </form>
                    </div>
                    @endhasrole
                </div>
            </div>

            <div class="card border-0 shadow-sm mb-4">
                <div class="card-body">
                    <h3 class="h6 fw-bold mb-3">Actions</h3>
                    <div class="d-flex flex-wrap gap-2">
                        @if ($membre->statut === $userClass::STATUT_EN_ATTENTE)
                            <form method="POST" action="{{ route('admin.membres.valider', $membre) }}" data-confirm="Valider cette demande d'adhésion ?">
                                @csrf
                                @method('PATCH')
                                <button type="submit" class="btn btn-success"><i class="bi bi-check-lg me-1"></i>Valider</button>
                            </form>
                            <button type="button" class="btn btn-danger" data-bs-toggle="modal" data-bs-target="#modalRefus">
                                <i class="bi bi-x-lg me-1"></i>Refuser
                            </button>
                        @elseif ($membre->statut === $userClass::STATUT_ACTIF)
                            <button type="button" class="btn btn-secondary" data-bs-toggle="modal" data-bs-target="#modalSuspension">
                                <i class="bi bi-pause-circle me-1"></i>Suspendre
                            </button>
                            <form method="POST" action="{{ route('admin.membres.desactiver', $membre) }}" data-confirm="Désactiver ce membre ?">
                                @csrf
                                @method('PATCH')
                                <button type="submit" class="btn btn-outline-dark"><i class="bi bi-slash-circle me-1"></i>Désactiver</button>
                            </form>
                        @elseif (in_array($membre->statut, [$userClass::STATUT_SUSPENDU, $userClass::STATUT_INACTIF]))
                            <form method="POST" action="{{ route('admin.membres.reactiver', $membre) }}" data-confirm="Réactiver ce membre ?">
                                @csrf
                                @method('PATCH')
                                <button type="submit" class="btn btn-success"><i class="bi bi-arrow-counterclockwise me-1"></i>Réactiver</button>
                            </form>
                        @else
                            <span class="text-muted small">Aucune action disponible pour ce statut.</span>
                        @endif
                    </div>
                </div>
            </div>

            @hasrole('super_admin')
                @if ($membre->id !== auth()->id())
                    <div class="card border-0 shadow-sm mb-4">
                        <div class="card-body">
                            <h3 class="h6 fw-bold mb-3">Rôle</h3>
                            <form method="POST" action="{{ route('admin.membres.role', $membre) }}" class="d-flex gap-2 flex-wrap">
                                @csrf
                                @method('PATCH')
                                <select name="role" class="form-select" style="max-width:220px;">
                                    @foreach (['membre', 'admin', 'super_admin'] as $role)
                                        <option value="{{ $role }}" @selected($membre->hasRole($role))>{{ ucfirst(str_replace('_', ' ', $role)) }}</option>
                                    @endforeach
                                </select>
                                <button type="submit" class="btn btn-faaci-navy">Mettre à jour</button>
                            </form>
                        </div>
                    </div>
                @endif
            @endhasrole
        </div>

        <div class="col-lg-4">
            <div class="card border-0 shadow-sm">
                <div class="card-body">
                    <h3 class="h6 fw-bold mb-3">Historique</h3>
                    @forelse ($historique as $activite)
                        <div class="border-bottom pb-2 mb-2">
                            <div class="small text-muted mb-1">
                                {{ $activite->created_at->format('d/m/Y à H:i') }} — {{ $activite->causer?->nom_complet ?? 'Système' }}
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

    @if ($membre->statut === $userClass::STATUT_EN_ATTENTE)
        <div class="modal fade" id="modalRefus" tabindex="-1" aria-hidden="true">
            <div class="modal-dialog">
                <div class="modal-content">
                    <form method="POST" action="{{ route('admin.membres.refuser', $membre) }}">
                        @csrf
                        @method('PATCH')
                        <div class="modal-header">
                            <h5 class="modal-title">Refuser la demande d'adhésion</h5>
                            <button type="button" class="btn-close" data-bs-dismiss="modal" aria-label="Fermer"></button>
                        </div>
                        <div class="modal-body">
                            <label class="form-label">Motif du refus</label>
                            <textarea name="motif_rejet" class="form-control" rows="3" required></textarea>
                        </div>
                        <div class="modal-footer">
                            <button type="button" class="btn btn-outline-secondary" data-bs-dismiss="modal">Annuler</button>
                            <button type="submit" class="btn btn-danger">Refuser</button>
                        </div>
                    </form>
                </div>
            </div>
        </div>
    @endif

    @if ($membre->statut === $userClass::STATUT_ACTIF)
        <div class="modal fade" id="modalSuspension" tabindex="-1" aria-hidden="true">
            <div class="modal-dialog">
                <div class="modal-content">
                    <form method="POST" action="{{ route('admin.membres.suspendre', $membre) }}">
                        @csrf
                        @method('PATCH')
                        <div class="modal-header">
                            <h5 class="modal-title">Suspendre ce membre</h5>
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
