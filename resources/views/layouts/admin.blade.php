<!DOCTYPE html>
<html lang="fr">
<head>
<meta charset="UTF-8">
<meta name="viewport" content="width=device-width, initial-scale=1.0">
<meta name="csrf-token" content="{{ csrf_token() }}">
<title>@yield('title', 'Administration') — FAACI</title>
@include('partials.faaci-styles')
<link rel="stylesheet" href="https://cdn.jsdelivr.net/npm/datatables.net-bs5@1.13.8/css/dataTables.bootstrap5.min.css">
<link rel="stylesheet" href="https://cdn.jsdelivr.net/npm/datatables.net-responsive-bs5@2.5.0/css/responsive.bootstrap5.min.css">
<link rel="stylesheet" href="https://cdn.jsdelivr.net/npm/summernote@0.9.0/dist/summernote-bs5.min.css">
<script defer src="https://cdn.jsdelivr.net/npm/alpinejs@3.14.1/dist/cdn.min.js"></script>
<style>
    .admin-sidebar {
        width: 260px;
        height: 100vh;
        background: var(--faaci-navy);
        position: fixed;
        top: 0;
        left: 0;
        z-index: 1040;
        overflow-y: auto;
        transition: transform 0.25s ease;
    }
    .admin-sidebar-header { padding: 1.25rem 1.25rem 0.5rem; }
    .admin-nav-section-title {
        font-size: 0.7rem;
        text-transform: uppercase;
        letter-spacing: 0.12em;
        color: rgba(255,255,255,0.4);
        padding: 1rem 1.25rem 0.4rem;
    }
    .admin-nav-link {
        color: rgba(255,255,255,0.7);
        padding: 0.55rem 1.25rem;
        font-size: 0.92rem;
        font-weight: 500;
        text-decoration: none;
        border-left: 3px solid transparent;
        transition: all 0.15s;
    }
    .admin-nav-link:hover { color: #fff; background: rgba(255,255,255,0.05); }
    .admin-nav-link.active {
        color: #fff;
        background: rgba(255,255,255,0.08);
        border-left-color: var(--faaci-steel);
    }
    .admin-nav-link i { font-size: 1.05rem; width: 1.25rem; text-align: center; }
    .admin-content { margin-left: 260px; min-height: 100vh; }
    .admin-topbar {
        background: #fff;
        border-bottom: 1px solid var(--faaci-border);
        padding: 0.75rem 1.5rem;
    }
    .admin-sidebar-footer { padding: 0.75rem 1.25rem 1.25rem; }
    @media (max-width: 991.98px) {
        .admin-sidebar { transform: translateX(-100%); }
        .admin-sidebar.show { transform: translateX(0); }
        .admin-content { margin-left: 0; }
    }

    /* ── Tableaux statiques responsive mobile ── */
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

    /* ── DataTables : responsive child row ── */
    .dtr-details li { border-bottom: 1px solid #f0f0f0; padding: 0.35rem 0; }
    .dtr-title { font-weight: 600; color: #6c757d; font-size: 0.8rem; text-transform: uppercase; letter-spacing:.04em; }
    .dtr-data { font-size: 0.88rem; }

    /* Alpine.js : cacher les éléments x-cloak avant initialisation */
    [x-cloak] { display: none !important; }
</style>
@stack('styles')
</head>
<body class="bg-faaci-gray">

<aside class="admin-sidebar d-flex flex-column" id="adminSidebar">
    <div class="admin-sidebar-header">
        <a href="{{ route('admin.dashboard') }}" class="d-inline-block mb-3 text-decoration-none">
            <x-faaci-logo/>
        </a>
    </div>

    <nav class="flex-grow-1 d-flex flex-column">
        <x-admin-nav-link route="admin.dashboard" icon="bi-speedometer2">Tableau de bord</x-admin-nav-link>

        <div class="admin-nav-section-title">Membres</div>
        <x-admin-nav-link route="admin.membres.index" active="admin.membres.*" icon="bi-people-fill">
            Gestion des membres
            @php $nbEnAttente = \App\Models\User::role('membre')->where('statut', \App\Models\User::STATUT_EN_ATTENTE)->count(); @endphp
            @if ($nbEnAttente > 0)
                ({{ $nbEnAttente }})
            @endif
        </x-admin-nav-link>

        @hasrole('super_admin')
        <x-admin-nav-link route="admin.administrateurs.index" active="admin.administrateurs.*" icon="bi-shield-lock">
            Administrateurs
        </x-admin-nav-link>
        @endhasrole

        <div class="admin-nav-section-title">Financement</div>
        <x-admin-nav-link route="admin.projets.index" active="admin.projets.*" icon="bi-lightbulb">Projets</x-admin-nav-link>
        <x-admin-nav-link route="admin.contributions.index" active="admin.contributions.*" icon="bi-cash-stack">Investissements</x-admin-nav-link>
        <x-admin-nav-link route="admin.dons.index" active="admin.dons.*" icon="bi-gift">Dons à la fondation</x-admin-nav-link>

        <div class="admin-nav-section-title">Cotisations</div>
        <x-admin-nav-link route="admin.cotisations.paiements.index" active="admin.cotisations.paiements.*" icon="bi-wallet2">
            Paiements
            @php $nbCotPending = \App\Models\PaiementCotisation::where('statut','en_attente')->count(); @endphp
            @if ($nbCotPending > 0)<span class="badge bg-warning text-dark ms-auto" style="font-size:.6rem;">{{ $nbCotPending }}</span>@endif
        </x-admin-nav-link>
        <x-admin-nav-link route="admin.cotisations.types.index" active="admin.cotisations.types.*" icon="bi-sliders">Paramétrage</x-admin-nav-link>

        <div class="admin-nav-section-title">Annuaire Alumni</div>
        <x-admin-nav-link route="admin.entreprises.index" active="admin.entreprises.*" icon="bi-building">Entreprises Alumni</x-admin-nav-link>

        <div class="admin-nav-section-title">Communauté</div>
        <x-admin-nav-link route="admin.evenements.index" active="admin.evenements.*" icon="bi-calendar-event">Événements</x-admin-nav-link>
        <x-admin-nav-link route="admin.emplois.index" active="admin.emplois.*" icon="bi-briefcase">Offres d'emploi</x-admin-nav-link>
        <x-admin-nav-link route="admin.annonces.index" active="admin.annonces.*" icon="bi-megaphone">Annonces membres</x-admin-nav-link>
        <x-admin-nav-link route="admin.competitions.index" active="admin.competitions.*" icon="bi-trophy">
            Compétitions
            @php $nbCompOuvertes = \App\Models\Competition::where('statut','ouverte')->count(); @endphp
            @if ($nbCompOuvertes > 0)<span class="badge bg-success ms-auto" style="font-size:.6rem;">{{ $nbCompOuvertes }}</span>@endif
        </x-admin-nav-link>

        <div class="admin-nav-section-title">Système</div>
        <x-admin-nav-link route="admin.notifications.index" active="admin.notifications.*" icon="bi-bell">
            Notifications
            @php $nbNotifsAdmin = auth()->user()->unreadNotifications()->count(); @endphp
            @if ($nbNotifsAdmin > 0)
                <span class="badge bg-danger ms-auto" style="font-size:.6rem;">{{ $nbNotifsAdmin }}</span>
            @endif
        </x-admin-nav-link>

        <div class="admin-nav-section-title">Site vitrine</div>
        <x-admin-nav-link route="admin.slides.index" active="admin.slides.*" icon="bi-images">Slider (Hero)</x-admin-nav-link>
        <x-admin-nav-link route="admin.accueil.edit" active="admin.accueil.*" icon="bi-house-door">Page d'accueil</x-admin-nav-link>
        <x-admin-nav-link route="admin.apropos.edit" active="admin.apropos.*" icon="bi-info-circle">À propos</x-admin-nav-link>
        <x-admin-nav-link route="admin.valeurs.index" active="admin.valeurs.*" icon="bi-gem">Valeurs</x-admin-nav-link>
        <x-admin-nav-link route="admin.activites.index" active="admin.activites.*" icon="bi-layers">Activités</x-admin-nav-link>
        <x-admin-nav-link route="admin.equipe.index" active="admin.equipe.*" icon="bi-people">Équipe dirigeante</x-admin-nav-link>
        <x-admin-nav-link route="admin.articles.index" active="admin.articles.*" icon="bi-newspaper">Actualités</x-admin-nav-link>
        <x-admin-nav-link route="admin.parametres.edit" active="admin.parametres.*" icon="bi-gear">Paramètres du site</x-admin-nav-link>
    </nav>

    <div class="admin-sidebar-footer">
        <a href="{{ url('/') }}" target="_blank" class="admin-nav-link d-flex align-items-center gap-2 px-0 mb-2">
            <i class="bi bi-box-arrow-up-right"></i><span>Voir le site</span>
        </a>
        <form method="POST" action="{{ route('logout') }}">
            @csrf
            <button type="submit" class="btn btn-sm btn-faaci-outline-white w-100">
                <i class="bi bi-box-arrow-left me-1"></i> Déconnexion
            </button>
        </form>
    </div>
</aside>

<div class="admin-content">
    <header class="admin-topbar d-flex align-items-center justify-content-between">
        <button class="btn btn-sm btn-outline-secondary d-lg-none" type="button"
                onclick="document.getElementById('adminSidebar').classList.toggle('show')">
            <i class="bi bi-list"></i>
        </button>
        <h1 class="h5 fw-bold text-faaci-navy mb-0">@yield('page-title', 'Administration')</h1>
        <div class="d-flex align-items-center gap-3">
            @php
                $notificationsNonLues = auth()->user()->unreadNotifications()->latest()->take(5)->get();
                $nombreNonLues = auth()->user()->unreadNotifications()->count();
                $dropdownTypesConfig = [
                    'projet_soumis'           => ['label' => 'Projet',         'couleur' => 'warning',   'icon' => 'bi-lightbulb'],
                    'promesse_investissement'  => ['label' => 'Investissement', 'couleur' => 'success',   'icon' => 'bi-cash-stack'],
                    'nouveau_don'             => ['label' => 'Don',             'couleur' => 'info',      'icon' => 'bi-gift'],
                    'entreprise_soumise'      => ['label' => 'Entreprise',      'couleur' => 'primary',   'icon' => 'bi-building'],
                    'offre_emploi_soumise'    => ['label' => 'Offre d\'emploi', 'couleur' => 'secondary', 'icon' => 'bi-briefcase'],
                    'candidature_emploi'      => ['label' => 'Candidature',     'couleur' => 'primary',   'icon' => 'bi-person-check'],
                    'candidature_competition' => ['label' => 'Compétition',     'couleur' => 'warning',   'icon' => 'bi-trophy'],
                    'nouvelle_demande'        => ['label' => 'Adhésion',        'couleur' => 'danger',    'icon' => 'bi-person-plus'],
                ];
            @endphp
            <div class="dropdown">
                <button class="btn btn-sm btn-light position-relative" type="button" data-bs-toggle="dropdown" aria-expanded="false">
                    <i class="bi bi-bell"></i>
                    @if ($nombreNonLues > 0)
                        <span class="position-absolute top-0 start-100 translate-middle badge rounded-pill bg-danger" style="font-size:0.6rem;">
                            {{ $nombreNonLues }}
                        </span>
                    @endif
                </button>
                <ul class="dropdown-menu dropdown-menu-end shadow-sm p-0 overflow-hidden" style="min-width:320px;">
                    <li class="px-3 py-2 d-flex justify-content-between align-items-center border-bottom bg-light">
                        <span class="fw-semibold small">Notifications non lues</span>
                        @if ($nombreNonLues > 0)
                            <form method="POST" action="{{ route('admin.notifications.tout-lire') }}" class="m-0">
                                @csrf @method('PATCH')
                                <button type="submit" class="btn btn-sm btn-outline-secondary py-0 px-2" style="font-size:.72rem;">
                                    <i class="bi bi-check2-all me-1"></i>Tout lire
                                </button>
                            </form>
                        @endif
                    </li>
                    @forelse ($notificationsNonLues as $notif)
                        @php
                            $dt   = $notif->data;
                            $dcfg = $dropdownTypesConfig[$dt['type'] ?? ''] ?? ['label' => 'Système', 'couleur' => 'secondary', 'icon' => 'bi-bell'];
                        @endphp
                        <li class="border-bottom">
                            <form method="POST" action="{{ route('admin.notifications.lue', $notif->id) }}">
                                @csrf @method('PATCH')
                                <button type="submit" class="w-100 text-start px-3 py-2 border-0 bg-transparent d-flex gap-2 align-items-start" style="cursor:pointer;">
                                    <span class="badge text-bg-{{ $dcfg['couleur'] }} flex-shrink-0 mt-1 d-flex align-items-center justify-content-center"
                                          style="width:28px;height:28px;border-radius:6px;font-size:.85rem;">
                                        <i class="bi {{ $dcfg['icon'] }}"></i>
                                    </span>
                                    <div class="flex-grow-1 overflow-hidden">
                                        <div class="d-flex align-items-center gap-1 mb-1">
                                            <span class="badge text-bg-{{ $dcfg['couleur'] }}" style="font-size:.6rem;">{{ $dcfg['label'] }}</span>
                                        </div>
                                        <div class="small fw-semibold text-dark" style="white-space:normal;line-height:1.3;">{{ $dt['titre'] ?? '' }}</div>
                                        <div class="text-muted" style="font-size:.75rem;">{{ $notif->created_at->diffForHumans() }}</div>
                                    </div>
                                </button>
                            </form>
                        </li>
                    @empty
                        <li><span class="d-block text-muted small text-center py-3">Aucune nouvelle notification.</span></li>
                    @endforelse
                    <li class="border-top">
                        <a href="{{ route('admin.notifications.index') }}" class="d-block text-center small py-2 text-faaci-steel text-decoration-none">
                            <i class="bi bi-list-ul me-1"></i> Voir toutes les notifications
                        </a>
                    </li>
                </ul>
            </div>
            <span class="text-muted small d-none d-sm-inline">{{ auth()->user()->nom_complet }}</span>
        </div>
    </header>

    <main class="container-fluid p-3 p-md-4">
        @if (session('status'))
            <div class="alert alert-success alert-dismissible fade show">
                <i class="bi bi-check-circle me-2"></i>{{ session('status') }}
                <button type="button" class="btn-close" data-bs-dismiss="alert"></button>
            </div>
        @endif
        @if (session('error'))
            <div class="alert alert-danger alert-dismissible fade show">
                <i class="bi bi-exclamation-circle me-2"></i>{{ session('error') }}
                <button type="button" class="btn-close" data-bs-dismiss="alert"></button>
            </div>
        @endif
        @if ($errors->any())
            <div class="alert alert-danger alert-dismissible fade show">
                <button type="button" class="btn-close" data-bs-dismiss="alert"></button>
                <ul class="mb-0">
                    @foreach ($errors->all() as $error)
                        <li>{{ $error }}</li>
                    @endforeach
                </ul>
            </div>
        @endif

        @yield('content')
    </main>
</div>

<script src="https://cdn.jsdelivr.net/npm/jquery@3.7.1/dist/jquery.min.js"></script>
<script src="https://cdn.jsdelivr.net/npm/datatables.net@1.13.8/js/jquery.dataTables.min.js"></script>
<script src="https://cdn.jsdelivr.net/npm/datatables.net-bs5@1.13.8/js/dataTables.bootstrap5.min.js"></script>
<script src="https://cdn.jsdelivr.net/npm/datatables.net-responsive@2.5.0/js/dataTables.responsive.min.js"></script>
<script src="https://cdn.jsdelivr.net/npm/datatables.net-responsive-bs5@2.5.0/js/responsive.bootstrap5.min.js"></script>
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
            ['view',   ['codeview','fullscreen']],
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
