<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Erreur</title>
    <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.2/dist/css/bootstrap.min.css" rel="stylesheet">
</head>
<body>
    <div class="container text-center mt-5">
        <h1 class="display-4 text-danger">Une erreur est survenue</h1>
        <p class="lead"><?= esc($message) ?></p>
        <a href="/" class="btn btn-primary mt-3">Retour à l'accueil</a>
    </div>
</body>
</html>
