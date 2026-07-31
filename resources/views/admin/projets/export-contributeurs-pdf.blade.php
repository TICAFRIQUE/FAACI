<!doctype html>
<html lang="fr">
<head>
    <meta charset="UTF-8">
    <title>Contributeurs — {{ $projet->titre }}</title>
    <style>
        * { box-sizing: border-box; margin: 0; padding: 0; }
        body { font-family: Arial, sans-serif; font-size: 12px; color: #1a1a2e; padding: 24px; }
        h1 { font-size: 18px; color: #0D1F3C; margin-bottom: 4px; }
        .meta { font-size: 11px; color: #666; margin-bottom: 20px; }
        table { width: 100%; border-collapse: collapse; margin-top: 12px; }
        thead th { background: #0D1F3C; color: #fff; padding: 8px 10px; text-align: left; font-size: 11px; }
        tbody tr:nth-child(even) { background: #f5f7fb; }
        tbody td { padding: 7px 10px; border-bottom: 1px solid #e0e4ed; font-size: 11px; }
        .badge { display: inline-block; padding: 2px 8px; border-radius: 999px; font-size: 10px; font-weight: 600; }
        .badge-warning  { background: #fef3c7; color: #92400e; }
        .badge-info     { background: #dbeafe; color: #1e40af; }
        .badge-success  { background: #dcfce7; color: #166534; }
        .badge-primary  { background: #ede9fe; color: #3730a3; }
        .badge-secondary{ background: #f1f5f9; color: #475569; }
        .totals { margin-top: 16px; font-size: 12px; color: #444; }
        .totals span { font-weight: 700; color: #0D1F3C; }
        footer { margin-top: 32px; font-size: 10px; color: #aaa; text-align: center; border-top: 1px solid #e0e4ed; padding-top: 10px; }
        @media print {
            body { padding: 10px; }
            .no-print { display: none !important; }
        }
    </style>
</head>
<body>

<div class="no-print" style="margin-bottom:20px;">
    <button onclick="window.print()" style="background:#0D1F3C;color:#fff;border:none;padding:8px 20px;border-radius:6px;cursor:pointer;font-size:13px;">
        🖨 Imprimer / Enregistrer en PDF
    </button>
    <a href="{{ route('admin.projets.show', $projet) }}" style="margin-left:12px;font-size:12px;color:#4A7FA5;">← Retour au projet</a>
</div>

<h1>Contributeurs — {{ $projet->titre }}</h1>
<div class="meta">
    Exporté le {{ now()->translatedFormat('d F Y à H:i') }}
    @if ($statut !== 'tous')
        &nbsp;·&nbsp; Filtre : <strong>{{ \App\Models\Contribution::statutsLibelles()[$statut] ?? $statut }}</strong>
    @endif
    &nbsp;·&nbsp; {{ $contributions->count() }} contributeur(s)
</div>

<table>
    <thead>
        <tr>
            <th>#</th>
            <th>Nom complet</th>
            <th>Email</th>
            <th>Montant promis</th>
            <th>Montant payé</th>
            <th>Moyen</th>
            <th>Statut</th>
            <th>Date promesse</th>
        </tr>
    </thead>
    <tbody>
        @forelse ($contributions as $i => $c)
            @php
                $badgeMap = [
                    'pending'   => 'warning',
                    'confirmed' => 'info',
                    'paid'      => 'success',
                    'partial'   => 'primary',
                    'cancelled' => 'secondary',
                ];
                $libelles = \App\Models\Contribution::statutsLibelles();
                $moyens   = \App\Models\Contribution::moyensPaiement();
            @endphp
            <tr>
                <td>{{ $i + 1 }}</td>
                <td>{{ $c->contributeur?->nom_complet ?? '—' }}</td>
                <td>{{ $c->contributeur?->email ?? '—' }}</td>
                <td>{{ number_format($c->montant_promis, 0, ',', ' ') }} FCFA</td>
                <td>{{ $c->montant_paye > 0 ? number_format($c->montant_paye, 0, ',', ' ').' FCFA' : '—' }}</td>
                <td>{{ $moyens[$c->moyen_paiement] ?? '—' }}</td>
                <td>
                    <span class="badge badge-{{ $badgeMap[$c->statut] ?? 'secondary' }}">
                        {{ $libelles[$c->statut] ?? $c->statut }}
                    </span>
                </td>
                <td>{{ $c->created_at->format('d/m/Y') }}</td>
            </tr>
        @empty
            <tr>
                <td colspan="8" style="text-align:center;padding:20px;color:#999;">
                    Aucun contributeur pour ce filtre.
                </td>
            </tr>
        @endforelse
    </tbody>
</table>

@if ($contributions->isNotEmpty())
    <div class="totals" style="margin-top:14px;">
        Total promis : <span>{{ number_format($contributions->sum('montant_promis'), 0, ',', ' ') }} FCFA</span>
        &nbsp;·&nbsp;
        Total payé : <span>{{ number_format($contributions->sum('montant_paye'), 0, ',', ' ') }} FCFA</span>
    </div>
@endif

<footer>
    FAACI — Plateforme numérique &nbsp;·&nbsp; Document généré automatiquement
</footer>
</body>
</html>
