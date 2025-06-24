<?= $this->extend('agent/agent') ?>

<?= $this->section('content') ?>
<div class="container-fluid">
    <div class="row">
        <div class="col-12">
            <h2 class="mb-4">Tableau de bord</h2>
        </div>
    </div>

    <!-- Statistiques individuelles -->
    <div class="row mb-4">
        <div class="col-12">
            <h4>Mes Statistiques</h4>
        </div>
        <div class="col-xl-3 col-md-6 mb-4">
            <div class="card stat-card primary">
                <div class="stat-title">Total des tickets</div>
                <div class="stat-value"><?= $stats_individuelles['total'] ?></div>
            </div>
        </div>
        <div class="col-xl-3 col-md-6 mb-4">
            <div class="card stat-card success">
                <div class="stat-title">Tickets résolus</div>
                <div class="stat-value"><?= $stats_individuelles['resolus'] ?></div>
            </div>
        </div>
        <div class="col-xl-3 col-md-6 mb-4">
            <div class="card stat-card warning">
                <div class="stat-title">En cours</div>
                <div class="stat-value"><?= $stats_individuelles['en_cours'] ?></div>
            </div>
        </div>
        <div class="col-xl-3 col-md-6 mb-4">
            <div class="card stat-card danger">
                <div class="stat-title">Nouveaux tickets</div>
                <div class="stat-value"><?= $stats_individuelles['nouveaux'] ?></div>
            </div>
        </div>
    </div>

    <!-- Statistiques du groupe --> 
    <?php if (isset($stats_groupe)): ?>
    <div class="row mb-4">
        <div class="col-12">
            <h4>Statistiques du Groupe</h4>
        </div>
        <div class="col-xl-3 col-md-6 mb-4">
            <div class="card stat-card primary">
                <div class="stat-title">Total des tickets</div>
                <div class="stat-value"><?= $stats_groupe['total'] ?></div>
            </div>
        </div>
        <div class="col-xl-3 col-md-6 mb-4">
            <div class="card stat-card success">
                <div class="stat-title">Tickets résolus</div>
                <div class="stat-value"><?= $stats_groupe['resolus'] ?></div>
            </div>
        </div>
        <div class="col-xl-3 col-md-6 mb-4">
            <div class="card stat-card warning">
                <div class="stat-title">En cours</div>
                <div class="stat-value"><?= $stats_groupe['en_cours'] ?></div>
            </div>
        </div>
        <div class="col-xl-3 col-md-6 mb-4">
            <div class="card stat-card danger">
                <div class="stat-title">Nouveaux tickets</div>
                <div class="stat-value"><?= $stats_groupe['nouveaux'] ?></div>
            </div>
        </div>
    </div>
    <?php endif; ?>

    <!-- Derniers tickets -->
    <div class="row">
        <div class="col-12">
            <div class="card">
                <div class="card-header">
                    <h6 class="m-0 font-weight-bold">Derniers tickets assignés</h6>
                </div>
                <div class="card-body">
                    <div class="table-responsive">
                        <table class="table">
                            <thead>
                                <tr>
                                    <th>ID</th>
                                    <th>Titre</th>
                                    <th>Client</th>
                                    <th>Catégorie</th>
                                    <th>Statut</th>
                                    <th>Date</th>
                                </tr>
                            </thead>
                            <tbody>
                                <?php foreach ($derniers_tickets as $ticket): ?>
                                <tr>
                                    <td>#<?= $ticket['id'] ?></td>
                                    <td><?= $ticket['titre'] ?></td>
                                    <td><?= $ticket['client_nom'] ?></td>
                                    <td><?= $ticket['categorie_nom'] ?></td>
                                    <td>
                                        <span class="badge bg-<?= $ticket['statut'] === 'nouveau' ? 'danger' : 
                                            ($ticket['statut'] === 'en_cours' ? 'warning' : 
                                            ($ticket['statut'] === 'resolu' ? 'success' : 'secondary')) ?>">
                                            <?= ucfirst($ticket['statut']) ?>
                                        </span>
                                    </td>
                                    <td><?= date('d/m/Y H:i', strtotime($ticket['created_at'])) ?></td>
                                </tr>
                                <?php endforeach; ?>
                            </tbody>
                        </table>
                    </div>
                </div>
            </div>
        </div>
    </div>
</div>
<?= $this->endSection() ?> 