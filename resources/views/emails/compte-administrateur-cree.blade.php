<x-mail::message>
# Bonjour {{ $user->prenom }},

Un compte **{{ ucfirst(str_replace('_', ' ', $user->getRoleNames()->first() ?? 'administrateur')) }}** vient d'être créé pour vous sur la plateforme **FAACI**.

Pour activer votre compte, cliquez sur le bouton ci-dessous pour définir votre mot de passe :

<x-mail::button :url="$lienMotDePasse">
Définir mon mot de passe
</x-mail::button>

Ce lien est valable **{{ config('auth.passwords.users.expire', 60) }} minutes**.

Si vous n'êtes pas à l'origine de cette demande, vous pouvez ignorer cet e-mail.

Cordialement,<br>
{{ config('app.name') }}
</x-mail::message>
