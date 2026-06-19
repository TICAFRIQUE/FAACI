<x-mail::message>
# Bonjour {{ $user->prenom }},

Nous avons bien reçu votre demande d'adhésion à la **Fondation AIESEC Alumni Côte d'Ivoire (FAACI)**.

Un administrateur va examiner votre demande dans les meilleurs délais. Vous recevrez un e-mail dès que votre compte sera activé.

<x-mail::button :url="route('login')">
Suivre ma demande
</x-mail::button>

Merci de votre confiance,<br>
{{ config('app.name') }}
</x-mail::message>
