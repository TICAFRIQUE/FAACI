@extends('layouts.admin')
@section('title', 'Cotisations — ' . $membre->nom_complet)

@section('content')
<div class="d-flex align-items-center justify-content-between mb-4">
    <div>
        <h4 class="fw-bold mb-0">
            <i class="bi bi-wallet2 me-2 text-faaci-steel"></i>Cotisations — {{ $membre->nom_complet }}
        </h4>
        <a href="{{ route('admin.membres.show', $membre) }}" class="text-muted small">
            <i class="bi bi-arrow-left me-1"></i> Retour à la fiche membre
        </a>
    </div>
    <button class="btn btn-faaci-navy btn-sm" data-bs-toggle="modal" data-bs-target="#modalAjoutPaiement">
        <i class="bi bi-plus-lg me-1"></i> Ajouter un paiement
    </button>
</div>

@if (empty($calendriers))
    <div class="alert alert-info">
        <i class="bi bi-info-circle me-2"></i>
        Aucun type de cotisation configuré avec une date de début.
        <a href="{{ route('admin.cotisations.types.index') }}" class="alert-link">Configurer les types</a>.
    </div>
@else

{{-- ── Calendriers Year × Month ── --}}
@foreach ($calendriers as $bloc)
    @php $type = $bloc['type']; $cal = $bloc['calendrier']; @endphp
    <div class="card border-0 shadow-sm mb-4">
        <div class="card-header bg-white border-bottom-0 pt-3 d-flex justify-content-between align-items-center">
            <div>
                <h6 class="fw-bold mb-0">{{ $type->nom }}</h6>
                <span class="text-muted small">
                    {{ \App\Models\TypeCotisation::frequences()[$type->frequence] }} —
                    {{ number_format($type->montant_standard, 0, ',', ' ') }} FCFA
                    | Depuis {{ $type->date_debut?->translatedFormat('d F Y') }}
                    @if ($type->date_fin)
                        → {{ $type->date_fin->translatedFormat('d F Y') }}
                    @else
                        → sans fin
                    @endif
                </span>
            </div>
            {{-- Légende --}}
            <div class="d-flex gap-2 flex-wrap justify-content-end">
                @foreach ([
                    ['#16a34a','#dcfce7','Payé'],
                    ['#ca8a04','#fef9c3','En attente'],
                    ['#dc2626','#fee2e2','En retard'],
                    ['#2563eb','#dbeafe','À venir'],
                ] as [$text,$bg,$lib])
                    <span class="badge rounded-pill px-2" style="background:{{$bg}};color:{{$text}};border:1px solid {{$text}};font-size:0.72rem;">
                        {{ $lib }}
                    </span>
                @endforeach
            </div>
        </div>
        <div class="card-body p-0">
            @if (empty($cal))
                <p class="text-muted small p-3 mb-0">Aucune donnée.</p>
            @else
                <div class="table-responsive">
                    <table class="table table-bordered mb-0 text-center" style="min-width:700px;">
                        <thead>
                            <tr style="background:#0D1F3C;color:#fff;font-size:0.8rem;font-weight:700;letter-spacing:.03em;">
                                <th style="min-width:70px;">ANNÉE</th>
                                @foreach(['JAN','FÉV','MAR','AVR','MAI','JUN','JUL','AOÛ','SEP','OCT','NOV','DÉC'] as $m)
                                    <th>{{ $m }}</th>
                                @endforeach
                            </tr>
                        </thead>
                        <tbody>
                            @foreach ($cal as $annee => $moisData)
                                <tr>
                                    <td style="background:#0D1F3C;color:#fff;font-weight:700;font-size:0.85rem;">
                                        {{ $annee }}
                                    </td>
                                    @for ($m = 1; $m <= 12; $m++)
                                        @php
                                            $cell   = $moisData[$m] ?? null;
                                            $statut = $cell['statut'] ?? 'hors_periode';
                                            $moisStr = $cell['mois'] ?? null;

                                            $styles = [
                                                'valide'       => ['#dcfce7','#16a34a','✓'],
                                                'en_attente'   => ['#fef9c3','#ca8a04','~'],
                                                'retard'       => ['#fee2e2','#dc2626','✗'],
                                                'futur'        => ['#dbeafe','#2563eb','·'],
                                                'hors_periode' => ['#f3f4f6','#d1d5db',''],
                                            ];
                                            [$bg,$border,$icon] = $styles[$statut] ?? $styles['hors_periode'];
                                        @endphp
                                        <td style="background:{{$bg}};color:{{$border}};font-weight:700;font-size:0.9rem;padding:6px 4px;border-color:#e5e7eb;">
                                            {{ $icon }}
                                        </td>
                                    @endfor
                                </tr>
                            @endforeach
                        </tbody>
                    </table>
                </div>
            @endif
        </div>
    </div>
@endforeach

{{-- ── Historique des paiements ── --}}
<div class="card border-0 shadow-sm">
    <div class="card-header bg-white fw-semibold pt-3 border-bottom-0">
        <i class="bi bi-clock-history me-1 text-faaci-steel"></i> Historique des paiements
    </div>
    <div class="card-body p-0">
        @if ($paiements->isEmpty())
            <p class="text-muted small p-3 mb-0">Aucun paiement enregistré.</p>
        @else
            <div class="table-responsive">
                <table class="table table-hover align-middle mb-0 small">
                    <thead class="table-light">
                        <tr>
                            <th>Type</th>
                            <th>Périodes</th>
                            <th>Montant</th>
                            <th>Moyen</th>
                            <th>N° Transaction</th>
                            <th>Statut</th>
                            <th>Date</th>
                            <th></th>
                        </tr>
                    </thead>
                    <tbody>
                        @foreach ($paiements as $p)
                            @php
                                $sc = ['en_attente'=>'warning','valide'=>'success','rejete'=>'danger'];
                                $sl = ['en_attente'=>'En attente','valide'=>'Validé','rejete'=>'Rejeté'];
                            @endphp
                            <tr>
                                <td class="fw-medium">{{ $p->type?->nom ?? '—' }}</td>
                                <td>{{ \App\Http\Controllers\Admin\CotisationAdminController::formatMoisCouverts($p->mois_couverts) }}</td>
                                <td class="fw-medium">{{ number_format($p->montant, 0, ',', ' ') }} FCFA</td>
                                <td class="text-muted">{{ $p->moyen_paiement ? \App\Models\PaiementCotisation::moyensPaiement()[$p->moyen_paiement] : '—' }}</td>
                                <td class="font-monospace small">{{ $p->numero_transaction ?: '—' }}</td>
                                <td>
                                    <span class="badge bg-{{ $sc[$p->statut]??'secondary' }}">
                                        {{ $sl[$p->statut]??$p->statut }}
                                    </span>
                                </td>
                                <td class="text-muted">{{ $p->created_at->translatedFormat('d M Y H:i') }}</td>
                                <td class="text-end">
                                    @if ($p->statut === 'en_attente')
                                        <form method="POST" action="{{ route('admin.cotisations.paiements.valider', $p) }}"
                                              class="d-inline" data-confirm="Valider ce paiement ?">
                                            @csrf @method('PATCH')
                                            <button type="submit" class="btn btn-sm btn-success"><i class="bi bi-check-lg"></i></button>
                                        </form>
                                    @endif
                                </td>
                            </tr>
                        @endforeach
                    </tbody>
                </table>
            </div>
        @endif
    </div>
</div>
@endif

{{-- ── Modal ajout paiement ── --}}
<div class="modal fade" id="modalAjoutPaiement" tabindex="-1">
    <div class="modal-dialog modal-lg">
        <div class="modal-content" x-data="ajoutPaiementAdmin()">
            <form method="POST" action="{{ route('admin.cotisations.membre.paiement', $membre) }}"
                  enctype="multipart/form-data">
                @csrf
                <div class="modal-header" style="background:#0D1F3C;">
                    <h5 class="modal-title text-white">
                        <i class="bi bi-plus-circle me-2"></i>Ajouter un paiement pour {{ $membre->prenom }}
                    </h5>
                    <button type="button" class="btn-close btn-close-white" data-bs-dismiss="modal"></button>
                </div>
                <div class="modal-body">
                    <div class="row g-3">

                        {{-- Type de cotisation --}}
                        <div class="col-12">
                            <label class="form-label fw-semibold small">Type de cotisation <span class="text-danger">*</span></label>
                            <select name="type_cotisation_id" class="form-select" required x-ref="selectType"
                                    @change="selectType($event.target)">
                                <option value="" selected disabled>— Choisir un type —</option>
                                @foreach ($types as $t)
                                    <option value="{{ $t->id }}"
                                            data-debut="{{ $t->date_debut?->format('Y-m') }}"
                                            data-montant="{{ $t->montant_standard }}"
                                            data-couverts="{{ json_encode($moisDejaCouverts[$t->id] ?? []) }}">
                                        {{ $t->nom }}
                                    </option>
                                @endforeach
                            </select>
                        </div>

                        {{-- Sélection des mois --}}
                        <div class="col-12" x-show="annees.length > 0" x-cloak>
                            <label class="form-label fw-semibold small">
                                Mois à couvrir <span class="text-danger">*</span>
                            </label>

                            {{-- Sélection rapide --}}
                            <div class="d-flex align-items-center gap-2 mb-2">
                                <input type="number" min="1" max="600" x-model.number="nMois"
                                       class="form-control form-control-sm" style="width:75px;"
                                       placeholder="Mois">
                                <button type="button" class="btn btn-outline-primary btn-sm"
                                        @click="selectionnerNMois()">
                                    <i class="bi bi-lightning-fill me-1"></i>Générer
                                </button>
                                <span class="text-muted small" x-show="selected.length > 0" x-cloak>
                                    <span x-text="selected.length"></span> sélectionné(s)
                                    · <button type="button" class="btn btn-link btn-sm p-0 text-muted"
                                              @click="selected = []">effacer</button>
                                </span>
                            </div>

                            <template x-for="annee in annees" :key="annee.year">
                                <div class="mb-2">
                                    <div class="fw-semibold small text-muted mb-1" x-text="annee.year"></div>
                                    <div class="d-flex flex-wrap gap-1">
                                        <template x-for="m in annee.mois" :key="m.val">
                                            <label :style="isCouvert(m.val) ? 'cursor:not-allowed;' : 'cursor:pointer;'">
                                                <input type="checkbox" name="mois_couverts[]"
                                                       :value="m.val" x-model="selected" class="d-none"
                                                       :disabled="isCouvert(m.val)">
                                                <span class="d-inline-block px-2 py-1 rounded small fw-semibold"
                                                      :title="isCouvert(m.val) ? 'Déjà couvert' : ''"
                                                      :style="isCouvert(m.val)
                                                          ? 'background:#d1fae5;color:#065f46;border:2px solid #6ee7b7;opacity:.7;'
                                                          : selected.includes(m.val)
                                                              ? 'background:#0D1F3C;color:#fff;border:2px solid #0D1F3C;'
                                                              : 'background:#f3f4f6;color:#374151;border:2px solid #e5e7eb;'"
                                                      x-text="isCouvert(m.val) ? m.label + ' ✓' : m.label">
                                                </span>
                                            </label>
                                        </template>
                                    </div>
                                </div>
                            </template>
                        </div>

                        {{-- Montant --}}
                        <div class="col-sm-6">
                            <label class="form-label fw-semibold small">Montant (FCFA) <span class="text-danger">*</span></label>
                            <input type="number" name="montant" class="form-control" min="1" required
                                   :value="montantAuto" x-model="montantSaisi">
                            <div class="form-text" x-show="montantAuto > 0" x-cloak>
                                Standard : <span x-text="formatFCFA(montantAuto)"></span> FCFA
                                (<span x-text="selected.length"></span> mois × <span x-text="formatFCFA(montantUnitaire)"></span>)
                            </div>
                        </div>

                        {{-- Moyen de paiement --}}
                        <div class="col-sm-6">
                            <label class="form-label fw-semibold small">Moyen de paiement <span class="text-danger">*</span></label>
                            <select name="moyen_paiement" class="form-select" required x-model="moyen">
                                <option value="">— Choisir —</option>
                                @foreach (\App\Models\PaiementCotisation::moyensPaiement() as $k => $v)
                                    <option value="{{ $k }}">{{ $v }}</option>
                                @endforeach
                            </select>
                        </div>

                        {{-- N° Transaction --}}
                        <div class="col-12" x-show="['orange_money','wave','virement','autre'].includes(moyen)" x-cloak>
                            <label class="form-label fw-semibold small">N° Transaction</label>
                            <input type="text" name="numero_transaction" class="form-control form-control-sm"
                                   placeholder="Ex : MP250316.2137.C53621" maxlength="100">
                        </div>

                        {{-- Note --}}
                        <div class="col-12">
                            <label class="form-label fw-semibold small">Note (optionnel)</label>
                            <input type="text" name="note" class="form-control form-control-sm"
                                   placeholder="Ex : Paiement annuel 2026">
                        </div>

                        {{-- Preuve --}}
                        <div class="col-12">
                            <label class="form-label fw-semibold small">Preuve (optionnel)</label>
                            <input type="file" name="preuve" class="form-control form-control-sm"
                                   accept="image/*,.pdf">
                        </div>

                    </div>
                </div>
                <div class="modal-footer">
                    <button type="button" class="btn btn-outline-secondary" data-bs-dismiss="modal">Annuler</button>
                    <button type="submit" class="btn btn-faaci-navy">
                        <i class="bi bi-check-lg me-1"></i> Enregistrer et valider
                    </button>
                </div>
            </form>
        </div>
    </div>
</div>

@endsection

@push('scripts')
<script>
function ajoutPaiementAdmin() {
    const MOIS = ['Jan','Fév','Mar','Avr','Mai','Jun','Jul','Aoû','Sep','Oct','Nov','Déc'];
    return {
        annees: [], selected: [], moisCouverts: [],
        montantUnitaire: 0, moyen: '', montantSaisi: 0, nMois: '',
        _debut: null,

        init() {
            const modal = this.$el.closest('.modal');
            if (!modal) return;
            modal.addEventListener('show.bs.modal', () => {
                this.annees          = [];
                this.selected        = [];
                this.moisCouverts    = [];
                this.montantUnitaire = 0;
                this.montantSaisi    = 0;
                this.moyen           = '';
                this.nMois           = '';
                this._debut          = null;
                if (this.$refs.selectType) this.$refs.selectType.value = '';
            });
        },

        get montantAuto() { return this.selected.length * this.montantUnitaire; },

        isCouvert(val) { return this.moisCouverts.includes(val); },

        // Génère les mois depuis _debut jusqu'à avoir nLibresVoulu mois libres
        // ET jusqu'à couvrir au minimum aujourd'hui + 3 mois
        _buildAnnees(nLibresVoulu) {
            if (!this._debut) return;
            const [dy, dm] = this._debut.split('-').map(Number);
            const now    = new Date();
            const minY   = now.getFullYear();
            const minM   = now.getMonth() + 4; // aujourd'hui + 3 mois minimum
            const moisParAnnee = {};
            let y = dy, m = dm, libres = 0, total = 0;

            while (total < 600) { // garde-fou : 50 ans max
                const val = `${y}-${String(m).padStart(2,'0')}`;
                if (!moisParAnnee[y]) moisParAnnee[y] = [];
                moisParAnnee[y].push({ val, label: MOIS[m - 1] });
                if (!this.moisCouverts.includes(val)) libres++;
                m++; if (m > 12) { m = 1; y++; }
                total++;
                const pastMin = y > minY || (y === minY && m > minM);
                if (libres >= nLibresVoulu && pastMin) break;
            }

            this.annees = Object.entries(moisParAnnee)
                .map(([year, mois]) => ({ year: parseInt(year), mois }))
                .sort((a, b) => a.year - b.year);
        },

        moisLibres() {
            return this.annees.flatMap(a => a.mois).filter(m => !this.isCouvert(m.val));
        },

        toutSelectionner() {
            this.selected = this.moisLibres().map(m => m.val);
        },

        selectionnerNMois() {
            const n = parseInt(this.nMois) || 0;
            if (n < 1) return;
            // Régénérer le picker pour s'assurer d'avoir au moins n mois libres
            this._buildAnnees(n);
            this.selected = this.moisLibres().slice(0, n).map(m => m.val);
        },

        selectType(select) {
            this.selected = []; this.annees = []; this.moisCouverts = []; this._debut = null;
            const opt = select.options[select.selectedIndex];
            if (!opt || !opt.value || !opt.dataset.debut) return;
            this.montantUnitaire = parseFloat(opt.dataset.montant) || 0;
            this.montantSaisi    = 0;
            this.moisCouverts    = JSON.parse(opt.dataset.couverts || '[]');
            this._debut          = opt.dataset.debut;
            this._buildAnnees(12); // affichage initial : 12 mois libres disponibles
        },

        formatFCFA(n) { return new Intl.NumberFormat('fr-FR').format(n); },
    };
}
</script>
@endpush
