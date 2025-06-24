<!DOCTYPE html>
<html lang="fr">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Dashboard Client</title>
    <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.2/dist/css/bootstrap.min.css" rel="stylesheet">
</head>
<body>
<nav class="navbar navbar-expand-lg navbar-light bg-light mb-4">
    <div class="container-fluid">
        <a class="navbar-brand" href="<?= site_url('/client/dashboard') ?>">Espace Client</a>
        <div class="d-flex">
            <span class="navbar-text me-3">Bienvenue, <?= session('client_nom') ?></span>
            <a href="<?= site_url('/client/logout') ?>" class="btn btn-outline-danger">Déconnexion</a>
        </div>
    </div>
</nav>
<div class="container">
    <div class="row">
        <div class="col-md-6 col-lg-4">
            <div class="card text-center mb-4">
                <div class="card-body">
                    <h5 class="card-title">Messages envoyés</h5>
                    <p class="display-4 text-primary"><?= $nbMessages ?></p>
                </div>
            </div>
        </div>
        <!-- Ajoute ici d'autres stats si besoin -->
    </div>
</div>
</body>
</html>
