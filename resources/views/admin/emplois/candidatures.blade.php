@extends('layouts.admin')

@section('title', 'Candidatures — ' . $offre->titre)
@section('page-title', 'Candidatures')

@section('content')
<div class="mb-3">
    <a href="{{ route('admin.emplois.show', $offre) }}" class="btn btn-outline-secondary btn-sm">
        <i class="bi bi-arrow-left me-1"></i> Retour à l'offre
    </a>
</div>

<div class="card border-0 shadow-sm mb-3">
    <div class="card-body p-3">
        <span class="fw-semibold">{{ $offre->titre }}</span>
        <span class="badge bg-light text-dark border ms-2">{{ $offre->type_libelle }}</span>
        <span class="badge bg-success ms-1">{{ $offre->candidatures->count() }} candidature(s)</span>
    </div>
</div>

@if ($offre->candidatures->isEmpty())
    <div class="text-center py-5 text-muted">
        <i class="bi bi-inbox fs-1 d-block mb-2 opacity-25"></i>
        Aucune candidature reçue pour l'instant.
    </div>
@else
    @foreach ($offre->candidatures as $c)
        <div class="card border-0 shadow-sm mb-3">
            <div class="card-body p-4">
                <div class="d-flex align-items-start gap-3 flex-wrap">
                    <div class="rounded-circle d-flex align-items-center justify-content-center flex-shrink-0 text-white fw-bold"
                         style="width:42px;height:42px;background:var(--faaci-navy);">
                        {{ mb_strtoupper(mb_substr($c->candidat->prenom,0,1)) }}{{ mb_strtoupper(mb_substr($c->candidat->nom,0,1)) }}
                    </div>
                    <div class="flex-grow-1">
                        <div class="fw-semibold">{{ $c->candidat->nom_complet }}</div>
                        <div class="text-muted small">{{ $c->candidat->email }} · Postulé {{ $c->created_at->diffForHumans() }}</div>
                    </div>
                    @php
                        $sc = ['soumise'=>'warning','en_cours'=>'info','acceptee'=>'success','rejetee'=>'danger'];
                    @endphp
                    <span class="badge bg-{{ $sc[$c->statut] ?? 'secondary' }} align-self-start">{{ $c->statut_libelle }}</span>
                </div>

                @if ($c->lettre_motivation)
                    <div class="mt-3 p-3 bg-light rounded small" style="white-space:pre-line;">{{ $c->lettre_motivation }}</div>
                @endif

                @if ($c->cv_url)
                    <div class="mt-2">
                        <a href="{{ $c->cv_url }}" target="_blank" class="btn btn-sm btn-outline-secondary">
                            <i class="bi bi-file-earmark-pdf me-1 text-danger"></i> Télécharger le CV
                        </a>
                    </div>
                @endif

                <form method="POST" action="{{ route('admin.candidatures.maj', $c) }}" class="mt-3 row g-2 align-items-end">
                    @csrf @method('PATCH')
                    <div class="col-sm-4">
                        <select name="statut" class="form-select form-select-sm">
                            @foreach (\App\Models\Candidature::STATUTS_LIBELLES as $val => $lib)
                                <option value="{{ $val }}" {{ $c->statut === $val ? 'selected' : '' }}>{{ $lib }}</option>
                            @endforeach
                        </select>
                    </div>
                    <div class="col-sm-6">
                        <input type="text" name="note_recruteur" value="{{ $c->note_recruteur }}"
                               class="form-control form-control-sm" placeholder="Note pour le candidat (optionnel)">
                    </div>
                    <div class="col-sm-2">
                        <button type="submit" class="btn btn-sm btn-faaci-primary w-100">Mettre à jour</button>
                    </div>
                </form>
            </div>
        </div>
    @endforeach
@endif
@endsection
