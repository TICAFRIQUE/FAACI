<x-mail::message>
# Bonjour {{ $user->prenom }},

Votre mot de passe sur la plateforme **FAACI** a été réinitialisé par un administrateur.

Cliquez sur le bouton ci-dessous pour définir votre nouveau mot de passe :

<x-mail::button :url="$lienMotDePasse">
Définir mon mot de passe
</x-mail::button>

Ce lien est valable **{{ config('auth.passwords.users.expire', 60) }} minutes**.

Si vous n'êtes pas à l'origine de cette demande, contactez l'administrateur de la plateforme.

Cordialement,<br>
{{ config('app.name') }}
</x-mail::message>
