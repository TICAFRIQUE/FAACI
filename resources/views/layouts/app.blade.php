<!DOCTYPE html>
<html lang="fr">
<head>
<meta charset="UTF-8">
<meta name="viewport" content="width=device-width, initial-scale=1.0">
<title>@yield('title', 'Espace membre') — FAACI</title>
@include('partials.faaci-styles')
<script defer src="https://cdn.jsdelivr.net/npm/alpinejs@3.14.1/dist/cdn.min.js"></script>
<style>
    [x-cloak] { display: none !important; }
    .membre-sidebar {
        width: 240px;
        height: 100vh;
        background: var(--faaci-navy);
        position: fixed;
        top: 0;
        left: 0;
        z-index: 1040;
        overflow-y: auto;
        transition: transform 0.25s ease;
    }
    .membre-sidebar-header { padding: 1.1rem 1.1rem 0.5rem; border-bottom: 1px solid rgba(255,255,255,0.07); }
    .membre-nav-section-title {
        font-size: 0.68rem;
        text-transform: uppercase;
        letter-spacing: 0.12em;
        color: rgba(255,255,255,0.35);
        padding: 1rem 1.1rem 0.35rem;
    }
    .membre-nav-link {
        color: rgba(255,255,255,0.7);
        padding: 0.5rem 1.1rem;
        font-size: 0.9rem;
        font-weight: 500;
        text-decoration: none;
        border-left: 3px solid transparent;
        transition: all 0.15s;
        display: flex;
        align-items: center;
        gap: 0.5rem;
    }
    .membre-nav-link:hover { color: #fff; background: rgba(255,255,255,0.06); }
    .membre-nav-link.active { color: #fff; background: rgba(255,255,255,0.08); border-left-color: var(--faaci-steel); }
    .membre-nav-link i { font-size: 1rem; width: 1.2rem; text-align: center; }
    .membre-content { margin-left: 240px; min-height: 100vh; }
    .membre-topbar {
        background: #fff;
        border-bottom: 1px solid var(--faaci-border);
        padding: 0.65rem 1.5rem;
        position: sticky;
        top: 0;
        z-index: 1030;
    }
    .membre-sidebar-footer { padding: 0.75rem 1.1rem 1.1rem; border-top: 1px solid rgba(255,255,255,0.07); margin-top: auto; }
    .completeness-bar { height: 5px; border-radius: 3px; background: rgba(255,255,255,0.12); }
    .completeness-bar-fill { height: 5px; border-radius: 3px; background: var(--faaci-steel); transition: width 0.3s; }
    @media (max-width: 991.98px) {
        .membre-sidebar { transform: translateX(-100%); }
        .membre-sidebar.show { transform: translateX(0); }
        .membre-content { margin-left: 0; }
    }

    /* ── Tableaux responsive mobile (card style) ── */
    @media (max-width: 575.98px) {
        .table-mobile-cards thead { display: none; }
        .table-mobile-cards tbody tr {
            display: block;
            border: 1px solid var(--faaci-border);
            border-radius: 10px;
            margin-bottom: 0.75rem;
            padding: 0.5rem 0.25rem;
            background: #fff;
        }
        .table-mobile-cards tbody td {
            display: flex;
            justify-content: space-between;
            align-items: center;
            border: none;
            padding: 0.3rem 0.75rem;
            font-size: 0.85rem;
        }
        .table-mobile-cards tbody td::before {
            content: attr(data-label);
            font-weight: 600;
            color: #6c757d;
            font-size: 0.75rem;
            text-transform: uppercase;
            letter-spacing: .04em;
            flex-shrink: 0;
            margin-right: 0.5rem;
        }
        .table-mobile-cards tbody td:last-child {
            justify-content: flex-end;
            padding-top: 0.5rem;
            margin-top: 0.25rem;
            border-top: 1px solid var(--faaci-border);
        }
        .table-mobile-cards tbody td:last-child::before { display: none; }
    }
</style>
<link rel="stylesheet" href="https://cdn.jsdelivr.net/npm/summernote@0.9.0/dist/summernote-bs5.min.css">
@stack('styles')
</head>
<body class="bg-faaci-gray">

{{-- Sidebar --}}
<aside class="membre-sidebar d-flex flex-column" id="membreSidebar">
    <div class="membre-sidebar-header">
        <a href="{{ route('accueil') }}" class="text-decoration-none d-flex align-items-center gap-2 mb-2">
            <x-faaci-logo />
        </a>
        @php $user = auth()->user(); @endphp
        <div class="d-flex align-items-center gap-2 mt-2 pb-2">
            @if ($user->photo_url)
                <img src="{{ $user->photo_url }}" alt="" class="rounded-circle" style="width:36px;height:36px;object-fit:cover;">
            @else
                <div class="rounded-circle d-flex align-items-center justify-content-center text-white fw-bold"
                     style="width:36px;height:36px;background:var(--faaci-steel);font-size:0.8rem;">
                    {{ mb_strtoupper(mb_substr($user->prenom, 0, 1)) }}{{ mb_strtoupper(mb_substr($user->nom, 0, 1)) }}
                </div>
            @endif
            <div class="overflow-hidden">
                <div class="text-white fw-semibold text-truncate" style="font-size:0.85rem;max-width:160px;">{{ $user->nom_complet }}</div>
                <div class="text-white-50" style="font-size:0.72rem;">Membre actif</div>
            </div>
        </div>
        @php $c = $user->completeness; @endphp
        <div class="completeness-bar mt-1">
            <div class="completeness-bar-fill" style="width:{{ $c }}%"></div>
        </div>
        <div class="text-white-50 mt-1" style="font-size:0.68rem;">Profil complété à {{ $c }} %</div>
    </div>

    <nav class="flex-grow-1 py-2">
        <p class="membre-nav-section-title">Navigation</p>
        <a href="{{ route('membre.dashboard') }}"
           class="membre-nav-link {{ request()->routeIs('membre.dashboard') ? 'active' : '' }}">
            <i class="bi bi-speedometer2"></i> Tableau de bord
        </a>

        <a href="{{ route('membre.cotisations.index') }}"
           class="membre-nav-link {{ request()->routeIs('membre.cotisations*') ? 'active' : '' }}">
            <i class="bi bi-wallet2"></i> Mes cotisations
            @php
                $cotRetard = \App\Models\Cotisation::where('utilisateur_id', auth()->id())
                    ->where('statut', 'en_retard')->count();
            @endphp
            @if ($cotRetard > 0)
                <span class="badge bg-danger ms-auto" style="font-size:.6rem;">{{ $cotRetard }}</span>
            @endif
        </a>

        <p class="membre-nav-section-title">Financement</p>
        <a href="{{ route('membre.projets.index') }}"
           class="membre-nav-link {{ request()->routeIs('membre.projets.index') ? 'active' : '' }}">
            <i class="bi bi-lightbulb"></i> Projets
        </a>
        <a href="{{ route('membre.projets.mes-projets') }}"
           class="membre-nav-link {{ request()->routeIs('membre.projets.mes-projets') ? 'active' : '' }}">
            <i class="bi bi-folder2"></i> Mes projets
        </a>
        <a href="{{ route('membre.contributions.index') }}"
           class="membre-nav-link {{ request()->routeIs('membre.contributions*') ? 'active' : '' }}">
            <i class="bi bi-cash-stack"></i> Mes investissements
        </a>
        <a href="{{ route('membre.dons.index') }}"
           class="membre-nav-link {{ request()->routeIs('membre.dons*') ? 'active' : '' }}">
            <i class="bi bi-gift"></i> Mes dons
        </a>

        <p class="membre-nav-section-title">Annuaire</p>
        <a href="{{ route('membre.annuaire') }}"
           class="membre-nav-link {{ request()->routeIs('membre.annuaire') || request()->routeIs('membre.annuaire.show') ? 'active' : '' }}">
            <i class="bi bi-people"></i> Membres
        </a>
        <a href="{{ route('membre.entreprises.index') }}"
           class="membre-nav-link {{ request()->routeIs('membre.entreprises.index') || request()->routeIs('membre.entreprises.show') ? 'active' : '' }}">
            <i class="bi bi-building"></i> Entreprises Alumni
        </a>
        <a href="{{ route('membre.entreprises.mes-entreprises') }}"
           class="membre-nav-link {{ request()->routeIs('membre.entreprises.mes-entreprises') || request()->routeIs('membre.entreprises.create') || request()->routeIs('membre.entreprises.edit') ? 'active' : '' }}">
            <i class="bi bi-briefcase"></i> Mes entreprises
        </a>

        <p class="membre-nav-section-title">Compétitions</p>
        <a href="{{ route('membre.competitions.index') }}"
           class="membre-nav-link {{ request()->routeIs('membre.competitions.index') || request()->routeIs('membre.competitions.show') ? 'active' : '' }}">
            <i class="bi bi-trophy"></i> Appels à projets
        </a>
        <a href="{{ route('membre.competitions.mes-candidatures') }}"
           class="membre-nav-link {{ request()->routeIs('membre.competitions.mes-candidatures') ? 'active' : '' }}">
            <i class="bi bi-send"></i> Mes candidatures
        </a>

        <p class="membre-nav-section-title">Opportunités</p>
        <a href="{{ route('membre.evenements.index') }}"
           class="membre-nav-link {{ request()->routeIs('membre.evenements*') ? 'active' : '' }}">
            <i class="bi bi-calendar-event"></i> Événements
        </a>
        <a href="{{ route('membre.emplois.index') }}"
           class="membre-nav-link {{ request()->routeIs('membre.emplois.index') || request()->routeIs('membre.emplois.show') ? 'active' : '' }}">
            <i class="bi bi-briefcase"></i> Offres d'emploi
        </a>
        <a href="{{ route('membre.emplois.mes-offres') }}"
           class="membre-nav-link {{ request()->routeIs('membre.emplois.mes-offres') || request()->routeIs('membre.emplois.create') || request()->routeIs('membre.emplois.edit') ? 'active' : '' }}">
            <i class="bi bi-megaphone"></i> Mes offres
        </a>
        <a href="{{ route('membre.emplois.mes-candidatures') }}"
           class="membre-nav-link {{ request()->routeIs('membre.emplois.mes-candidatures') ? 'active' : '' }}">
            <i class="bi bi-send"></i> Mes candidatures
        </a>

        <p class="membre-nav-section-title">Mon compte</p>
        <a href="{{ route('membre.notifications.index') }}"
           class="membre-nav-link {{ request()->routeIs('membre.notifications*') ? 'active' : '' }}">
            <i class="bi bi-bell"></i> Notifications
            @php
                $sideUser   = auth()->user();
                $sideNotifs = $sideUser->unreadNotifications()->count()
                    + \App\Models\Annonce::actives()
                        ->when($sideUser->annonces_lues_at, fn($q) => $q->where('publiee_at', '>', $sideUser->annonces_lues_at))
                        ->count();
            @endphp
            <span id="sidebarBellBadge"
                  class="badge bg-danger ms-auto{{ $sideNotifs > 0 ? '' : ' d-none' }}"
                  style="font-size:.6rem;">{{ $sideNotifs > 99 ? '99+' : $sideNotifs }}</span>
        </a>
        <a href="{{ route('membre.profil') }}"
           class="membre-nav-link {{ request()->routeIs('membre.profil*') ? 'active' : '' }}">
            <i class="bi bi-person"></i> Mon profil
        </a>
        <a href="{{ route('membre.mot-de-passe.edit') }}"
           class="membre-nav-link {{ request()->routeIs('membre.mot-de-passe*') ? 'active' : '' }}">
            <i class="bi bi-lock"></i> Mot de passe
        </a>
    </nav>

    <div class="membre-sidebar-footer">
        <form method="POST" action="{{ route('logout') }}">
            @csrf
            <button type="submit" class="membre-nav-link w-100 border-0 bg-transparent text-start cursor-pointer">
                <i class="bi bi-box-arrow-left"></i> Déconnexion
            </button>
        </form>
    </div>
</aside>

{{-- Overlay mobile --}}
<div class="d-lg-none position-fixed top-0 start-0 w-100 h-100 bg-dark bg-opacity-50 d-none"
     id="sidebarOverlay" style="z-index:1039;" onclick="closeSidebar()"></div>

{{-- Contenu principal --}}
<div class="membre-content">
    <header class="membre-topbar d-flex align-items-center gap-3">
        <button class="btn btn-sm btn-outline-secondary d-lg-none" onclick="openSidebar()">
            <i class="bi bi-list fs-5"></i>
        </button>
        <nav aria-label="breadcrumb" class="me-auto">
            <ol class="breadcrumb mb-0 small">
                <li class="breadcrumb-item"><a href="{{ route('membre.dashboard') }}">Accueil</a></li>
                @yield('breadcrumb')
            </ol>
        </nav>
        <div class="d-flex align-items-center gap-2">

            {{-- Cloche notifications --}}
            @php
                $user = auth()->user();
                $nbNotifs   = $user->unreadNotifications()->count();
                $nbAnnonces = \App\Models\Annonce::actives()
                    ->when($user->annonces_lues_at, fn($q) => $q->where('publiee_at', '>', $user->annonces_lues_at))
                    ->count();
                $nbTotal = $nbNotifs + $nbAnnonces;
            @endphp

            <div class="position-relative"
                 x-data="bellDropdown('{{ route('membre.notifications.dropdown') }}', '{{ route('membre.notifications.count') }}')"
                 @click.outside="close()">

                <button @click="toggle()"
                        class="btn btn-sm btn-light border position-relative"
                        title="Notifications">
                    <i id="bellIcon" class="bi bi-bell{{ $nbTotal > 0 ? '-fill text-faaci-navy' : '' }}"></i>
                    <span id="bellBadge"
                          class="position-absolute top-0 start-100 translate-middle badge rounded-pill bg-danger{{ $nbTotal > 0 ? '' : ' d-none' }}"
                          style="font-size:.6rem;">{{ $nbTotal > 99 ? '99+' : $nbTotal }}</span>
                </button>

                <div x-show="open" x-cloak x-transition
                     class="position-absolute end-0 mt-2 bg-white border rounded-3 shadow-sm"
                     style="width:320px;z-index:1060;">
                    <div class="d-flex justify-content-between align-items-center px-3 py-2 border-bottom">
                        <span class="fw-semibold small">Notifications</span>
                        <form method="POST" action="{{ route('membre.notifications.tout-lire') }}" class="m-0">
                            @csrf @method('PATCH')
                            <button class="btn btn-sm btn-outline-secondary py-0 px-2" style="font-size:.72rem;">
                                <i class="bi bi-check2-all me-1"></i>Tout lire
                            </button>
                        </form>
                    </div>

                    {{-- Contenu chargé en AJAX --}}
                    <div id="bellDropdownBody" style="max-height:340px;overflow-y:auto;">
                        <div class="text-center text-muted py-4 small">
                            <i class="bi bi-arrow-repeat d-block fs-3 mb-1 opacity-50"></i>
                            Chargement…
                        </div>
                    </div>

                    <div class="px-3 py-2 border-top text-center">
                        <a href="{{ route('membre.notifications.index') }}" class="small text-faaci-navy fw-semibold text-decoration-none">
                            Voir toutes les notifications
                        </a>
                    </div>
                </div>
            </div>

            <a href="{{ route('membre.profil') }}" class="text-decoration-none text-secondary small d-none d-sm-block">
                {{ auth()->user()->nom_complet }}
            </a>
        </div>
    </header>

    <main class="p-3 p-md-4">
        @if (session('status'))
            <div class="alert alert-success alert-dismissible fade show" role="alert">
                {{ session('status') }}
                <button type="button" class="btn-close" data-bs-dismiss="alert"></button>
            </div>
        @endif
        @if (session('error'))
            <div class="alert alert-danger alert-dismissible fade show" role="alert">
                {{ session('error') }}
                <button type="button" class="btn-close" data-bs-dismiss="alert"></button>
            </div>
        @endif

        @yield('content')
    </main>
</div>

<script src="https://cdn.jsdelivr.net/npm/jquery@3.7.1/dist/jquery.min.js"></script>
<script src="https://cdn.jsdelivr.net/npm/bootstrap@5.3.3/dist/js/bootstrap.bundle.min.js"></script>
<script src="https://cdn.jsdelivr.net/npm/summernote@0.9.0/dist/summernote-bs5.min.js"></script>
<script src="https://cdn.jsdelivr.net/npm/summernote@0.9.0/dist/lang/summernote-fr-FR.min.js"></script>
<script>
$(function () {
    $('.rich-editor-full').summernote({
        lang: 'fr-FR',
        height: 320,
        toolbar: [
            ['style',  ['bold','italic','underline','strikethrough','clear']],
            ['para',   ['ul','ol','paragraph']],
            ['insert', ['link','hr']],
            ['view',   ['fullscreen']],
        ],
    });
    $('.rich-editor-basic').summernote({
        lang: 'fr-FR',
        height: 160,
        toolbar: [
            ['style', ['bold','italic','underline']],
            ['para',  ['ul','ol']],
        ],
    });
});
</script>
<script>
// ── Cloche notifications (Alpine component + polling) ─────────────────
function bellDropdown(dropdownUrl, countUrl) {
    return {
        open: false,

        toggle() {
            this.open = !this.open;
            if (this.open) this.loadContent();
        },

        close() {
            this.open = false;
        },

        loadContent() {
            fetch(dropdownUrl, { headers: { 'X-Requested-With': 'XMLHttpRequest' } })
                .then(r => r.ok ? r.text() : null)
                .then(html => {
                    if (!html) return;
                    const body = document.getElementById('bellDropdownBody');
                    if (body) body.innerHTML = html;
                })
                .catch(() => {});
        },

        updateCount(n) {
            const badge   = document.getElementById('bellBadge');
            const icon    = document.getElementById('bellIcon');
            const sidebar = document.getElementById('sidebarBellBadge');
            const label   = n > 99 ? '99+' : n;
            if (badge)   { badge.textContent = label; badge.classList.toggle('d-none', n === 0); }
            if (icon)    { icon.className = n > 0 ? 'bi bi-bell-fill text-faaci-navy' : 'bi bi-bell'; }
            if (sidebar) { sidebar.textContent = label; sidebar.classList.toggle('d-none', n === 0); }
        }
    };
}

// Polling toutes les 30s
(function pollNotifications() {
    const url = '{{ route('membre.notifications.count') }}';
    function refresh() {
        fetch(url, { headers: { 'X-Requested-With': 'XMLHttpRequest' } })
            .then(r => r.ok ? r.json() : null)
            .then(data => {
                if (data) {
                    // Cherche l'instance Alpine de la cloche et met à jour le compteur
                    const el = document.querySelector('[x-data^="bellDropdown"]');
                    if (el && el._x_dataStack) {
                        el._x_dataStack[0].updateCount(data.total);
                    } else {
                        // Fallback direct DOM si Alpine pas encore initialisé
                        const n = data.total;
                        const badge   = document.getElementById('bellBadge');
                        const icon    = document.getElementById('bellIcon');
                        const sidebar = document.getElementById('sidebarBellBadge');
                        const label   = n > 99 ? '99+' : n;
                        if (badge)   { badge.textContent = label; badge.classList.toggle('d-none', n === 0); }
                        if (icon)    { icon.className = n > 0 ? 'bi bi-bell-fill text-faaci-navy' : 'bi bi-bell'; }
                        if (sidebar) { sidebar.textContent = label; sidebar.classList.toggle('d-none', n === 0); }
                    }
                }
            })
            .catch(() => {});
    }
    setInterval(refresh, 30000);
})();
</script>
<script>
function openSidebar() {
    document.getElementById('membreSidebar').classList.add('show');
    document.getElementById('sidebarOverlay').classList.remove('d-none');
}
function closeSidebar() {
    document.getElementById('membreSidebar').classList.remove('show');
    document.getElementById('sidebarOverlay').classList.add('d-none');
}
</script>
@stack('scripts')

{{-- Modal confirmation globale --}}
<div class="modal fade" id="faacConfirmModal" tabindex="-1" aria-hidden="true">
    <div class="modal-dialog modal-dialog-centered modal-sm">
        <div class="modal-content border-0 shadow">
            <div class="modal-body p-4 text-center">
                <div class="mb-3" style="font-size:2.5rem;line-height:1;">
                    <i class="bi bi-question-circle text-warning"></i>
                </div>
                <p id="faacConfirmMessage" class="fw-medium mb-4 text-dark"></p>
                <div class="d-flex gap-2 justify-content-center">
                    <button type="button" class="btn btn-outline-secondary btn-sm px-4"
                            data-bs-dismiss="modal">Annuler</button>
                    <button type="button" class="btn btn-danger btn-sm px-4"
                            id="faacConfirmOk">Confirmer</button>
                </div>
            </div>
        </div>
    </div>
</div>
<script>
(function () {
    let _pendingForm = null;
    document.addEventListener('DOMContentLoaded', function () {
        const modalEl  = document.getElementById('faacConfirmModal');
        const modal    = new bootstrap.Modal(modalEl);
        const msgEl    = document.getElementById('faacConfirmMessage');
        const okBtn    = document.getElementById('faacConfirmOk');

        document.addEventListener('submit', function (e) {
            const msg = e.target.dataset.confirm;
            if (!msg) return;
            e.preventDefault();
            e.stopPropagation();
            msgEl.textContent = msg;
            _pendingForm = e.target;
            modal.show();
        }, true);

        okBtn.addEventListener('click', function () {
            modal.hide();
            if (_pendingForm) {
                const f = _pendingForm;
                _pendingForm = null;
                delete f.dataset.confirm;
                f.submit();
            }
        });

        modalEl.addEventListener('hidden.bs.modal', function () {
            _pendingForm = null;
        });
    });
})();
</script>
</body>
</html>
