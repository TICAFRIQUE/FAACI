<x-mail::message>
# Bonjour {{ $user->prenom }},

Après examen, nous sommes au regret de vous informer que votre demande d'adhésion à la **Fondation AIESEC Alumni Côte d'Ivoire (FAACI)** n'a pas été retenue.

@if ($user->motif_rejet)
**Motif :** {{ $user->motif_rejet }}
@endif

Vous pouvez soumettre une nouvelle demande d'adhésion à tout moment.

Cordialement,<br>
{{ config('app.name') }}
</x-mail::message>
