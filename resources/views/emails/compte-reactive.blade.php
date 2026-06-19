<x-mail::message>
# Bonjour {{ $user->prenom }},

Bonne nouvelle : votre compte sur la plateforme **FAACI** a été réactivé par un administrateur.

Vous pouvez de nouveau vous connecter et accéder à votre espace membre.

<x-mail::button :url="route('login')">
Accéder à mon espace
</x-mail::button>

Cordialement,<br>
{{ config('app.name') }}
</x-mail::message>
