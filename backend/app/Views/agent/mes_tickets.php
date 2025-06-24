<?php
// ============ Vue corrigée (mes-tickets.php) ============
?>
<?= $this->extend('agent/agent') ?>

<?= $this->section('content') ?>
<div class="row">
    <div class="col-12">
        <div class="card">
            <div class="card-header">
                <h6 class="m-0 font-weight-bold">Mes tickets</h6>
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
                                <th>Actions</th>
                            </tr>
                        </thead>
                        <tbody>
                            <?php foreach ($tickets as $ticket): ?>
                            <tr>
                                <td>#<?= $ticket['id'] ?></td>
                                <td><?= $ticket['titre'] ?></td>
                                <td><?= $ticket['client_nom'] ?></td>
                                <td><?= $ticket['categorie_nom'] ?></td>
                                <td>
                                    <span class="badge bg-<?= $ticket['statut'] === 'nouveau' ? 'danger' : 
                                        ($ticket['statut'] === 'en_attente' ? 'warning' : 
                                        ($ticket['statut'] === 'en_cours' ? 'info' : 
                                        ($ticket['statut'] === 'resolu' ? 'success' : 'secondary'))) ?>">
                                        <?= ucfirst(str_replace('_', ' ', $ticket['statut'])) ?>
                                    </span>
                                </td>
                                <td><?= date('d/m/Y H:i', strtotime($ticket['created_at'])) ?></td>
                                <td>
                                    <?php if ($ticket['statut'] !== 'resolu' && $ticket['statut'] !== 'ferme'): ?>
                                    <div class="btn-group">
                                        <button type="button" class="btn btn-sm btn-primary dropdown-toggle" 
                                                data-bs-toggle="dropdown">
                                            Mettre à jour
                                        </button>
                                        <ul class="dropdown-menu">
                                            <?php if ($ticket['statut'] === 'nouveau' || $ticket['statut'] === 'en_attente'): ?>
                                            <li>
                                                <a class="dropdown-item" href="#" 
                                                   onclick="updateStatus(<?= $ticket['id'] ?>, 'en_cours')">
                                                    Marquer comme en cours
                                                </a>
                                            </li>
                                            <?php endif; ?>
                                            <?php if ($ticket['statut'] === 'en_cours'): ?>
                                            <li>
                                                <a class="dropdown-item" href="#" 
                                                   onclick="updateStatus(<?= $ticket['id'] ?>, 'resolu')">
                                                    Marquer comme résolu
                                                </a>
                                            </li>
                                            <li>
                                                <a class="dropdown-item" href="#" 
                                                   onclick="updateStatus(<?= $ticket['id'] ?>, 'en_attente')">
                                                    Remettre en attente
                                                </a>
                                            </li>
                                            <?php endif; ?>
                                        </ul>
                                    </div>
                                    <?php endif; ?>
                                </td>
                            </tr>
                            <?php endforeach; ?>
                        </tbody>
                    </table>
                </div>
            </div>
        </div>
    </div>
</div>

<script src="<?= base_url('assets/js/ticket-update.js') ?>"></script>
<script>
    // Configuration globale pour les appels AJAX
    const ticketConfig = {
        baseUrl: '<?= base_url() ?>',
        csrfToken: '<?= csrf_hash() ?>',
        csrfName: '<?= csrf_token() ?>'
    };
</script>
<?= $this->endSection() ?>