<x-mail::message>
# Bienvenue {{ $user->prenom }} !

Bonne nouvelle : votre adhésion à la **Fondation AIESEC Alumni Côte d'Ivoire (FAACI)** a été validée par un administrateur.

Vous pouvez désormais vous connecter avec votre adresse e-mail et votre mot de passe pour accéder à votre espace membre : annuaire, projets, opportunités, événements et plus encore.

<x-mail::button :url="route('login')">
Accéder à mon espace
</x-mail::button>

Bienvenue parmi nous,<br>
{{ config('app.name') }}
</x-mail::message>
