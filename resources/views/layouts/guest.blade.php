<!DOCTYPE html>
<html lang="fr">
<head>
<meta charset="UTF-8">
<meta name="viewport" content="width=device-width, initial-scale=1.0">
<title>@yield('title', 'Connexion') — FAACI</title>
@include('partials.faaci-styles')
</head>
<body>
<div class="container-fluid p-0">
    <div class="row g-0 min-vh-100">

        <div class="col-lg-5 d-none d-lg-flex flex-column justify-content-between p-5 bg-faaci-navy text-white">
            <x-faaci-logo/>

            <div>
                <h1 class="h2 fw-bold mb-3">Le réseau des Alumni AIESEC Côte d'Ivoire</h1>
                <p class="text-white-50">
                    Espace réservé aux membres : annuaire, projets, opportunités d'affaires,
                    offres d'emploi et événements de la communauté.
                </p>
            </div>

            <p class="text-white-50 small mb-0">&copy; {{ date('Y') }} Fondation AIESEC Alumni CI</p>
        </div>

        <div class="col-lg-7 d-flex align-items-center justify-content-center p-4 p-md-5">
            <div class="w-100" style="max-width: 440px;">

                <div class="d-lg-none mb-4">
                    <x-faaci-logo :dark="true"/>
                </div>

                @yield('content')

            </div>
        </div>

    </div>
</div>

<script src="https://cdn.jsdelivr.net/npm/bootstrap@5.3.3/dist/js/bootstrap.bundle.min.js"></script>
</body>
</html>
