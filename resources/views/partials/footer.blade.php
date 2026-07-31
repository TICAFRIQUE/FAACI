@php
    $home = request()->routeIs('accueil') ? '' : route('accueil');
    $parametres = \App\Models\ParametreSite::tous();

    if (auth()->check()) {
        $userNav = auth()->user();

        if ($userNav->hasAnyRole(['admin', 'super_admin'])) {
            $espaceUrl = route('admin.dashboard');
            $espaceLabel = 'Administration';
        } elseif ($userNav->statut !== \App\Models\User::STATUT_ACTIF) {
            $espaceUrl = route('compte.statut');
            $espaceLabel = 'Mon compte';
        } else {
            $espaceUrl = route('dashboard');
            $espaceLabel = 'Mon espace';
        }
    }
@endphp

<!-- FOOTER -->
<footer class="footer">
  <div class="container">
    <div class="row g-4 mb-4">
      <div class="col-12 col-lg-4">
        <div class="d-flex align-items-center gap-2 mb-3">
          @php $logoUrl = !empty($parametres['logo']) ? $parametres['logo'] : asset('images/logo.jpg'); @endphp
          <img src="{{ $logoUrl }}" alt="Logo FAACI"
               class="rounded-circle" style="width:48px;height:48px;object-fit:cover;flex-shrink:0;">
          <div>
            <div class="logo-text">FAACI</div>
            <div class="logo-sub">Fondation Alumni AIESEC CI</div>
          </div>
        </div>
        <p style="color: rgba(255,255,255,0.5); font-size: 0.9rem; line-height: 1.65; margin-bottom: 1.25rem;">{{ $parametres['footer_texte'] ?? '' }}</p>
        <div>
          <a href="{{ $parametres['facebook_url'] ?? '#' }}" class="social-link"><i class="bi bi-facebook"></i></a>
          <a href="{{ $parametres['linkedin_url'] ?? '#' }}" class="social-link"><i class="bi bi-linkedin"></i></a>
          <a href="{{ $parametres['whatsapp_url'] ?? '#' }}" class="social-link whatsapp"><i class="bi bi-whatsapp"></i></a>
        </div>
      </div>

      <div class="col-6 col-md-4 col-lg-3">
        <h6 class="footer-title">Navigation</h6>
        <a href="{{ $home }}#about" class="footer-link">À propos</a>
        <a href="{{ $home }}#activites" class="footer-link">Activités</a>
        <a href="{{ route('evenements.index') }}" class="footer-link">Événements</a>
        <a href="{{ $home }}#actualites" class="footer-link">Actualités</a>

      </div>

      <div class="col-6 col-md-4 col-lg-2">
        <h6 class="footer-title">Espace membre</h6>
        @auth
          <a href="{{ $espaceUrl }}" class="footer-link">{{ $espaceLabel }}</a>
          <form method="POST" action="{{ route('logout') }}" class="m-0">
            @csrf
            <button type="submit" class="footer-link bg-transparent border-0 text-start w-100">Déconnexion</button>
          </form>
        @else
          <a href="{{ route('login') }}" class="footer-link">Connexion</a>
          <a href="{{ route('register') }}" class="footer-link">Devenir membre</a>
        @endauth
      </div>

      <div class="col-12 col-md-4 col-lg-3">
        <h6 class="footer-title">Contact</h6>
        <p class="footer-link mb-1">{{ $parametres['email_contact'] ?? '' }}</p>
        <p class="footer-link mb-1">{{ $parametres['telephone'] ?? '' }}</p>
        <p class="footer-link mb-0">{{ $parametres['adresse'] ?? '' }}</p>
      </div>
    </div>

    <div class="footer-bottom d-flex flex-column flex-sm-row justify-content-between align-items-center gap-2">
      @php
          $copyrightTexte = preg_replace('/^©\s*\d{4}\s*/u', '', $parametres['footer_copyright'] ?? '');
      @endphp
      <p class="mb-0">© {{ date('Y') }} {{ $copyrightTexte }}</p>
      <div class="d-flex gap-3">
        <a href="#" class="footer-link mb-0">Mentions légales</a>
        <a href="#" class="footer-link mb-0">Confidentialité</a>
      </div>
    </div>
  </div>
</footer>

<!-- BACK TO TOP -->
<a href="#" class="back-to-top" id="backToTop"><i class="bi bi-arrow-up"></i></a>
