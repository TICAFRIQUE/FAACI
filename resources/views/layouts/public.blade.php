<!DOCTYPE html>
<html lang="fr">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <meta name="csrf-token" content="{{ csrf_token() }}">
    <title>@yield('title', "FAACI — Fondation AIESEC Alumni Côte d'Ivoire")</title>
    <meta name="description" content="@yield('description', 'Le réseau des Alumni AIESEC en Côte d\'Ivoire. Connecter, collaborer, bâtir ensemble.')">

    @include('partials.faaci-styles')
    @include('partials.public-styles')
    @stack('styles')
</head>
<body>

@include('partials.navbar')

@yield('content')

@include('partials.footer')

<script src="https://cdn.jsdelivr.net/npm/bootstrap@5.3.3/dist/js/bootstrap.bundle.min.js"></script>
<script>
// Menu mobile : fermer l'offcanvas au clic sur un lien (Bootstrap bloque la navigation sur <a data-bs-dismiss>)
document.addEventListener('DOMContentLoaded', function () {
    const mobileMenu = document.getElementById('mobileMenu');
    if (!mobileMenu) return;

    mobileMenu.querySelectorAll('a[href]').forEach(function (link) {
        link.addEventListener('click', function () {
            const offcanvas = bootstrap.Offcanvas.getInstance(mobileMenu);
            if (offcanvas) offcanvas.hide();
        });
    });
});

// Navbar : effet au scroll
const navbar = document.getElementById('navbar');
window.addEventListener('scroll', () => {
    if (window.scrollY > 30) navbar.classList.add('scrolled');
    else navbar.classList.remove('scrolled');
});
if (window.scrollY > 30) navbar.classList.add('scrolled');

// Apparition au scroll
const fadeObserver = new IntersectionObserver((entries) => {
    entries.forEach(entry => {
        if (entry.isIntersecting) {
            entry.target.classList.add('visible');
            fadeObserver.unobserve(entry.target);
        }
    });
}, { threshold: 0.1, rootMargin: '0px 0px -40px 0px' });
document.querySelectorAll('.fade-in').forEach(el => fadeObserver.observe(el));

// Compteurs animés
function animateCounter(el, target, duration = 2000) {
    const start = performance.now();
    const update = (time) => {
        const progress = Math.min((time - start) / duration, 1);
        const eased = 1 - Math.pow(1 - progress, 3);
        el.textContent = Math.floor(eased * target).toLocaleString('fr-FR');
        if (progress < 1) requestAnimationFrame(update);
    };
    requestAnimationFrame(update);
}
const counterObserver = new IntersectionObserver((entries) => {
    entries.forEach(entry => {
        if (entry.isIntersecting) {
            const el = entry.target;
            animateCounter(el, parseInt(el.dataset.target, 10));
            counterObserver.unobserve(el);
        }
    });
}, { threshold: 0.5 });
document.querySelectorAll('.counter[data-target]').forEach(el => counterObserver.observe(el));

// Retour en haut
const backToTop = document.getElementById('backToTop');
window.addEventListener('scroll', () => {
    if (window.scrollY > 400) backToTop.classList.add('visible');
    else backToTop.classList.remove('visible');
});
backToTop.addEventListener('click', (e) => {
    e.preventDefault();
    window.scrollTo({ top: 0, behavior: 'smooth' });
});

// Carrousel héro (page d'accueil uniquement)
const heroCarousel = document.getElementById('heroCarousel');
if (heroCarousel) {
    const progressBar = document.getElementById('heroProgressBar');
    const SLIDE_DURATION = 6000;
    let progressStart = performance.now();
    let paused = false;

    function updateProgress(now) {
        if (!paused) {
            const elapsed = now - progressStart;
            const pct = Math.min((elapsed / SLIDE_DURATION) * 100, 100);
            progressBar.style.width = pct + '%';
        }
        requestAnimationFrame(updateProgress);
    }
    requestAnimationFrame(updateProgress);

    heroCarousel.addEventListener('slide.bs.carousel', () => {
        progressStart = performance.now();
        progressBar.style.width = '0%';
    });
    heroCarousel.addEventListener('mouseenter', () => { paused = true; });
    heroCarousel.addEventListener('mouseleave', () => {
        paused = false;
        progressStart = performance.now() - (parseFloat(progressBar.style.width) / 100) * SLIDE_DURATION;
    });
}
</script>
{{-- Scroll-spy : liens actifs au scroll (sections de la page d'accueil) --}}
<script>
(function () {
    // Sections de la page d'accueil et leur correspondance data-section
    const SECTION_IDS = ['about', 'activites', 'evenements', 'actualites', 'contact'];

    // Collect les sections présentes sur cette page
    const sections = SECTION_IDS
        .map(id => document.getElementById(id))
        .filter(Boolean);

    if (!sections.length) return; // Pas sur la page d'accueil — rien à faire

    const navbar   = document.getElementById('navbar');
    const mobile   = document.getElementById('mobileMenu');
    const OFFSET   = 100; // hauteur navbar + marge

    // Sélectionne tous les liens portant data-section dans desktop et mobile
    function getLinks(section) {
        const sel = `[data-section="${section}"]`;
        return [
            ...document.querySelectorAll(`#navbar ${sel}`),
            ...document.querySelectorAll(`#mobileMenu ${sel}`),
        ];
    }

    // Retire active de tous les liens scroll-sensibles, puis active la cible
    function setActive(sectionId) {
        const scrollTargets = ['accueil', ...SECTION_IDS];

        scrollTargets.forEach(id => {
            getLinks(id).forEach(el => {
                el.classList.remove('active');
                // Mobile : retire aussi fw-bold ajouté dynamiquement
                if (el.classList.contains('nav-mobile-link')) {
                    el.classList.remove('text-faaci-navy-bold');
                }
            });
        });

        const target = sectionId ?? 'accueil';
        getLinks(target).forEach(el => {
            el.classList.add('active');
        });
    }

    function getCurrentSection() {
        const scrollY = window.scrollY + OFFSET;
        let current = null;
        for (const section of sections) {
            if (section.offsetTop <= scrollY) {
                current = section.id;
            }
        }
        return current; // null = en haut (accueil)
    }

    // Lance à chaque scroll
    window.addEventListener('scroll', () => setActive(getCurrentSection()), { passive: true });

    // Lance immédiatement pour l'état initial (cas du rechargement à mi-page)
    setActive(getCurrentSection());
})();
</script>

@stack('scripts')
</body>
</html>
