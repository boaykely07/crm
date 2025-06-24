<!DOCTYPE html>
<html lang="fr">
<head>
    <meta charset="UTF-8">
    <title>Erreur</title>
    <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.2/dist/css/bootstrap.min.css" rel="stylesheet">
</head>
<body class="bg-light">
    <div class="container mt-5">
        <div class="alert alert-danger">
            <h1>Une erreur est survenue</h1>
            <p><?= esc($message ?? 'Erreur inconnue.') ?></p>
        </div>
    </div>
</body>
</html>
