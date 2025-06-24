<?= $this->extend('layout/main') ?>

<?= $this->section('content') ?>
    <h1 class="display-6 fw-bold text-primary mb-3">Bienvenue, <?= esc($user['prenom']) ?> !</h1>
    <p class="text-muted">Ceci est votre tableau de bord CRM.</p>
    <!-- Ajoutez ici le contenu principal du dashboard -->
<?= $this->endSection() ?>
