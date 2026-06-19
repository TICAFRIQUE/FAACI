<x-mail::message>
# Bonjour {{ $user->prenom }},

Votre compte sur la plateforme **FAACI** a été temporairement suspendu par un administrateur.

@if ($user->motif_suspension)
**Motif :** {{ $user->motif_suspension }}
@endif

Pendant cette période, l'accès à votre espace membre n'est plus disponible. Si vous pensez qu'il s'agit d'une erreur, vous pouvez contacter l'administration de la fondation.

Cordialement,<br>
{{ config('app.name') }}
</x-mail::message>
