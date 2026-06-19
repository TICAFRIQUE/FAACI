<style>
html { scroll-behavior: smooth; }
body { background: #fff; }

.btn-faaci-white {
  background: #fff; color: var(--faaci-navy);
  border: 1px solid #fff; font-weight: 600;
  transition: all 0.2s;
}
.btn-faaci-white:hover { background: var(--faaci-light); color: var(--faaci-navy); border-color: var(--faaci-light); }

/* NAVBAR */
.navbar-faaci {
  position: fixed; top: 0; left: 0; right: 0;
  z-index: 1030; background: transparent;
  transition: background 0.3s, box-shadow 0.3s, padding 0.3s;
  padding: 1.15rem 0;
}
.navbar-faaci.scrolled {
  background: rgba(255,255,255,0.98);
  box-shadow: 0 1px 20px rgba(0,0,0,0.08);
  padding: 0.65rem 0;
}
.navbar-faaci .nav-link {
  color: rgba(255,255,255,0.9) !important;
  font-weight: 500; font-size: 0.9rem;
  margin: 0 0.5rem; padding: 0.5rem 0 !important;
  position: relative; transition: color 0.2s;
}
.navbar-faaci .nav-link::after {
  content: ''; position: absolute; bottom: -2px; left: 0;
  width: 0; height: 2px; background: var(--faaci-steel);
  transition: width 0.25s;
}
.navbar-faaci .nav-link:hover::after { width: 100%; }
.navbar-faaci .nav-link:hover { color: #fff !important; }

/* Bouton Déconnexion navbar */
.btn-nav-logout {
  background: transparent;
  border: 1px solid rgba(255,255,255,0.5);
  color: rgba(255,255,255,0.9) !important;
  border-radius: 6px;
  padding: 5px 14px;
  font-size: 0.85rem;
  font-weight: 500;
  cursor: pointer;
  transition: background .2s, border-color .2s;
}
.btn-nav-logout:hover {
  background: rgba(255,255,255,0.12);
  border-color: rgba(255,255,255,0.8);
}
.navbar-faaci.scrolled .btn-nav-logout {
  border-color: var(--faaci-navy);
  color: var(--faaci-navy) !important;
}
.navbar-faaci.scrolled .btn-nav-logout:hover {
  background: rgba(13,31,60,0.07);
}

/* Lien actif — desktop */
.navbar-faaci .nav-link.active { color: #fff !important; }
.navbar-faaci .nav-link.active::after { width: 100%; background: #fff; }
.navbar-faaci.scrolled .nav-link.active { color: var(--faaci-navy) !important; }
.navbar-faaci.scrolled .nav-link.active::after { background: var(--faaci-navy); }

.navbar-faaci.scrolled .nav-link { color: #4A5568 !important; }
.navbar-faaci.scrolled .nav-link:hover { color: var(--faaci-navy) !important; }
.navbar-faaci.scrolled .logo-text { color: var(--faaci-navy); }
.navbar-faaci.scrolled .logo-sub { color: var(--faaci-silver); }
.navbar-faaci.scrolled .btn-login { color: var(--faaci-navy) !important; }
.navbar-faaci.scrolled .btn-cta-nav {
  background: var(--faaci-navy); color: #fff; border-color: var(--faaci-navy);
}
.navbar-faaci.scrolled .nav-toggle-icon { color: var(--faaci-navy) !important; }
.navbar-faaci .logo-text, .navbar-faaci .logo-sub { color: #fff; }
.navbar-faaci.scrolled .logo-text.text-white, .navbar-faaci.scrolled .logo-sub.text-white-50 { color: var(--faaci-navy) !important; }

/* Liens actifs — menu mobile */
.nav-mobile-link { color: var(--faaci-navy) !important; }
.nav-mobile-link.active {
  background: var(--faaci-light);
  color: var(--faaci-navy) !important;
  font-weight: 700 !important;
  border-left: 3px solid var(--faaci-steel);
  padding-left: calc(1rem - 3px) !important;
}

/* Dropdown À propos */
.navbar-faaci .dropdown-menu {
  border: 1px solid var(--faaci-border);
  border-radius: 0.75rem;
  box-shadow: 0 10px 40px rgba(13,31,60,0.15);
  padding: 0.5rem; margin-top: 0.5rem !important;
  min-width: 240px;
}
.navbar-faaci .dropdown-item {
  border-radius: 0.5rem; padding: 0.65rem 0.85rem;
  font-size: 0.9rem; color: #4A5568;
  font-weight: 500; display: flex; align-items: center;
  gap: 0.7rem; transition: all 0.15s;
}
.navbar-faaci .dropdown-item:hover { background: var(--faaci-light); color: var(--faaci-navy); }
.navbar-faaci .dropdown-item i { color: var(--faaci-steel); font-size: 1rem; }

/* HERO SLIDER */
.hero-carousel { height: 100vh; min-height: 560px; overflow: hidden; position: relative; }
@supports (height: 100dvh) {
  .hero-carousel { height: 100dvh; }
}
.hero-carousel .carousel-inner, .hero-carousel .carousel-item { height: 100%; }

.hero-slide {
  position: relative; height: 100%;
  background-size: cover; background-position: center;
  display: flex; align-items: center;
  padding: 5rem 0 4rem;
}
.hero-slide::before {
  content: ''; position: absolute; inset: 0;
  background: linear-gradient(135deg,
    rgba(13,31,60,0.93) 0%,
    rgba(13,31,60,0.78) 45%,
    rgba(13,31,60,0.55) 100%);
  z-index: 1;
}
.hero-slide-content { position: relative; z-index: 2; width: 100%; }

.hero-badge {
  display: inline-flex; align-items: center; gap: 0.5rem;
  background: rgba(255,255,255,0.1);
  border: 1px solid rgba(255,255,255,0.2);
  border-radius: 999px; padding: 0.45rem 1.05rem;
  font-size: 0.75rem; color: rgba(255,255,255,0.9);
  font-weight: 500; margin-bottom: 1.75rem;
}
.hero-badge .dot {
  width: 8px; height: 8px; background: var(--faaci-steel);
  border-radius: 50%; animation: pulse 2s infinite;
}
@keyframes pulse { 0%,100%{opacity:1} 50%{opacity:0.4} }

.hero-title {
  font-family: 'Playfair Display', serif !important;
  font-weight: 700; color: #fff;
  font-size: clamp(2.25rem, 6vw, 4.5rem);
  line-height: 1.05; margin-bottom: 1.75rem;
}
.hero-title .highlight { color: var(--faaci-silver); }

.hero-desc {
  color: rgba(255,255,255,0.75);
  font-size: 1.05rem; line-height: 1.65;
  max-width: 560px; margin-bottom: 2.5rem;
}

.hero-buttons {
  display: flex; flex-wrap: wrap; gap: 0.9rem;
  margin-bottom: 2rem;
}
.hero-buttons .btn {
  padding: 0.9rem 1.85rem;
  font-size: 0.92rem; border-radius: 0.6rem;
}

.carousel-item.active .hero-slide-content > * {
  animation: slideUp 0.9s cubic-bezier(0.22, 1, 0.36, 1) backwards;
}
.carousel-item.active .hero-slide-content > *:nth-child(1) { animation-delay: 0.15s; }
.carousel-item.active .hero-slide-content > *:nth-child(2) { animation-delay: 0.3s; }
.carousel-item.active .hero-slide-content > *:nth-child(3) { animation-delay: 0.45s; }
.carousel-item.active .hero-slide-content > *:nth-child(4) { animation-delay: 0.6s; }
@keyframes slideUp {
  from { opacity: 0; transform: translateY(30px); }
  to   { opacity: 1; transform: translateY(0); }
}

.hero-carousel .carousel-control-prev,
.hero-carousel .carousel-control-next {
  width: 48px; height: 48px; top: 50%;
  transform: translateY(-50%);
  background: rgba(255,255,255,0.1);
  backdrop-filter: blur(8px);
  border: 1px solid rgba(255,255,255,0.2);
  border-radius: 50%; opacity: 1; z-index: 10;
  transition: background 0.2s;
}
.hero-carousel .carousel-control-prev { left: 1.5rem; }
.hero-carousel .carousel-control-next { right: 1.5rem; }
.hero-carousel .carousel-control-prev:hover,
.hero-carousel .carousel-control-next:hover { background: rgba(255,255,255,0.25); }
@media (max-width: 768px) {
  .hero-carousel .carousel-control-prev,
  .hero-carousel .carousel-control-next { display: none; }
}

.hero-carousel .carousel-indicators { bottom: 2.5rem; margin-bottom: 0; gap: 0.6rem; }
.hero-carousel .carousel-indicators button {
  width: 8px !important; height: 8px !important;
  border-radius: 50%; background: rgba(255,255,255,0.35) !important;
  border: none !important; margin: 0 !important;
  opacity: 1 !important; transition: all 0.3s;
}
.hero-carousel .carousel-indicators button.active {
  background: #fff !important; width: 32px !important; border-radius: 4px;
}

.hero-progress {
  position: absolute; bottom: 0; left: 0;
  height: 3px; width: 100%;
  background: rgba(255,255,255,0.1); z-index: 10;
}
.hero-progress-bar {
  height: 100%;
  background: linear-gradient(90deg, var(--faaci-steel), var(--faaci-silver));
  width: 0%;
}

/* SECTIONS GENERIQUES */
.section { padding-top: 1.5rem; padding-bottom: 1.5rem; }
@media (min-width: 576px) { .section { padding-top: 3rem; padding-bottom: 3rem; } }
@media (min-width: 992px) { .section { padding-top: 6rem; padding-bottom: 6rem; } }
/* Première section juste après le hero : encore moins d'espace */
.hero-carousel + .section { padding-top: 1rem; }

.section-eyebrow {
  color: var(--faaci-steel); font-size: 0.78rem;
  font-weight: 600; letter-spacing: 0.2em;
  text-transform: uppercase; margin-bottom: 0.85rem;
  display: block;
}
.section-title {
  font-family: 'Playfair Display', serif !important;
  font-weight: 700; color: var(--faaci-navy);
  font-size: clamp(2rem, 4vw, 3rem);
  line-height: 1.15; margin-bottom: 1rem;
}
.section-lead { color: #6c757d; font-size: 1rem; line-height: 1.65; margin-bottom: 0; }

.fade-in { opacity: 0; transform: translateY(24px); transition: opacity 0.6s ease, transform 0.6s ease; }
.fade-in.visible { opacity: 1; transform: translateY(0); }

/* À PROPOS */
.about-image-wrap {
  position: relative; border-radius: 1rem;
  margin-right: 1.5rem; margin-bottom: 1.5rem;
}
.about-image {
  width: 100%; aspect-ratio: 4/3;
  background: linear-gradient(135deg, var(--faaci-light), var(--faaci-gray));
  display: flex; align-items: center; justify-content: center;
  color: var(--faaci-silver); border-radius: 1rem; overflow: hidden;
}
.about-image img { width: 100%; height: 100%; object-fit: cover; }
.about-floating-badge {
  position: absolute; bottom: -1.25rem; right: -1.25rem;
  background: var(--faaci-navy); color: #fff;
  border-radius: 0.85rem; padding: 1.25rem 1.5rem;
  box-shadow: 0 14px 32px rgba(13,31,60,0.22);
  min-width: 130px;
}
.about-floating-num {
  font-family: 'Playfair Display', serif !important;
  font-size: 2rem; font-weight: 700;
  line-height: 1; display: block; margin-bottom: 0.35rem;
}
.about-floating-label {
  font-size: 0.72rem; color: rgba(255,255,255,0.7);
  line-height: 1.3;
}
.about-text-block > * + * { margin-top: 1.25rem; }

.value-grid {
  display: grid; grid-template-columns: repeat(2, 1fr);
  gap: 1.15rem; margin: 1.75rem 0;
}
.value-item { display: flex; align-items: flex-start; gap: 0.85rem; }
.value-icon {
  width: 38px; height: 38px;
  border-radius: 0.5rem;
  background: rgba(74,127,165,0.12); color: var(--faaci-steel);
  display: flex; align-items: center; justify-content: center;
  flex-shrink: 0; font-size: 1rem;
}
.value-text-title { font-weight: 600; color: var(--faaci-navy); font-size: 0.9rem; margin: 0 0 0.2rem 0; }
.value-text-desc { color: #6c757d; font-size: 0.78rem; margin: 0; line-height: 1.4; }

/* STATS */
.stats-section { background: var(--faaci-navy); padding: 3.5rem 0; }
.stat-block { text-align: center; }
.stat-number {
  font-family: 'Playfair Display', serif !important;
  font-weight: 700; color: #fff; font-size: 2.5rem;
  line-height: 1; font-variant-numeric: tabular-nums;
}
@media (min-width: 992px) { .stat-number { font-size: 3rem; } }
.stat-label { color: rgba(255,255,255,0.55); font-size: 0.8rem; margin-top: 0.65rem; }

/* ACTIVITES */
.activity-card {
  background: #fff; border-radius: 1rem;
  padding: 2rem; height: 100%;
  transition: box-shadow 0.25s, transform 0.25s;
}
.activity-card:hover { box-shadow: 0 16px 40px rgba(13,31,60,0.08); transform: translateY(-4px); }
.activity-icon {
  width: 48px; height: 48px;
  border-radius: 0.75rem;
  background: rgba(74,127,165,0.12); color: var(--faaci-steel);
  display: flex; align-items: center; justify-content: center;
  font-size: 1.25rem; margin-bottom: 1.5rem;
  transition: all 0.25s;
}
.activity-card:hover .activity-icon { background: var(--faaci-navy); color: #fff; }
.activity-title {
  font-family: 'Playfair Display', serif !important;
  font-weight: 700; color: var(--faaci-navy); font-size: 1.2rem; margin-bottom: 0.85rem;
}
.activity-desc { color: #6c757d; font-size: 0.9rem; line-height: 1.6; margin: 0; }

.activity-card-cta {
  background: var(--faaci-navy); color: #fff;
  display: flex; flex-direction: column; justify-content: space-between;
}
.activity-card-cta .activity-icon { background: rgba(255,255,255,0.1); color: #fff; }
.activity-card-cta .activity-title { color: #fff; }
.activity-card-cta .activity-desc { color: rgba(255,255,255,0.65); margin-bottom: 1.75rem; }

/* COMMENT REJOINDRE */
.join-section { background: var(--faaci-navy); position: relative; overflow: hidden; }
.join-section::before, .join-section::after { content: ''; position: absolute; border-radius: 50%; filter: blur(80px); }
.join-section::before { top: 0; right: 0; width: 400px; height: 400px; background: var(--faaci-steel); opacity: 0.08; }
.join-section::after { bottom: 0; left: 0; width: 350px; height: 350px; background: var(--faaci-silver); opacity: 0.05; }
.join-section .container { position: relative; z-index: 2; }

.join-step { text-align: center; }
.join-step-num {
  width: 56px; height: 56px; border-radius: 50%;
  background: rgba(74,127,165,0.3);
  border: 1px solid rgba(74,127,165,0.4);
  color: #fff;
  font-family: 'Playfair Display', serif !important;
  font-weight: 700; font-size: 1.25rem;
  display: flex; align-items: center; justify-content: center;
  margin: 0 auto 1.25rem;
}
.join-step-title {
  color: #fff; font-weight: 600; font-size: 1rem;
  margin-bottom: 0.6rem;
  font-family: 'DM Sans', sans-serif !important;
}
.join-step-desc { color: rgba(255,255,255,0.55); font-size: 0.85rem; line-height: 1.55; margin: 0; }

/* EVENEMENTS */
.event-card { cursor: pointer; text-decoration: none; display: block; }
.event-image {
  aspect-ratio: 16/10; border-radius: 0.85rem;
  position: relative; margin-bottom: 1.25rem; overflow: hidden;
}
.event-image img { width: 100%; height: 100%; object-fit: cover; }
.event-date-badge {
  position: absolute; top: 1rem; left: 1rem;
  background: #fff; border-radius: 0.5rem;
  padding: 0.5rem 0.75rem; text-align: center;
  min-width: 56px; box-shadow: 0 4px 12px rgba(0,0,0,0.08);
  z-index: 2;
}
.event-date-day {
  font-family: 'Playfair Display', serif !important;
  font-weight: 700; color: var(--faaci-steel); font-size: 1.3rem; line-height: 1;
}
.event-date-month {
  font-size: 0.65rem; color: var(--faaci-navy);
  font-weight: 600; text-transform: uppercase;
  letter-spacing: 0.1em; margin-top: 0.25rem;
}
.event-type-badge {
  position: absolute; top: 1rem; right: 1rem;
  color: #fff; font-size: 0.7rem; font-weight: 500;
  padding: 0.3rem 0.75rem; border-radius: 999px;
  z-index: 2;
}
.event-title {
  font-family: 'Playfair Display', serif !important;
  font-weight: 700; color: var(--faaci-navy);
  font-size: 1.1rem; line-height: 1.35;
  margin-bottom: 0.5rem; transition: color 0.2s;
}
.event-card:hover .event-title { color: var(--faaci-steel); }
.event-desc { color: #6c757d; font-size: 0.85rem; line-height: 1.55; margin-bottom: 0.85rem; }
.event-meta { display: flex; gap: 1.15rem; font-size: 0.75rem; color: #adb5bd; flex-wrap: wrap; }
.event-meta span { display: inline-flex; align-items: center; gap: 0.35rem; }

/* ACTUALITES */
.news-card { background: #fff; border-radius: 0.85rem; overflow: hidden; height: 100%; transition: box-shadow 0.25s; }
.news-card:hover { box-shadow: 0 12px 30px rgba(13,31,60,0.08); }
.news-image {
  aspect-ratio: 16/10; background: var(--faaci-light);
  display: flex; align-items: center; justify-content: center;
  color: var(--faaci-silver); font-size: 2rem; overflow: hidden;
}
.news-image img { width: 100%; height: 100%; object-fit: cover; }
.news-body { padding: 1.5rem; }
.news-meta { display: flex; align-items: center; gap: 0.6rem; margin-bottom: 0.85rem; }
.news-category {
  background: rgba(13,31,60,0.08); color: var(--faaci-navy);
  font-size: 0.7rem; font-weight: 500;
  padding: 0.3rem 0.75rem; border-radius: 999px;
}
.news-date { font-size: 0.75rem; color: #adb5bd; }
.news-title {
  font-family: 'Playfair Display', serif !important;
  font-weight: 700; color: var(--faaci-navy); font-size: 1.05rem; line-height: 1.35; margin-bottom: 0.55rem;
}
.news-title a { color: inherit; text-decoration: none; transition: color 0.2s; }
.news-title a:hover { color: var(--faaci-steel); }
.news-excerpt { color: #6c757d; font-size: 0.85rem; line-height: 1.55; margin: 0; }

/* GALERIE */
.gallery-item {
  position: relative; border-radius: 0.85rem; overflow: hidden;
  aspect-ratio: 4/3; display: block; text-decoration: none;
}
.gallery-item img { width: 100%; height: 100%; object-fit: cover; transition: transform 0.3s; }
.gallery-item:hover img { transform: scale(1.05); }
.gallery-item-overlay {
  position: absolute; inset: 0;
  background: linear-gradient(180deg, transparent 50%, rgba(13,31,60,0.85) 100%);
  display: flex; align-items: flex-end; padding: 1rem;
}
.gallery-item-title { color: #fff; font-weight: 600; font-size: 0.95rem; margin: 0; }
.gallery-item-count { color: rgba(255,255,255,0.7); font-size: 0.75rem; }

/* EQUIPE */
.team-card {
  background: #fff; border-radius: 1rem; padding: 2rem;
  text-align: center; height: 100%;
  transition: box-shadow 0.25s, transform 0.25s;
}
.team-card:hover { box-shadow: 0 16px 40px rgba(13,31,60,0.08); transform: translateY(-4px); }
.team-photo {
  width: 110px; height: 110px; border-radius: 50%;
  margin: 0 auto 1.25rem; overflow: hidden;
  background: linear-gradient(135deg, var(--faaci-light), var(--faaci-gray));
  display: flex; align-items: center; justify-content: center;
  color: var(--faaci-silver); font-size: 2.5rem;
}
.team-photo img { width: 100%; height: 100%; object-fit: cover; }
.team-name {
  font-family: 'Playfair Display', serif !important;
  font-weight: 700; color: var(--faaci-navy); font-size: 1.1rem; margin-bottom: 0.25rem;
}
.team-role { color: var(--faaci-steel); font-size: 0.85rem; font-weight: 600; margin-bottom: 0.75rem; }
.team-bio { color: #6c757d; font-size: 0.85rem; line-height: 1.6; margin: 0; }

/* CONTACT */
.contact-info-item { display: flex; align-items: flex-start; gap: 1rem; margin-bottom: 1.5rem; }
.contact-info-icon {
  width: 42px; height: 42px; border-radius: 0.5rem;
  background: rgba(74,127,165,0.12); color: var(--faaci-steel);
  display: flex; align-items: center; justify-content: center;
  font-size: 1rem; flex-shrink: 0;
}
.contact-info-label { font-weight: 600; color: var(--faaci-navy); font-size: 0.9rem; margin: 0 0 0.2rem 0; }
.contact-info-value { color: #6c757d; font-size: 0.9rem; margin: 0; line-height: 1.4; }

.contact-form { background: #fff; padding: 2rem; border-radius: 1rem; }
.contact-form .form-label { font-size: 0.85rem; font-weight: 500; color: var(--faaci-navy); margin-bottom: 0.45rem; }
.contact-form .form-control, .contact-form .form-select {
  border: 1px solid var(--faaci-border);
  padding: 0.8rem 1rem; font-size: 0.9rem; border-radius: 0.55rem;
}
.contact-form .form-control:focus, .contact-form .form-select:focus {
  border-color: var(--faaci-steel);
  box-shadow: 0 0 0 3px rgba(74,127,165,0.1);
}

/* FOOTER */
.footer { background: var(--faaci-navy); color: #fff; padding: 4rem 0 2rem; }
.footer-title {
  font-family: 'DM Sans', sans-serif !important;
  font-size: 0.82rem; font-weight: 600;
  text-transform: uppercase; letter-spacing: 0.1em;
  color: rgba(255,255,255,0.85); margin-bottom: 1.35rem;
}
.footer-link {
  color: rgba(255,255,255,0.5); font-size: 0.9rem;
  text-decoration: none; display: block;
  padding: 0.35rem 0; transition: color 0.2s;
}
.footer-link:hover { color: #fff; }

.social-link {
  width: 36px; height: 36px; border-radius: 0.5rem;
  background: rgba(255,255,255,0.1); color: #fff;
  display: inline-flex; align-items: center; justify-content: center;
  margin-right: 0.5rem; transition: background 0.2s;
  text-decoration: none;
}
.social-link:hover { background: var(--faaci-steel); color: #fff; }
.social-link.whatsapp:hover { background: #25D366; }

.footer-bottom {
  border-top: 1px solid rgba(255,255,255,0.1);
  padding-top: 1.5rem; margin-top: 2.5rem;
  font-size: 0.8rem; color: rgba(255,255,255,0.4);
}

/* BACK TO TOP */
.back-to-top {
  position: fixed; bottom: 20px; right: 20px;
  z-index: 1020; width: 42px; height: 42px;
  background: var(--faaci-navy); color: #fff;
  border-radius: 50%;
  display: flex; align-items: center; justify-content: center;
  text-decoration: none;
  box-shadow: 0 6px 20px rgba(13,31,60,0.25);
  opacity: 0; transform: translateY(20px);
  transition: all 0.3s;
}
.back-to-top.visible { opacity: 1; transform: translateY(0); }
.back-to-top:hover { background: var(--faaci-steel); color: #fff; }

/* PAGE INTERIEURE (sous-pages) */
.page-header {
  background: var(--faaci-navy); color: #fff;
  padding: 9rem 0 3.5rem; position: relative; overflow: hidden;
}
.page-header::before, .page-header::after { content: ''; position: absolute; border-radius: 50%; filter: blur(80px); }
.page-header::before { top: -100px; right: -50px; width: 350px; height: 350px; background: var(--faaci-steel); opacity: 0.12; }
.page-header::after { bottom: -120px; left: -80px; width: 300px; height: 300px; background: var(--faaci-silver); opacity: 0.06; }
.page-header .container { position: relative; z-index: 2; }
.page-header-title {
  font-family: 'Playfair Display', serif !important;
  font-weight: 700; font-size: clamp(2rem, 4vw, 3rem); margin-bottom: 0.5rem;
}
.page-header-breadcrumb { font-size: 0.85rem; color: rgba(255,255,255,0.6); }
.page-header-breadcrumb a { color: rgba(255,255,255,0.8); text-decoration: none; }
.page-header-breadcrumb a:hover { color: #fff; }

.line-clamp-2 {
  display: -webkit-box;
  -webkit-line-clamp: 2;
  -webkit-box-orient: vertical;
  overflow: hidden;
}
.line-clamp-3 {
  display: -webkit-box;
  -webkit-line-clamp: 3;
  -webkit-box-orient: vertical;
  overflow: hidden;
}
</style>
