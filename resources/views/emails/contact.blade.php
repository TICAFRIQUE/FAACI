<!DOCTYPE html>
<html lang="fr">
<head><meta charset="utf-8"><title>Message de contact</title></head>
<body style="font-family:sans-serif;font-size:15px;color:#333;line-height:1.6;">
    <p><strong>De :</strong> {{ $nomExpediteur }} ({{ $emailExpediteur }})</p>
    <p><strong>Sujet :</strong> {{ $sujet }}</p>
    <hr>
    <p>{{ $corps }}</p>
</body>
</html>
