@extends('layouts.public')

@section('title', "Accueil — FAACI")
@section('description', "Le réseau des Alumni AIESEC en Côte d'Ivoire : annuaire, opportunités d'affaires, offres d'emploi, projets à financer et événements réservés aux membres.")

@section('content')

{{-- HERO --}}
<section class="hero-carousel">
    @if ($slides->isNotEmpty())
        <div id="heroCarousel" class="carousel slide carousel-fade" data-bs-ride="carousel" data-bs-interval="6000">
            @if ($slides->count() > 1)
                <div class="carousel-indicators">
                    @foreach ($slides as $i => $slide)
                        <button type="button" data-bs-target="#heroCarousel" data-bs-slide-to="{{ $i }}" class="{{ $i === 0 ? 'active' : '' }}" @if ($i === 0) aria-current="true" @endif></button>
                    @endforeach
                </div>
            @endif
            <div class="carousel-inner">
                @foreach ($slides as $i => $slide)
                    @php
                        $fond = $slide->image_url
                            ? "url('{$slide->image_url}')"
                            : 'linear-gradient(135deg, var(--faaci-navy), var(--faaci-steel))';
                    @endphp
                    <div class="carousel-item {{ $i === 0 ? 'active' : '' }}">
                        <div class="hero-slide" style="background-image: {{ $fond }};">
                            <div class="container hero-slide-content">
                                <div class="row">
                                    <div class="col-lg-8 col-xl-7">
                                        <div class="hero-badge">
                                            <span class="dot"></span>
                                            <span>Fondation AIESEC Alumni Côte d'Ivoire</span>
                                        </div>
                                        <h1 class="hero-title">{{ $slide->titre }}</h1>
                                        <p class="hero-desc">
                                            @if ($slide->sous_titre)
                                                <strong>{{ $slide->sous_titre }}</strong><br>
                                            @endif
                                            {{ $slide->description }}
                                        </p>
                                        <div class="hero-buttons">
                                            @if ($slide->libelle_bouton_1)
                                                <a href="{{ $slide->lien_bouton_1 ?: '#' }}" class="btn btn-faaci-white">{{ $slide->libelle_bouton_1 }}</a>
                                            @endif
                                            @if ($slide->libelle_bouton_2)
                                                <a href="{{ $slide->lien_bouton_2 ?: '#' }}" class="btn btn-faaci-outline-white">{{ $slide->libelle_bouton_2 }} <i class="bi bi-arrow-right ms-1"></i></a>
                                            @endif
                                        </div>
                                    </div>
                                </div>
                            </div>
                        </div>
                    </div>
                @endforeach
            </div>

            @if ($slides->count() > 1)
                <button class="carousel-control-prev" type="button" data-bs-target="#heroCarousel" data-bs-slide="prev">
                    <i class="bi bi-chevron-left text-white fs-5"></i>
                </button>
                <button class="carousel-control-next" type="button" data-bs-target="#heroCarousel" data-bs-slide="next">
                    <i class="bi bi-chevron-right text-white fs-5"></i>
                </button>
            @endif

            <div class="hero-progress">
                <div class="hero-progress-bar" id="heroProgressBar"></div>
            </div>
        </div>
    @else
        <div class="hero-slide" style="background: linear-gradient(135deg, var(--faaci-navy), var(--faaci-steel)); background-size: cover; background-position: center;">
            <div class="container hero-slide-content">
                <div class="row">
                    <div class="col-lg-8 col-xl-7">
                        <div class="hero-badge">
                            <span class="dot"></span>
                            <span>Fondation AIESEC Alumni Côte d'Ivoire</span>
                        </div>
                        <h1 class="hero-title">Le réseau des<br><span class="highlight">Alumni AIESEC</span><br>en Côte d'Ivoire.</h1>
                        <p class="hero-desc">Un hub qui réunit les anciens d'AIESEC CI autour d'une ambition commune : connecter, collaborer, faire grandir le réseau.</p>
                        <div class="hero-buttons">
                            <a href="{{ route('register') }}" class="btn btn-faaci-white">Rejoindre le réseau</a>
                            <a href="#about" class="btn btn-faaci-outline-white">Découvrir la FAACI <i class="bi bi-arrow-right ms-1"></i></a>
                        </div>
                    </div>
                </div>
            </div>
        </div>
    @endif
</section>


{{-- À PROPOS --}}
<section id="about" class="section bg-white">
    <div class="container">
        <div class="row g-3 g-lg-5 align-items-center">

            <div class="col-lg-6 fade-in order-2 order-lg-1">
                <div class="about-image-wrap">
                    <div class="about-image" style="@if(!empty($about['about_image'])) background-image:url('{{ $about['about_image'] }}'); background-size:cover; background-position:center; @endif">
                        @if (empty($about['about_image']))
                            <div class="text-center text-white opacity-50">
                                <i class="bi bi-image fs-1 d-block mb-2"></i>
                                <small>Ajoutez une image depuis le backoffice</small>
                            </div>
                        @endif
                    </div>
                    @if (!empty($about['about_badge_nombre']))
                        <div class="about-floating-badge">
                            <span class="about-floating-num">{{ $about['about_badge_nombre'] }}</span>
                            <span class="about-floating-label">{!! nl2br(e($about['about_badge_label'] ?? '')) !!}</span>
                        </div>
                    @endif
                </div>
            </div>

            <div class="col-lg-6 fade-in order-1 order-lg-2">
                <div class="about-text-block">
                    @if (!empty($about['about_eyebrow']))
                        <span class="section-eyebrow">{{ $about['about_eyebrow'] }}</span>
                    @endif
                    @if (!empty($about['about_titre']))
                        <h2 class="section-title">{{ $about['about_titre'] }}</h2>
                    @endif
                    @if (!empty($about['about_texte_1']))
                        <p class="section-lead">{!! nl2br(e($about['about_texte_1'])) !!}</p>
                    @endif
                    @if (!empty($about['about_texte_2']))
                        <p class="section-lead">{!! nl2br(e($about['about_texte_2'])) !!}</p>
                    @endif
                </div>

                <div class="value-grid">
                    <div class="value-item">
                        <div class="value-icon"><i class="bi bi-people"></i></div>
                        <div>
                            <p class="value-text-title">Communauté</p>
                            <p class="value-text-desc">Un réseau soudé et bienveillant</p>
                        </div>
                    </div>
                    <div class="value-item">
                        <div class="value-icon"><i class="bi bi-lightning-charge"></i></div>
                        <div>
                            <p class="value-text-title">Excellence</p>
                            <p class="value-text-desc">Un engagement de haut niveau</p>
                        </div>
                    </div>
                    <div class="value-item">
                        <div class="value-icon"><i class="bi bi-shield-check"></i></div>
                        <div>
                            <p class="value-text-title">Confiance</p>
                            <p class="value-text-desc">Transparence et intégrité</p>
                        </div>
                    </div>
                    <div class="value-item">
                        <div class="value-icon"><i class="bi bi-award"></i></div>
                        <div>
                            <p class="value-text-title">Impact</p>
                            <p class="value-text-desc">Des actions concrètes et durables</p>
                        </div>
                    </div>
                </div>

                <a href="{{ route('apropos.mission') }}" class="text-decoration-none fw-semibold text-faaci-navy">
                    En savoir plus sur notre mission <i class="bi bi-arrow-right ms-1"></i>
                </a>
            </div>

        </div>
    </div>
</section>


{{-- VALEURS --}}
@if ($valeurs->isNotEmpty())
    <section class="section bg-faaci-gray">
        <div class="container">
            <div class="text-center mb-5 fade-in">
                <span class="section-eyebrow">Nos principes</span>
                <h2 class="section-title">Nos valeurs</h2>
            </div>
            <div class="row g-4">
                @foreach ($valeurs as $valeur)
                    <div class="col-md-6 col-lg-3 fade-in">
                        <div class="activity-card text-center">
                            <div class="activity-icon mx-auto"><i class="bi {{ $valeur->icone }}"></i></div>
                            <h3 class="activity-title">{{ $valeur->titre }}</h3>
                            <div class="activity-desc rich-content">{!! $valeur->description !!}</div>
                        </div>
                    </div>
                @endforeach
            </div>
        </div>
    </section>
@endif


{{-- STATS --}}
<section class="stats-section">
    <div class="container">
        <div class="row g-4 justify-content-center">
            @for ($i = 1; $i <= 5; $i++)
                @if (!empty($stats["stat_{$i}_nombre"]))
                    <div class="col-6 col-md-4 col-lg fade-in">
                        <div class="stat-block">
                            <div class="stat-number counter" data-target="{{ (int) $stats["stat_{$i}_nombre"] }}">0</div>
                            <div class="stat-label">{{ $stats["stat_{$i}_label"] ?? '' }}</div>
                        </div>
                    </div>
                @endif
            @endfor
        </div>
    </div>
</section>


{{-- ACTIVITES --}}
<section id="activites" class="section bg-faaci-gray">
    <div class="container">
        <div class="text-center mb-5 fade-in">
            @if (!empty($activites_section['activites_eyebrow']))
                <span class="section-eyebrow">{{ $activites_section['activites_eyebrow'] }}</span>
            @endif
            @if (!empty($activites_section['activites_titre']))
                <h2 class="section-title">{{ $activites_section['activites_titre'] }}</h2>
            @else
                <h2 class="section-title">Nos domaines d'activité</h2>
            @endif
            @if (!empty($activites_section['activites_texte']))
                <p class="section-lead mx-auto" style="max-width: 580px;">{{ $activites_section['activites_texte'] }}</p>
            @endif
        </div>

        <div class="row g-4">
            @forelse ($activites as $activite)
                <div class="col-md-6 col-lg-4 fade-in">
                    <div class="activity-card">
                        <div class="activity-icon"><i class="bi {{ $activite->icone }}"></i></div>
                        <h3 class="activity-title">{{ $activite->titre }}</h3>
                        @if ($activite->description)
                            <div class="activity-desc rich-content">{!! $activite->description !!}</div>
                        @endif
                    </div>
                </div>
            @empty
                {{-- Fallback si aucune activité en base --}}
                <div class="col-12 text-center text-muted py-4">
                    <i class="bi bi-layers fs-1 d-block mb-2 opacity-50"></i>
                    <p>Les activités seront bientôt disponibles.</p>
                </div>
            @endforelse
        </div>

        @if ($activites->isNotEmpty())
            <div class="text-center mt-5 fade-in">
                <a href="{{ route('activites') }}" class="btn btn-faaci-navy">
                    Voir toutes nos activités <i class="bi bi-arrow-right ms-1"></i>
                </a>
            </div>
        @endif
    </div>
</section>


{{-- COMMENT REJOINDRE --}}
<section class="section join-section">
    <div class="container">
        <div class="text-center mb-5 fade-in">
            @if (!empty($rejoindre['rejoindre_eyebrow']))
                <span class="section-eyebrow">{{ $rejoindre['rejoindre_eyebrow'] }}</span>
            @endif
            @if (!empty($rejoindre['rejoindre_titre']))
                <h2 class="section-title text-white">{{ $rejoindre['rejoindre_titre'] }}</h2>
            @endif
        </div>

        <div class="row g-4">
            <div class="col-md-6 col-lg-3 fade-in">
                <div class="join-step">
                    <div class="join-step-num">1</div>
                    <h3 class="join-step-title">Demande d'adhésion</h3>
                    <p class="join-step-desc">Remplissez le formulaire et indiquez votre parcours AIESEC.</p>
                </div>
            </div>
            <div class="col-md-6 col-lg-3 fade-in">
                <div class="join-step">
                    <div class="join-step-num">2</div>
                    <h3 class="join-step-title">Validation</h3>
                    <p class="join-step-desc">Le bureau de la FAACI examine et valide votre demande.</p>
                </div>
            </div>
            <div class="col-md-6 col-lg-3 fade-in">
                <div class="join-step">
                    <div class="join-step-num">3</div>
                    <h3 class="join-step-title">Activation</h3>
                    <p class="join-step-desc">Vous recevez vos accès et complétez votre profil membre.</p>
                </div>
            </div>
            <div class="col-md-6 col-lg-3 fade-in">
                <div class="join-step">
                    <div class="join-step-num">4</div>
                    <h3 class="join-step-title">Bienvenue !</h3>
                    <p class="join-step-desc">Annuaire, opportunités, événements : tout vous est ouvert.</p>
                </div>
            </div>
        </div>

        <div class="text-center mt-5 fade-in">
            <a href="{{ route('register') }}" class="btn btn-faaci-white px-5 py-3 rounded-3">Faire une demande maintenant</a>
        </div>
    </div>
</section>


{{-- EVENEMENTS --}}
<section id="evenements" class="section bg-white">
    <div class="container">
        <div class="d-flex flex-column flex-sm-row justify-content-between align-items-sm-end gap-3 mb-5 fade-in">
            <div>
                <span class="section-eyebrow">Agenda</span>
                <h2 class="section-title mb-0">Événements à venir</h2>
            </div>
            <a href="{{ route('evenements.index') }}" class="text-decoration-none text-faaci-navy fw-semibold">Voir tout le calendrier <i class="bi bi-arrow-right ms-1"></i></a>
        </div>

        @if ($evenements->isNotEmpty())
            <div class="row g-4">
                @foreach ($evenements as $evenement)
                    <div class="col-md-6 col-lg-4 fade-in">
                        @include('public.evenements._card', ['evenement' => $evenement])
                    </div>
                @endforeach
            </div>
        @else
            <p class="text-muted fade-in mb-0">Aucun événement à venir pour le moment. Revenez bientôt !</p>
        @endif
    </div>
</section>


{{-- ACTUALITES --}}
<section id="actualites" class="section bg-faaci-gray">
    <div class="container">
        <div class="d-flex flex-column flex-sm-row justify-content-between align-items-sm-end gap-3 mb-5 fade-in">
            <div>
                <span class="section-eyebrow">Vie du réseau</span>
                <h2 class="section-title mb-0">Actualités</h2>
            </div>
            <a href="{{ route('actualites.index') }}" class="text-decoration-none text-faaci-navy fw-semibold">Toutes les actualités <i class="bi bi-arrow-right ms-1"></i></a>
        </div>

        @if ($articles->isNotEmpty())
            <div class="row g-4">
                @foreach ($articles as $article)
                    <div class="col-md-6 col-lg-4 fade-in">
                        @include('public.actualites._card', ['article' => $article])
                    </div>
                @endforeach
            </div>
        @else
            <p class="text-muted fade-in mb-0">Aucune actualité publiée pour le moment.</p>
        @endif
    </div>
</section>


{{-- CTA ADHESION --}}
<section class="section bg-white">
    <div class="container">
        <div class="row justify-content-center fade-in">
            <div class="col-lg-7 text-center">
                @if (!empty($cta['cta_eyebrow']))
                    <span class="section-eyebrow">{{ $cta['cta_eyebrow'] }}</span>
                @endif
                @if (!empty($cta['cta_titre']))
                    <h2 class="section-title">{{ $cta['cta_titre'] }}</h2>
                @endif
                @if (!empty($cta['cta_texte']))
                    <p class="section-lead mb-4 mx-auto" style="max-width: 540px;">{{ $cta['cta_texte'] }}</p>
                @endif
                <div class="d-flex flex-column flex-sm-row gap-3 justify-content-center">
                    <a href="{{ route('register') }}" class="btn btn-faaci-navy px-4 py-3 rounded-3">Faire une demande d'adhésion</a>
                    <a href="#contact" class="btn btn-outline-dark px-4 py-3 rounded-3">Nous contacter d'abord</a>
                </div>
            </div>
        </div>
    </div>
</section>


{{-- CONTACT --}}
@php
    $parametres = \App\Models\ParametreSite::tous();
@endphp
<section id="contact" class="section bg-faaci-gray">
    <div class="container">
        <div class="row g-5">

            <div class="col-lg-5 fade-in">
                <span class="section-eyebrow">Nous joindre</span>
                <h2 class="section-title">Contactez la FAACI</h2>
                <p class="section-lead mb-4">Une question, une demande d'adhésion ou une proposition de partenariat ? Notre équipe vous répond dans les meilleurs délais.</p>

                @if (!empty($parametres['email_contact']))
                    <div class="contact-info-item">
                        <div class="contact-info-icon"><i class="bi bi-envelope"></i></div>
                        <div><p class="contact-info-label">Email</p><p class="contact-info-value">{{ $parametres['email_contact'] }}</p></div>
                    </div>
                @endif
                @if (!empty($parametres['telephone']))
                    <div class="contact-info-item">
                        <div class="contact-info-icon"><i class="bi bi-telephone"></i></div>
                        <div><p class="contact-info-label">Téléphone</p><p class="contact-info-value">{{ $parametres['telephone'] }}</p></div>
                    </div>
                @endif
                @if (!empty($parametres['adresse']))
                    <div class="contact-info-item">
                        <div class="contact-info-icon"><i class="bi bi-geo-alt"></i></div>
                        <div><p class="contact-info-label">Adresse</p><p class="contact-info-value">{{ $parametres['adresse'] }}</p></div>
                    </div>
                @endif
            </div>

            <div class="col-lg-7 fade-in">
                @if (session('status'))
                    <div class="alert alert-success">{{ session('status') }}</div>
                @endif
                <form action="{{ route('contact.send') }}" method="POST" class="contact-form">
                    @csrf
                    <div class="row g-3">
                        <div class="col-sm-6">
                            <label class="form-label">Prénom & Nom</label>
                            <input type="text" name="nom" value="{{ old('nom') }}" class="form-control @error('nom') is-invalid @enderror" placeholder="Jean Kouassi" required>
                            @error('nom') <div class="invalid-feedback">{{ $message }}</div> @enderror
                        </div>
                        <div class="col-sm-6">
                            <label class="form-label">Email</label>
                            <input type="email" name="email" value="{{ old('email') }}" class="form-control @error('email') is-invalid @enderror" placeholder="jean@exemple.com" required>
                            @error('email') <div class="invalid-feedback">{{ $message }}</div> @enderror
                        </div>
                        <div class="col-12">
                            <label class="form-label">Sujet</label>
                            <select name="sujet" class="form-select @error('sujet') is-invalid @enderror" required>
                                <option value="">Choisissez un sujet</option>
                                <option @selected(old('sujet') === "Demande d'adhésion")>Demande d'adhésion</option>
                                <option @selected(old('sujet') === 'Question générale')>Question générale</option>
                                <option @selected(old('sujet') === 'Partenariat')>Partenariat</option>
                                <option @selected(old('sujet') === 'Autre')>Autre</option>
                            </select>
                            @error('sujet') <div class="invalid-feedback">{{ $message }}</div> @enderror
                        </div>
                        <div class="col-12">
                            <label class="form-label">Message</label>
                            <textarea name="message" rows="5" class="form-control @error('message') is-invalid @enderror" placeholder="Votre message..." required>{{ old('message') }}</textarea>
                            @error('message') <div class="invalid-feedback">{{ $message }}</div> @enderror
                        </div>
                        <div class="col-12 mt-2">
                            <button type="submit" class="btn btn-faaci-navy w-100 py-3 rounded-3">Envoyer le message</button>
                        </div>
                    </div>
                </form>
            </div>

        </div>
    </div>
</section>

@endsection
