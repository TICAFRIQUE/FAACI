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
        <a href="{{ route('membre.annuaire') }}"
           class="membre-nav-link {{ request()->routeIs('membre.annuaire*') ? 'active' : '' }}">
            <i class="bi bi-people"></i> Annuaire
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
            <i class="bi bi-cash-stack"></i> Mes contributions
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
            @if ($sideNotifs > 0)
                <span class="badge bg-danger ms-auto" style="font-size:.6rem;">{{ $sideNotifs > 99 ? '99+' : $sideNotifs }}</span>
            @endif
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

            <div class="position-relative" x-data="{ open: false }" @click.outside="open = false">
                <button @click="open = !open"
                        class="btn btn-sm btn-light border position-relative"
                        title="Notifications">
                    <i class="bi bi-bell{{ $nbTotal > 0 ? '-fill text-faaci-navy' : '' }}"></i>
                    @if ($nbTotal > 0)
                        <span class="position-absolute top-0 start-100 translate-middle badge rounded-pill bg-danger"
                              style="font-size:.6rem;">{{ $nbTotal > 99 ? '99+' : $nbTotal }}</span>
                    @endif
                </button>

                <div x-show="open" x-cloak x-transition
                     class="position-absolute end-0 mt-2 bg-white border rounded-3 shadow-sm"
                     style="width:320px;z-index:1060;">
                    <div class="d-flex justify-content-between align-items-center px-3 py-2 border-bottom">
                        <span class="fw-semibold small">Notifications</span>
                        @if ($nbTotal > 0)
                            <form method="POST" action="{{ route('membre.notifications.tout-lire') }}" class="m-0">
                                @csrf @method('PATCH')
                                <button class="btn btn-sm btn-outline-secondary py-0 px-2" style="font-size:.72rem;">
                                    <i class="bi bi-check2-all me-1"></i>Tout lire
                                </button>
                            </form>
                        @endif
                    </div>

                    <div style="max-height:340px;overflow-y:auto;">
                        {{-- Annonces non lues --}}
                        @php
                            $annoncesRecentes = \App\Models\Annonce::actives()
                                ->when($user->annonces_lues_at, fn($q) => $q->where('publiee_at', '>', $user->annonces_lues_at))
                                ->latest('publiee_at')->take(5)->get();
                        @endphp
                        @foreach ($annoncesRecentes as $annonce)
                            @php $cfg = $annonce->type_config; @endphp
                            <a href="{{ route('membre.notifications.index') }}"
                               class="d-flex gap-2 px-3 py-2 text-decoration-none border-bottom"
                               style="background:#f8f9ff;">
                                <i class="bi {{ $cfg['icone'] }} text-{{ $cfg['couleur'] }} mt-1 flex-shrink-0"></i>
                                <div>
                                    <div class="small fw-semibold text-dark">{{ $annonce->titre }}</div>
                                    <div class="text-muted" style="font-size:.75rem;">{{ $annonce->publiee_at->diffForHumans() }}</div>
                                </div>
                            </a>
                        @endforeach

                        {{-- Notifications événements --}}
                        @foreach ($user->unreadNotifications()->latest()->take(5)->get() as $notif)
                            <form method="POST" action="{{ route('membre.notifications.lue', $notif->id) }}">
                                @csrf @method('PATCH')
                                <button type="submit"
                                        class="d-flex gap-2 px-3 py-2 w-100 text-start border-bottom"
                                        style="background:#eef2ff;border:none;border-bottom:1px solid #dee2e6;cursor:pointer;transition:background .15s;"
                                        onmouseover="this.style.background='#dde3ff'"
                                        onmouseout="this.style.background='#eef2ff'">
                                    <i class="bi bi-calendar-event text-faaci-steel mt-1 flex-shrink-0"></i>
                                    <div class="flex-grow-1">
                                        <div class="small fw-semibold text-dark">{{ $notif->data['message'] }}</div>
                                        <div class="text-muted" style="font-size:.75rem;">{{ $notif->created_at->diffForHumans() }}</div>
                                    </div>
                                    <span class="badge bg-faaci-steel align-self-center flex-shrink-0" style="font-size:.6rem;">Voir</span>
                                </button>
                            </form>
                        @endforeach

                        @if ($nbTotal === 0)
                            <div class="text-center text-muted py-4 small">
                                <i class="bi bi-bell-slash d-block fs-3 mb-1 opacity-50"></i>
                                Aucune nouvelle notification
                            </div>
                        @endif
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
</body>
</html>
