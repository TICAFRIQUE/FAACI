{{--
  Calendrier dynamique cotisation.
  Params :
    $calendrier  = tableau construit par CotisationAdminController::buildCalendrier()
    $selectable  = bool (true = mode sélection avec checkboxes, false = lecture seule)
    $membre      = User (optionnel, pour le formulaire de paiement admin)
--}}

@php
    $selectable ??= false;
    $membre     ??= null;

    $couleurs = [
        'valide'     => ['bg' => '#d1fae5', 'border' => '#10b981', 'text' => '#065f46', 'label' => 'Soldé',      'icon' => '✓'],
        'partiel'    => ['bg' => '#fef9c3', 'border' => '#f59e0b', 'text' => '#78350f', 'label' => 'Partiel',    'icon' => '~'],
        'en_attente' => ['bg' => '#dbeafe', 'border' => '#3b82f6', 'text' => '#1e3a8a', 'label' => 'En attente', 'icon' => '○'],
        'en_retard'  => ['bg' => '#fee2e2', 'border' => '#ef4444', 'text' => '#7f1d1d', 'label' => 'En retard',  'icon' => '!'],
        'exonere'    => ['bg' => '#f3f4f6', 'border' => '#9ca3af', 'text' => '#374151', 'label' => 'Exonéré',    'icon' => '∅'],
        'inexistant' => ['bg' => '#f9fafb', 'border' => '#e5e7eb', 'text' => '#9ca3af', 'label' => 'À venir',    'icon' => '·'],
    ];
@endphp

@if (empty($calendrier))
    <div class="text-center text-muted py-4">
        <i class="bi bi-calendar-x fs-2 d-block mb-2 opacity-25"></i>
        Aucune période de cotisation trouvée.
    </div>
@else
    @foreach ($calendrier as $bloc)
        @php $type = $bloc['type']; @endphp

        <div class="mb-4" x-data="calendrierCotisation({{ $type->id }}, {{ $type->montant_standard }})">
            <div class="d-flex align-items-center justify-content-between mb-2">
                <div>
                    <h6 class="fw-bold mb-0">{{ $type->nom }}</h6>
                    <span class="text-muted small">
                        {{ \App\Models\TypeCotisation::frequences()[$type->frequence] }} —
                        {{ number_format($type->montant_standard, 0, ',', ' ') }} FCFA
                    </span>
                </div>
                @if ($selectable)
                    <button type="button" class="btn btn-faaci-primary btn-sm"
                            @click="toggleMode()"
                            x-text="modeSelection ? 'Annuler la sélection' : 'Déclarer un paiement'">
                    </button>
                @endif
            </div>

            {{-- Légende --}}
            <div class="d-flex flex-wrap gap-2 mb-3">
                @foreach ($couleurs as $statut => $c)
                    <span class="badge rounded-pill px-2 py-1 small"
                          style="background:{{ $c['bg'] }};border:1px solid {{ $c['border'] }};color:{{ $c['text'] }};">
                        {{ $c['icon'] }} {{ $c['label'] }}
                    </span>
                @endforeach
            </div>

            {{-- Calendrier par année --}}
            @foreach ($bloc['annees'] as $annee => $periodes)
                <div class="mb-3">
                    <div class="fw-semibold small text-muted mb-2 border-bottom pb-1">{{ $annee }}</div>
                    <div class="row g-2">
                        @foreach ($periodes as $item)
                            @php
                                $periode = $item['periode'];
                                $cot     = $item['cotisation'];
                                $statut  = $item['statut'];
                                $c       = $couleurs[$statut] ?? $couleurs['inexistant'];
                                $peutSelectionner = $selectable && in_array($statut, ['en_attente', 'en_retard', 'partiel', 'inexistant']);
                                $resteAPayer = $cot ? $cot->getReste() : (float) $periode->montant_standard;
                            @endphp
                            <div class="col-6 col-sm-4 col-md-3 col-lg-2">
                                <div class="position-relative rounded-2 p-2 text-center"
                                     style="background:{{ $c['bg'] }};border:2px solid {{ $c['border'] }};cursor:{{ $peutSelectionner ? 'pointer' : 'default' }};transition:transform .15s,box-shadow .15s;"
                                     @if ($selectable && $peutSelectionner)
                                         :class="{ 'shadow': selectionne({{ $periode->id }}) }"
                                         :style="selectionne({{ $periode->id }}) ? 'border-width:3px;transform:scale(1.04)' : ''"
                                         x-on:click="modeSelection && togglePeriode({{ $periode->id }}, {{ $resteAPayer }})"
                                     @endif
                                     title="{{ $c['label'] }}{{ $cot ? ' — ' . number_format($cot->montant_paye, 0, ',', ' ') . ' / ' . number_format($cot->montant_du, 0, ',', ' ') . ' FCFA' : '' }}">

                                    @if ($selectable && $peutSelectionner)
                                        <div x-show="modeSelection"
                                             class="position-absolute top-0 start-0 m-1">
                                            <input type="checkbox"
                                                   class="form-check-input"
                                                   :checked="selectionne({{ $periode->id }})"
                                                   style="pointer-events:none;">
                                        </div>
                                    @endif

                                    <div class="fw-bold" style="font-size:0.7rem;color:{{ $c['text'] }};">
                                        {{ $c['icon'] }}
                                    </div>
                                    <div class="fw-semibold" style="font-size:0.78rem;color:{{ $c['text'] }};">
                                        {{ $periode->date_debut->translatedFormat('M') }}
                                    </div>
                                    @if ($cot && $statut === 'partiel')
                                        <div style="font-size:0.65rem;color:{{ $c['text'] }};">
                                            {{ number_format($cot->montant_paye, 0, ',', ' ') }}/{{ number_format($cot->montant_du, 0, ',', ' ') }}
                                        </div>
                                    @endif
                                </div>
                            </div>
                        @endforeach
                    </div>
                </div>
            @endforeach

            {{-- Formulaire de paiement (mode sélection) --}}
            @if ($selectable)
                <div x-show="modeSelection && selected.length > 0" x-cloak
                     class="mt-3 p-3 rounded-3 border"
                     style="background:#f8faff;">

                    <form method="POST" action="{{ route('membre.cotisations.paiement.store') }}"
                          enctype="multipart/form-data">
                        @csrf
                        <input type="hidden" name="type_cotisation_id" value="{{ $type->id }}">

                        {{-- Champs cachés pour les périodes sélectionnées --}}
                        <template x-for="pid in selected" :key="pid">
                            <input type="hidden" name="periode_ids[]" :value="pid">
                        </template>

                        <div class="row g-3">
                            <div class="col-12">
                                <div class="d-flex justify-content-between align-items-center">
                                    <span class="fw-semibold">
                                        <span x-text="selected.length"></span> période(s) sélectionnée(s)
                                    </span>
                                    <span class="fw-bold fs-5" style="color:var(--faaci-navy);">
                                        Total : <span x-text="formatMontant(totalDu)"></span> FCFA
                                    </span>
                                </div>
                            </div>

                            <div class="col-sm-6">
                                <label class="form-label small fw-semibold">Montant versé (FCFA) <span class="text-danger">*</span></label>
                                <input type="number" name="montant" class="form-control"
                                       :value="totalDu" min="1" step="1" required
                                       x-model="montantSaisi">
                                <div class="form-text" x-show="montantSaisi < totalDu" style="color:#f59e0b;">
                                    Paiement partiel — le reste sera à compléter ultérieurement.
                                </div>
                            </div>
                            <div class="col-sm-6">
                                <label class="form-label small fw-semibold">Moyen de paiement <span class="text-danger">*</span></label>
                                <select name="moyen_paiement" class="form-select" required>
                                    <option value="">— Choisir —</option>
                                    @foreach (\App\Models\PaiementCotisation::moyensPaiement() as $k => $v)
                                        <option value="{{ $k }}">{{ $v }}</option>
                                    @endforeach
                                </select>
                            </div>
                            <div class="col-12">
                                <label class="form-label small fw-semibold">Note (optionnel)</label>
                                <input type="text" name="note" class="form-control form-control-sm"
                                       placeholder="Ex : Paiement annuel 2026">
                            </div>
                            <div class="col-12">
                                <label class="form-label small fw-semibold">Preuve de paiement (optionnel)</label>
                                <input type="file" name="preuve" class="form-control form-control-sm"
                                       accept="image/*,.pdf">
                            </div>
                            <div class="col-12 d-flex gap-2 justify-content-end">
                                <button type="button" class="btn btn-outline-secondary btn-sm" @click="toggleMode()">
                                    Annuler
                                </button>
                                <button type="submit" class="btn btn-faaci-primary btn-sm">
                                    <i class="bi bi-send me-1"></i> Envoyer la déclaration
                                </button>
                            </div>
                        </div>
                    </form>
                </div>
            @endif
        </div>

        @if (!$loop->last)
            <hr class="my-4">
        @endif
    @endforeach
@endif

@once
@push('scripts')
<script>
function calendrierCotisation(typeId, montantStandard) {
    return {
        typeId,
        montantStandard,
        modeSelection: false,
        selected: [],        // tableau de periode_id sélectionnés
        montants: {},        // { periode_id: resteAPayer }
        montantSaisi: 0,

        get totalDu() {
            return this.selected.reduce((s, id) => s + (this.montants[id] || this.montantStandard), 0);
        },

        toggleMode() {
            this.modeSelection = !this.modeSelection;
            if (!this.modeSelection) {
                this.selected = [];
                this.montants = {};
            }
        },

        selectionne(id) {
            return this.selected.includes(id);
        },

        togglePeriode(id, resteAPayer) {
            if (!this.modeSelection) return;
            const idx = this.selected.indexOf(id);
            if (idx === -1) {
                this.selected.push(id);
                this.montants[id] = resteAPayer;
            } else {
                this.selected.splice(idx, 1);
                delete this.montants[id];
            }
            this.montantSaisi = this.totalDu;
        },

        formatMontant(n) {
            return new Intl.NumberFormat('fr-FR').format(n);
        }
    };
}
</script>
@endpush
@endonce
