<link href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.3/dist/css/bootstrap.min.css" rel="stylesheet">
<link href="https://cdn.jsdelivr.net/npm/bootstrap-icons@1.11.3/font/bootstrap-icons.min.css" rel="stylesheet">
<link rel="preconnect" href="https://fonts.googleapis.com">
<link rel="preconnect" href="https://fonts.gstatic.com" crossorigin>
<link href="https://fonts.googleapis.com/css2?family=Playfair+Display:wght@400;600;700;800&family=DM+Sans:wght@300;400;500;600;700&display=swap" rel="stylesheet">

<style>
:root {
  --faaci-navy:   #0D1F3C;
  --faaci-steel:  #4A7FA5;
  --faaci-silver: #9BAAB8;
  --faaci-light:  #E8EEF4;
  --faaci-gray:   #F5F6F8;
  --faaci-border: #D8DDE6;
}

*, body, .btn, .form-control, .form-select, .nav-link, .dropdown-item {
  font-family: 'DM Sans', sans-serif !important;
}
h1, h2, h3, h4, h5, h6, .logo-text {
  font-family: 'Playfair Display', serif !important;
}

body { color: #0A0A0A; background: var(--faaci-gray); }

.text-faaci-navy  { color: var(--faaci-navy) !important; }
.text-faaci-steel { color: var(--faaci-steel) !important; }
.bg-faaci-navy    { background-color: var(--faaci-navy) !important; }
.bg-faaci-gray    { background-color: var(--faaci-gray) !important; }

.btn-faaci-navy {
  background: var(--faaci-navy); color: #fff;
  border: 1px solid var(--faaci-navy); font-weight: 600;
  transition: all 0.2s;
}
.btn-faaci-navy:hover { background: var(--faaci-steel); border-color: var(--faaci-steel); color: #fff; }

.btn-faaci-outline-white {
  background: transparent; color: #fff;
  border: 1px solid rgba(255,255,255,0.3); font-weight: 500;
  transition: all 0.2s;
}
.btn-faaci-outline-white:hover { background: rgba(255,255,255,0.1); color: #fff; border-color: rgba(255,255,255,0.5); }

a { color: var(--faaci-steel); }
.form-control:focus, .form-select:focus {
  border-color: var(--faaci-steel);
  box-shadow: 0 0 0 0.2rem rgba(74,127,165,0.15);
}

/* ── PAGINATION FAACI ─────────────────────────────────── */
.faaci-pagination-wrap {
    margin-top: 1.5rem;
}
.faaci-page-count {
    font-size: 0.85rem;
    color: var(--faaci-silver);
}
.faaci-page-count span {
    color: #444;
}
.faaci-pagination {
    display: flex;
    align-items: center;
    gap: 4px;
}
.faaci-page-btn {
    display: inline-flex;
    align-items: center;
    justify-content: center;
    gap: 5px;
    min-width: 36px;
    height: 36px;
    padding: 0 10px;
    border-radius: 8px;
    border: 1.5px solid var(--faaci-border);
    background: #fff;
    color: #444;
    font-size: 0.875rem;
    font-weight: 500;
    text-decoration: none;
    cursor: pointer;
    transition: all 0.15s ease;
    font-family: 'DM Sans', sans-serif !important;
}
.faaci-page-btn:hover:not(:disabled):not(.faaci-page-btn--active) {
    border-color: var(--faaci-steel);
    color: var(--faaci-steel);
    background: rgba(74,127,165,0.06);
}
.faaci-page-btn--active {
    background: var(--faaci-navy);
    border-color: var(--faaci-navy);
    color: #fff !important;
    font-weight: 600;
    cursor: default;
    box-shadow: 0 2px 8px rgba(13,31,60,0.18);
}
.faaci-page-btn--nav {
    color: var(--faaci-navy);
    font-weight: 500;
}
.faaci-page-btn--nav:hover:not(:disabled) {
    background: var(--faaci-navy);
    border-color: var(--faaci-navy);
    color: #fff;
}
.faaci-page-btn:disabled,
.faaci-page-btn[disabled] {
    opacity: 0.38;
    cursor: not-allowed;
    pointer-events: none;
}
.faaci-page-dots {
    display: inline-flex;
    align-items: center;
    justify-content: center;
    min-width: 36px;
    height: 36px;
    color: var(--faaci-silver);
    font-size: 0.9rem;
    letter-spacing: 0.05em;
}
@media (max-width: 575.98px) {
    .faaci-page-btn--nav {
        font-size: 0.82rem;
        padding: 0 12px;
    }
    .faaci-page-info {
        font-size: 0.82rem;
        color: var(--faaci-silver);
    }
}
/* ───────────────────────────────────────────────────────── */

/* LOGO */
.logo-svg { width: 42px; height: 42px; flex-shrink: 0; }
.logo-text { font-weight: 700; font-size: 1.15rem; line-height: 1; letter-spacing: 0.02em; }
.logo-sub {
  font-family: 'DM Sans', sans-serif !important;
  font-size: 0.62rem; letter-spacing: 0.15em; text-transform: uppercase;
  line-height: 1; margin-top: 3px;
}
</style>
