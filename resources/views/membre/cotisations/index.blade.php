@extends('layouts.app')

@section('title', 'Mes cotisations')

@section('breadcrumb')
    <li class="breadcrumb-item active">Mes cotisations</li>
@endsection

@section('content')
<div class="d-flex align-items-center justify-content-between mb-4">
    <h4 class="fw-bold mb-0">Mes cotisations</h4>
</div>
<div class="alert alert-info d-flex align-items-center gap-2 mb-4" style="font-size:.9rem;">
    <i class="bi bi-info-circle-fill flex-shrink-0"></i>
    Les paiements de cotisation sont enregistrés et validés par l'administration. Contactez un administrateur pour déclarer un paiement.
</div>

@if (empty($calendriers))
    <div class="text-center py-5 text-muted">
        <i class="bi bi-wallet2 fs-1 d-block mb-2 opacity-25"></i>
        Aucune cotisation configurée pour le moment.
    </div>
@else

{{-- ── Calendriers ── --}}
@foreach ($calendriers as $bloc)
    @php $type = $bloc['type']; $cal = $bloc['calendrier']; @endphp
    <div class="card border-0 shadow-sm mb-4">
        <div class="card-header bg-white border-bottom-0 pt-3">
            <div class="d-flex align-items-start justify-content-between flex-wrap gap-2">
                <div>
                    <h6 class="fw-bold mb-0">{{ $type->nom }}</h6>
                    <span class="text-muted small">
                        {{ \App\Models\TypeCotisation::frequences()[$type->frequence] }} —
                        {{ number_format($type->montant_standard, 0, ',', ' ') }} FCFA
                    </span>
                </div>
                {{-- Légende --}}
                <div class="d-flex gap-2 flex-wrap">
                    @foreach([['#16a34a','#dcfce7','Payé'],['#ca8a04','#fef9c3','En attente'],['#dc2626','#fee2e2','En retard'],['#2563eb','#dbeafe','À venir']] as [$t,$b,$l])
                        <span class="badge rounded-pill px-2" style="background:{{$b}};color:{{$t}};border:1px solid {{$t}};font-size:0.7rem;">{{$l}}</span>
                    @endforeach
                </div>
            </div>
        </div>
        <div class="card-body p-0">
            @if (empty($cal))
                <p class="text-muted small p-3 mb-0">Aucune donnée.</p>
            @else
                <div class="table-responsive">
                    <table class="table table-bordered mb-0 text-center" style="min-width:650px;">
                        <thead>
                            <tr style="background:#0D1F3C;color:#fff;font-size:0.78rem;font-weight:700;">
                                <th style="min-width:65px;">ANNÉE</th>
                                @foreach(['JAN','FÉV','MAR','AVR','MAI','JUN','JUL','AOÛ','SEP','OCT','NOV','DÉC'] as $nm)
                                    <th>{{ $nm }}</th>
                                @endforeach
                            </tr>
                        </thead>
                        <tbody>
                            @foreach ($cal as $annee => $moisData)
                                <tr>
                                    <td style="background:#0D1F3C;color:#fff;font-weight:700;font-size:0.83rem;">{{ $annee }}</td>
                                    @for ($m = 1; $m <= 12; $m++)
                                        @php
                                            $cell   = $moisData[$m] ?? null;
                                            $statut = $cell['statut'] ?? 'hors_periode';
                                            $styles = [
                                                'valide'       => ['#dcfce7','#16a34a','✓'],
                                                'en_attente'   => ['#fef9c3','#ca8a04','~'],
                                                'retard'       => ['#fee2e2','#dc2626','✗'],
                                                'futur'        => ['#dbeafe','#2563eb','·'],
                                                'hors_periode' => ['#f9fafb','#d1d5db',''],
                                            ];
                                            [$bg,$border,$icon] = $styles[$statut] ?? $styles['hors_periode'];
                                        @endphp
                                        <td style="background:{{$bg}};color:{{$border}};font-weight:700;font-size:0.88rem;padding:5px 3px;border-color:#e5e7eb;">
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

{{-- ── Historique ── --}}
@if ($paiements->isNotEmpty())
    <div class="card border-0 shadow-sm">
        <div class="card-header bg-white fw-semibold pt-3 border-bottom-0">
            <i class="bi bi-clock-history me-1 text-faaci-steel"></i> Historique de mes paiements
        </div>
        <div class="card-body p-0">
            <div class="table-responsive">
                <table class="table table-hover align-middle mb-0 table-mobile-cards small">
                    <thead class="table-light">
                        <tr>
                            <th>Type</th>
                            <th>Périodes</th>
                            <th>Montant</th>
                            <th>Moyen</th>
                            <th>N° Transaction</th>
                            <th>Statut</th>
                            <th>Date</th>
                        </tr>
                    </thead>
                    <tbody>
                        @foreach ($paiements as $p)
                            @php $sc=['en_attente'=>'warning','valide'=>'success','rejete'=>'danger']; $sl=['en_attente'=>'En attente','valide'=>'Validé','rejete'=>'Rejeté']; @endphp
                            <tr>
                                <td data-label="Type" class="fw-medium">{{ $p->type?->nom }}</td>
                                <td data-label="Périodes" class="text-muted">
                                    {{ \App\Http\Controllers\Admin\CotisationAdminController::formatMoisCouverts($p->mois_couverts) }}
                                </td>
                                <td data-label="Montant" class="fw-medium">{{ number_format($p->montant, 0, ',', ' ') }} FCFA</td>
                                <td data-label="Moyen" class="text-muted">{{ $p->moyen_paiement ? \App\Models\PaiementCotisation::moyensPaiement()[$p->moyen_paiement] : '—' }}</td>
                                <td data-label="N° Transaction" class="font-monospace small">{{ $p->numero_transaction ?: '—' }}</td>
                                <td data-label="Statut">
                                    <span class="badge bg-{{ $sc[$p->statut]??'secondary' }}">{{ $sl[$p->statut]??$p->statut }}</span>
                                    @if ($p->statut === 'rejete' && $p->motif_rejet)
                                        <div class="text-danger small mt-1">{{ $p->motif_rejet }}</div>
                                    @endif
                                </td>
                                <td data-label="Date" class="text-muted">{{ $p->created_at->diffForHumans() }}</td>
                            </tr>
                        @endforeach
                    </tbody>
                </table>
            </div>
        </div>
    </div>
@endif

{{-- ── Modal déclaration ── --}}
<div class="modal fade" id="modalDeclarer" tabindex="-1">
    <div class="modal-dialog modal-lg">
        <div class="modal-content" x-data="declarerPaiement()">
            <form method="POST" action="{{ route('membre.cotisations.paiement.store') }}" enctype="multipart/form-data">
                @csrf
                <div class="modal-header">
                    <h5 class="modal-title fw-bold">Déclarer un paiement</h5>
                    <button type="button" class="btn-close" data-bs-dismiss="modal"></button>
                </div>
                <div class="modal-body">
                    <div class="row g-3">
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

                        <div class="col-12" x-show="annees.length > 0" x-cloak>
                            <label class="form-label fw-semibold small">
                                Mois à couvrir <span class="text-danger">*</span>
                                <span x-show="selected.length > 0" class="text-muted fw-normal">
                                    — <span x-text="selected.length"></span> sélectionné(s)
                                </span>
                            </label>

                            {{-- Sélection rapide --}}
                            <div class="d-flex flex-wrap align-items-center gap-2 mb-2">
                                <button type="button" class="btn btn-outline-secondary btn-sm"
                                        @click="toutSelectionner()">
                                    <i class="bi bi-check2-all me-1"></i>Tout sélectionner
                                </button>
                                <button type="button" class="btn btn-outline-secondary btn-sm"
                                        @click="selected = []">
                                    <i class="bi bi-x-lg me-1"></i>Effacer
                                </button>
                                <div class="d-flex align-items-center gap-1">
                                    <input type="number" min="1" max="60" x-model.number="nMois"
                                           class="form-control form-control-sm" style="width:65px;"
                                           placeholder="N">
                                    <button type="button" class="btn btn-outline-primary btn-sm"
                                            @click="selectionnerNMois()">
                                        premiers mois
                                    </button>
                                </div>
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
                                                              ? 'background:var(--faaci-navy);color:#fff;border:2px solid var(--faaci-navy);'
                                                              : 'background:#f3f4f6;color:#374151;border:2px solid #e5e7eb;'"
                                                      x-text="isCouvert(m.val) ? m.label + ' ✓' : m.label">
                                                </span>
                                            </label>
                                        </template>
                                    </div>
                                </div>
                            </template>
                        </div>

                        <div class="col-sm-6">
                            <label class="form-label fw-semibold small">Montant versé (FCFA) <span class="text-danger">*</span></label>
                            <input type="number" name="montant" class="form-control" min="1" required
                                   :value="montantAuto" x-model="montantSaisi">
                            <div class="form-text" x-show="montantAuto > 0" x-cloak>
                                Standard : <span x-text="formatFCFA(montantAuto)"></span> FCFA
                            </div>
                        </div>
                        <div class="col-sm-6">
                            <label class="form-label fw-semibold small">Moyen de paiement <span class="text-danger">*</span></label>
                            <select name="moyen_paiement" class="form-select" required x-model="moyen">
                                <option value="">— Choisir —</option>
                                @foreach (\App\Models\PaiementCotisation::moyensPaiement() as $k => $v)
                                    <option value="{{ $k }}">{{ $v }}</option>
                                @endforeach
                            </select>
                        </div>

                        <div class="col-12" x-show="['orange_money','wave','virement','autre'].includes(moyen)" x-cloak>
                            <label class="form-label fw-semibold small">N° Transaction</label>
                            <input type="text" name="numero_transaction" class="form-control form-control-sm"
                                   placeholder="Ex : MP250316.2137.C53621" maxlength="100">
                        </div>
                        <div class="col-12">
                            <label class="form-label fw-semibold small">Note (optionnel)</label>
                            <input type="text" name="note" class="form-control form-control-sm"
                                   placeholder="Ex : Paiement annuel 2026">
                        </div>
                        <div class="col-12">
                            <label class="form-label fw-semibold small">Preuve de paiement (optionnel)</label>
                            <input type="file" name="preuve" class="form-control form-control-sm" accept="image/*,.pdf">
                            <div class="form-text">Photo du reçu ou capture de la transaction.</div>
                        </div>
                    </div>
                </div>
                <div class="modal-footer">
                    <button type="button" class="btn btn-outline-secondary" data-bs-dismiss="modal">Annuler</button>
                    <button type="submit" class="btn btn-faaci-primary">
                        <i class="bi bi-send me-1"></i> Envoyer la déclaration
                    </button>
                </div>
            </form>
        </div>
    </div>
</div>

@endif
@endsection

@push('scripts')
<script>
function declarerPaiement() {
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
        // ET jusqu'à couvrir au minimum aujourd'hui + 3 mois (pour voir les mois proches)
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
                // S'arrêter quand on a assez de mois libres ET qu'on est passé la date min
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
            this.moisCouverts    = JSON.parse(opt.dataset.couverts || '[]');
            this._debut          = opt.dataset.debut;
            this._buildAnnees(12); // affichage initial : 12 mois libres disponibles
        },

        formatFCFA(n) { return new Intl.NumberFormat('fr-FR').format(n); },
    };
}
</script>
@endpush
