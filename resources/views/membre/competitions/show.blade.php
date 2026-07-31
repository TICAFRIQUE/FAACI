@extends('layouts.app')

@section('title', $competition->titre)

@section('breadcrumb')
    <li class="breadcrumb-item"><a href="{{ route('membre.competitions.index') }}">Compétitions</a></li>
    <li class="breadcrumb-item active">{{ Str::limit($competition->titre, 40) }}</li>
@endsection

@section('content')
<div class="row g-4">
    {{-- Détail de la compétition --}}
    <div class="col-lg-8">
        <div class="card border-0 shadow-sm mb-4">
            <div class="card-header bg-white pt-3 border-bottom-0">
                <div class="d-flex align-items-start justify-content-between gap-2">
                    <h5 class="fw-bold mb-0">
                        <i class="bi bi-trophy me-1 text-faaci-steel"></i> {{ $competition->titre }}
                    </h5>
                    @php
                        $badge = \App\Models\Competition::statutsBadge()[$competition->statut] ?? 'secondary';
                        $lib   = \App\Models\Competition::statuts()[$competition->statut] ?? $competition->statut;
                    @endphp
                    <span class="badge bg-{{ $badge }} flex-shrink-0">{{ $lib }}</span>
                </div>
            </div>
            <div class="card-body">
                @if ($competition->description)
                    <div class="text-muted mb-4" style="white-space:pre-line;">{{ $competition->description }}</div>
                @endif

                <div class="row g-3">
                    @if ($competition->budget)
                        <div class="col-sm-4">
                            <div class="p-3 rounded" style="background:#f0fdf4;">
                                <div class="fw-bold text-success" style="font-size:1.1rem;">
                                    {{ number_format($competition->budget, 0, ',', ' ') }} FCFA
                                </div>
                                <small class="text-muted">Budget alloué</small>
                            </div>
                        </div>
                    @endif
                    @if ($competition->date_limite_candidature)
                        <div class="col-sm-4">
                            <div class="p-3 rounded" style="background:#eff6ff;">
                                <div class="fw-bold text-primary">
                                    {{ $competition->date_limite_candidature->translatedFormat('d M Y') }}
                                </div>
                                <small class="text-muted">Date limite de candidature</small>
                            </div>
                        </div>
                    @endif
                    @if ($competition->date_pitch)
                        <div class="col-sm-4">
                            <div class="p-3 rounded" style="background:#faf5ff;">
                                <div class="fw-bold" style="color:#7c3aed;">
                                    {{ $competition->date_pitch->translatedFormat('d M Y') }}
                                </div>
                                <small class="text-muted">Date du pitch</small>
                            </div>
                        </div>
                    @endif
                </div>
            </div>
        </div>

        {{-- Ma candidature ou formulaire --}}
        @if ($maCandidature)
            <div class="card border-0 shadow-sm">
                <div class="card-header bg-white pt-3 border-bottom-0 fw-semibold">
                    <i class="bi bi-file-text me-1 text-faaci-steel"></i> Ma candidature
                </div>
                <div class="card-body">
                    @php
                        $sb = \App\Models\CandidatureCompetition::statutsBadge()[$maCandidature->statut] ?? 'secondary';
                        $sl = \App\Models\CandidatureCompetition::statuts()[$maCandidature->statut] ?? $maCandidature->statut;
                    @endphp
                    <div class="mb-3">
                        <span class="badge bg-{{ $sb }} fs-6">{{ $sl }}</span>
                    </div>
                    <dl class="row mb-0">
                        <dt class="col-sm-3 text-muted fw-normal small">Titre du projet</dt>
                        <dd class="col-sm-9 fw-semibold">{{ $maCandidature->titre_projet }}</dd>

                        <dt class="col-sm-3 text-muted fw-normal small">Résumé</dt>
                        <dd class="col-sm-9" style="white-space:pre-line;">{{ $maCandidature->resume_projet }}</dd>

                        @if ($maCandidature->note_jury)
                            <dt class="col-sm-3 text-muted fw-normal small">Note du jury</dt>
                            <dd class="col-sm-9 fst-italic">{{ $maCandidature->note_jury }}</dd>
                        @endif

                        <dt class="col-sm-3 text-muted fw-normal small">Soumise le</dt>
                        <dd class="col-sm-9 text-muted">{{ $maCandidature->created_at->translatedFormat('d F Y à H:i') }}</dd>
                    </dl>
                </div>
            </div>
        @elseif ($competition->estOuverte())
            <div class="card border-0 shadow-sm">
                <div class="card-header bg-white pt-3 border-bottom-0 fw-semibold">
                    <i class="bi bi-send me-1 text-faaci-steel"></i> Soumettre ma candidature
                </div>
                <div class="card-body">
                    <form method="POST" action="{{ route('membre.competitions.postuler', $competition) }}">
                        @csrf
                        <div class="mb-3">
                            <label for="titre_projet" class="form-label fw-semibold small">
                                Titre de votre projet <span class="text-danger">*</span>
                            </label>
                            <input type="text" id="titre_projet" name="titre_projet"
                                   class="form-control @error('titre_projet') is-invalid @enderror"
                                   value="{{ old('titre_projet') }}" required maxlength="200"
                                   placeholder="Ex : Application de mise en relation agro-alimentaire">
                            @error('titre_projet')<div class="invalid-feedback">{{ $message }}</div>@enderror
                        </div>
                        <div class="mb-3">
                            <label for="resume_projet" class="form-label fw-semibold small">
                                Résumé du projet <span class="text-danger">*</span>
                                <span class="text-muted fw-normal">(max 3 000 caractères)</span>
                            </label>
                            <textarea id="resume_projet" name="resume_projet" rows="7"
                                      class="form-control @error('resume_projet') is-invalid @enderror"
                                      required maxlength="3000"
                                      placeholder="Décrivez votre projet : problème résolu, solution proposée, impact attendu, équipe…">{{ old('resume_projet') }}</textarea>
                            <div class="form-text">
                                <span id="charCount">0</span> / 3 000 caractères
                            </div>
                            @error('resume_projet')<div class="invalid-feedback">{{ $message }}</div>@enderror
                        </div>
                        <div class="alert alert-info small mb-3">
                            <i class="bi bi-info-circle me-1"></i>
                            Votre nom et coordonnées seront automatiquement joints à votre candidature.
                            Seule votre présentation de projet est requise ici.
                        </div>
                        <button type="submit" class="btn btn-faaci-primary">
                            <i class="bi bi-send me-1"></i> Soumettre ma candidature
                        </button>
                    </form>
                </div>
            </div>
        @else
            <div class="alert alert-secondary">
                <i class="bi bi-lock me-1"></i> Les candidatures sont closes pour cette compétition.
            </div>
        @endif
    </div>

    {{-- Sidebar infos --}}
    <div class="col-lg-4">
        <div class="card border-0 shadow-sm">
            <div class="card-header bg-white fw-semibold pt-3 border-bottom-0">
                <i class="bi bi-info-circle me-1 text-faaci-steel"></i> Comment ça marche ?
            </div>
            <div class="card-body">
                <ol class="ps-3 small text-muted mb-0" style="line-height:2;">
                    <li>Soumettez votre candidature avant la date limite</li>
                    <li>La fondation sélectionne les meilleurs projets</li>
                    <li>Les candidats sélectionnés pitchent lors d'un événement</li>
                    <li>Le jury désigne le(s) vainqueur(s)</li>
                    <li>Le(s) projet(s) gagnant(s) reçoivent le financement</li>
                </ol>
            </div>
        </div>

        <div class="mt-3">
            <a href="{{ route('membre.competitions.index') }}" class="btn btn-outline-secondary btn-sm w-100">
                <i class="bi bi-arrow-left me-1"></i> Retour aux compétitions
            </a>
        </div>
    </div>
</div>
@endsection

@push('scripts')
<script>
const ta = document.getElementById('resume_projet');
const counter = document.getElementById('charCount');
if (ta && counter) {
    const update = () => counter.textContent = ta.value.length;
    ta.addEventListener('input', update);
    update();
}
</script>
@endpush
