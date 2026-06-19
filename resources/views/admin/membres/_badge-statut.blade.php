@php
    $badges = [
        'en_attente' => ['label' => 'En attente', 'class' => 'bg-warning text-dark'],
        'actif' => ['label' => 'Actif', 'class' => 'bg-success'],
        'suspendu' => ['label' => 'Suspendu', 'class' => 'bg-secondary'],
        'inactif' => ['label' => 'Inactif', 'class' => 'bg-dark'],
        'rejete' => ['label' => 'Rejeté', 'class' => 'bg-danger'],
    ];
    $badge = $badges[$statut] ?? ['label' => $statut, 'class' => 'bg-light text-dark'];
@endphp
<span class="badge {{ $badge['class'] }}">{{ $badge['label'] }}</span>
