@php
    $home       = request()->routeIs('accueil') ? '' : route('accueil');
    $parametres = \App\Models\ParametreSite::tous();
    $logoUrl    = !empty($parametres['logo']) ? $parametres['logo'] : asset('images/logo.jpg');

    $isApropos  = request()->routeIs('apropos*');
    $isGalerie  = request()->routeIs('galerie*');

    if (auth()->check()) {
        $userNav = auth()->user();

        if ($userNav->hasAnyRole(['admin', 'super_admin'])) {
            $espaceUrl   = route('admin.dashboard');
            $espaceLabel = 'Administration';
            $espaceIcon  = 'bi-speedometer2';
        } elseif ($userNav->statut !== \App\Models\User::STATUT_ACTIF) {
            $espaceUrl   = route('compte.statut');
            $espaceLabel = 'Mon compte';
            $espaceIcon  = 'bi-person-circle';
        } else {
            $espaceUrl   = route('dashboard');
            $espaceLabel = 'Mon espace';
            $espaceIcon  = 'bi-speedometer2';
        }
    }
@endphp

<!-- NAVBAR -->
<nav id="navbar" class="navbar-faaci">
  <div class="container">
    <div class="d-flex align-items-center justify-content-between">

      <a href="{{ route('accueil') }}" class="d-flex align-items-center gap-2 text-decoration-none">
        <img src="{{ $logoUrl }}" alt="Logo FAACI"
             class="logo-svg rounded-circle" style="width:48px;height:48px;object-fit:cover;">
        <div class="d-none d-sm-block">
          <div class="logo-text">FAACI</div>
          <div class="logo-sub">Fondation Alumni AIESEC CI</div>
        </div>
      </a>

      {{-- Desktop --}}
      <div class="d-none d-lg-flex align-items-center gap-1">

        <a href="{{ route('accueil') }}"
           class="nav-link px-3 @if(request()->routeIs('accueil')) active @endif"
           data-section="accueil">Accueil</a>

        <div class="dropdown">
          <a class="nav-link px-3 dropdown-toggle text-decoration-none @if($isApropos) active @endif"
             href="#" id="aboutDropdown" role="button"
             data-bs-toggle="dropdown" aria-expanded="false"
             data-section="about">
            À propos
          </a>
          <ul class="dropdown-menu">
            <li><a class="dropdown-item @if(request()->routeIs('apropos.mission')) active @endif"
                   href="{{ route('apropos.mission') }}"><i class="bi bi-bullseye"></i>Mission</a></li>
            <li><a class="dropdown-item @if(request()->routeIs('apropos.vision')) active @endif"
                   href="{{ route('apropos.vision') }}"><i class="bi bi-eye"></i>Vision</a></li>
            <li><a class="dropdown-item @if(request()->routeIs('apropos.valeurs')) active @endif"
                   href="{{ route('apropos.valeurs') }}"><i class="bi bi-shield-check"></i>Valeurs</a></li>
            <li><a class="dropdown-item @if(request()->routeIs('apropos.equipe')) active @endif"
                   href="{{ route('apropos.equipe') }}"><i class="bi bi-people"></i>Équipe dirigeante</a></li>
            <li><a class="dropdown-item @if(request()->routeIs('apropos.histoire')) active @endif"
                   href="{{ route('apropos.histoire') }}"><i class="bi bi-clock-history"></i>Histoire FAACI</a></li>
          </ul>
        </div>

        <a href="{{ route('activites') }}"
           class="nav-link px-3 @if(request()->routeIs('activites')) active @endif"
           data-section="activites">Activités</a>

        <a href="{{ route('evenements.index') }}"
           class="nav-link px-3 @if(request()->routeIs('evenements*')) active @endif"
           data-section="evenements">Événements</a>

        <a href="{{ $home }}#actualites"
           class="nav-link px-3"
           data-section="actualites">Actualités</a>

        <a href="{{ route('galerie.index') }}"
           class="nav-link px-3 @if($isGalerie) active @endif"
           data-section="galerie">Galerie</a>

        <a href="{{ $home }}#contact"
           class="nav-link px-3"
           data-section="contact">Contact</a>

      </div>

      <div class="d-none d-lg-flex align-items-center gap-3">
        @auth
          <a href="{{ $espaceUrl }}" class="btn-cta-nav btn btn-faaci-white px-4 py-2 rounded-3 small">
            <i class="bi {{ $espaceIcon }} me-1"></i>{{ $espaceLabel }}
          </a>
          <form method="POST" action="{{ route('logout') }}" class="m-0">
            @csrf
            <button type="submit" class="btn-nav-logout">
              <i class="bi bi-box-arrow-right me-1"></i>Déconnexion
            </button>
          </form>
        @else
          <a href="{{ route('login') }}" class="btn-login text-white text-decoration-none small fw-medium">Connexion</a>
          <a href="{{ route('register') }}" class="btn-cta-nav btn btn-faaci-white px-4 py-2 rounded-3 small">Devenir membre</a>
        @endauth
      </div>

      <button class="d-lg-none btn p-2 border-0" type="button"
              data-bs-toggle="offcanvas" data-bs-target="#mobileMenu" aria-label="Ouvrir le menu">
        <i class="bi bi-list fs-2 text-white nav-toggle-icon"></i>
      </button>

    </div>
  </div>
</nav>

<!-- OFFCANVAS MOBILE -->
<div class="offcanvas offcanvas-end" tabindex="-1" id="mobileMenu" style="max-width: 320px;">
  <div class="offcanvas-header border-bottom">
    <div class="d-flex align-items-center gap-2">
      <img src="{{ $logoUrl }}" alt="Logo FAACI"
           class="rounded-circle" style="width:40px;height:40px;object-fit:cover;">
      <div>
        <div class="logo-text text-faaci-navy">FAACI</div>
        <div class="logo-sub" style="color: var(--faaci-silver)">Fondation Alumni AIESEC CI</div>
      </div>
    </div>
    <button type="button" class="btn-close" data-bs-dismiss="offcanvas" aria-label="Fermer"></button>
  </div>
  <div class="offcanvas-body">
    <div class="d-flex flex-column gap-1">

      <a href="{{ route('accueil') }}"
         class="nav-mobile-link text-decoration-none p-3 rounded fw-medium @if(request()->routeIs('accueil')) active @endif"
         data-section="accueil">Accueil</a>

      <div class="accordion accordion-flush" id="aboutAccordion">
        <div class="accordion-item border-0">
          <h2 class="accordion-header">
            <button class="accordion-button @if(!$isApropos) collapsed @endif bg-transparent shadow-none p-3 fw-medium text-faaci-navy"
                    type="button" data-bs-toggle="collapse" data-bs-target="#aboutCollapse"
                    data-section="about">
              À propos
            </button>
          </h2>
          <div id="aboutCollapse" class="accordion-collapse collapse @if($isApropos) show @endif"
               data-bs-parent="#aboutAccordion">
            <div class="accordion-body p-2">
              <a href="{{ route('apropos.mission') }}"
                 class="nav-mobile-sub text-decoration-none d-block p-2 ps-4 rounded text-faaci-navy small @if(request()->routeIs('apropos.mission')) fw-bold @endif">Mission</a>
              <a href="{{ route('apropos.vision') }}"
                 class="nav-mobile-sub text-decoration-none d-block p-2 ps-4 rounded text-faaci-navy small @if(request()->routeIs('apropos.vision')) fw-bold @endif">Vision</a>
              <a href="{{ route('apropos.valeurs') }}"
                 class="nav-mobile-sub text-decoration-none d-block p-2 ps-4 rounded text-faaci-navy small @if(request()->routeIs('apropos.valeurs')) fw-bold @endif">Valeurs</a>
              <a href="{{ route('apropos.equipe') }}"
                 class="nav-mobile-sub text-decoration-none d-block p-2 ps-4 rounded text-faaci-navy small @if(request()->routeIs('apropos.equipe')) fw-bold @endif">Équipe dirigeante</a>
              <a href="{{ route('apropos.histoire') }}"
                 class="nav-mobile-sub text-decoration-none d-block p-2 ps-4 rounded text-faaci-navy small @if(request()->routeIs('apropos.histoire')) fw-bold @endif">Histoire FAACI</a>
            </div>
          </div>
        </div>
      </div>

      <a href="{{ route('activites') }}"
         class="nav-mobile-link text-decoration-none p-3 rounded fw-medium @if(request()->routeIs('activites')) active @endif"
         data-section="activites">Activités</a>

      <a href="{{ route('evenements.index') }}"
         class="nav-mobile-link text-decoration-none p-3 rounded fw-medium @if(request()->routeIs('evenements*')) active @endif"
         data-section="evenements">Événements</a>

      <a href="{{ $home }}#actualites"
         class="nav-mobile-link text-decoration-none p-3 rounded fw-medium"
         data-section="actualites">Actualités</a>

      <a href="{{ route('galerie.index') }}"
         class="nav-mobile-link text-decoration-none p-3 rounded fw-medium @if($isGalerie) active @endif"
         data-section="galerie">Galerie</a>

      <a href="{{ $home }}#contact"
         class="nav-mobile-link text-decoration-none p-3 rounded fw-medium"
         data-section="contact">Contact</a>

      <hr class="my-3"/>
      @auth
        <a href="{{ $espaceUrl }}" class="btn btn-faaci-navy rounded-3 mb-2" data-bs-dismiss="offcanvas">
          <i class="bi {{ $espaceIcon }} me-1"></i>{{ $espaceLabel }}
        </a>
        <form method="POST" action="{{ route('logout') }}">
          @csrf
          <button type="submit" class="btn btn-outline-dark rounded-3 w-100">
            <i class="bi bi-box-arrow-right me-1"></i>Déconnexion
          </button>
        </form>
      @else
        <a href="{{ route('login') }}" class="btn btn-outline-dark rounded-3 mb-2">Connexion</a>
        <a href="{{ route('register') }}" class="btn btn-faaci-navy rounded-3">Devenir membre</a>
      @endauth
    </div>
  </div>
</div>
