<div class="d-flex align-items-center gap-2">
    <div class="bg-faaci-gray rounded-circle d-flex align-items-center justify-content-center flex-shrink-0 fw-semibold text-faaci-navy" style="width:40px; height:40px;">
        {{ mb_strtoupper(mb_substr($membre->prenom, 0, 1).mb_substr($membre->nom, 0, 1)) }}
    </div>
    <div>
        <div class="fw-semibold">{{ $membre->nom_complet }}</div>
        <div class="text-muted small">{{ $membre->email }}</div>
    </div>
</div>
