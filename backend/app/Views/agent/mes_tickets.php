<?php
// ============ Vue corrigée (mes-tickets.php) ============
?>
<?= $this->extend('agent/agent') ?>

<?= $this->section('content') ?>
<?php if (session()->getFlashdata('success')): ?>
    <div class="alert alert-success"><?= session('success') ?></div>
<?php endif; ?>
<?php if (session()->getFlashdata('error')): ?>
    <div class="alert alert-danger"><?= session('error') ?></div>
<?php endif; ?>
<div class="container-fluid">
<div class="row">
    <div class="col-12">
        <div class="card">
            <div class="card-header">
                <h6 class="m-0 font-weight-bold">Mes tickets</h6>
            </div>
            <div class="container-fluid">
                <div class="table-responsive">
                    <table class="table table-hover table-bordered align-middle table-lg" style="font-size:1.1rem;">
                        <thead class="table-light">
                            <tr>
                                <th style="min-width: 80px;">ID</th>
                                <th style="min-width: 220px;">Titre</th>
                                <th style="min-width: 180px;">Client</th>
                                <th style="min-width: 180px;">Catégorie</th>
                                <th style="min-width: 140px;">Statut</th>
                                <th style="min-width: 180px;">Date</th>
                                <th style="min-width: 140px;">Commentaires</th>
                                <th style="min-width: 240px;">Actions</th>
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
                                    <a href="<?= base_url('agent/message/' . $ticket['id_message']) ?>" 
                                       class="btn btn-info btn-sm">
                                        <i class="fas fa-comments"></i> 
                                        Voir les commentaires
                                    </a>
                                </td>
                                <td>
                                    <div class="d-flex gap-2 flex-wrap">
                                        <form method="post" action="<?= site_url('agent/tickets/' . $ticket['id'] . '/status') ?>">
                                            <input type="hidden" name="status" value="en_cours">
                                            <?= csrf_field() ?>
                                            <button class="btn btn-sm btn-primary" type="submit">
                                                <i class="fas fa-play"></i> En cours
                                            </button>
                                        </form>
                                        <form method="post" action="<?= site_url('agent/tickets/' . $ticket['id'] . '/status') ?>">
                                            <input type="hidden" name="status" value="resolu">
                                            <?= csrf_field() ?>
                                            <button class="btn btn-sm btn-success" type="submit">
                                                <i class="fas fa-check"></i> Résolu
                                            </button>
                                        </form>
                                        <form method="post" action="<?= site_url('agent/tickets/' . $ticket['id'] . '/status') ?>">
                                            <input type="hidden" name="status" value="ferme">
                                            <?= csrf_field() ?>
                                            <button class="btn btn-sm btn-danger" type="submit">
                                                <i class="fas fa-times"></i> Fermé
                                            </button>
                                        </form>
                                    </div>
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
</div>

<script src="<?= base_url('assets/js/ticket-update.js') ?>"></script>
<script>
    // Configuration globale pour les appels AJAX
    const ticketConfig = {
        baseUrl: '<?= base_url() ?>',
        csrfToken: '<?= csrf_hash() ?>',
        csrfName: '<?= csrf_token() ?>'
    };

    function updateStatus(ticketId, newStatus) {
        fetch(ticketConfig.baseUrl + 'agent/tickets/' + ticketId + '/status', {
            method: 'POST',
            credentials: 'same-origin',
            headers: {
                'Content-Type': 'application/x-www-form-urlencoded',
                [ticketConfig.csrfName]: ticketConfig.csrfToken
            },
            body: 'status=' + encodeURIComponent(newStatus)
        })
        .then(response => response.json())
        .then(data => {
            if (data.success) {
                location.reload();
            } else {
                alert(data.message || 'Erreur lors de la mise à jour du statut');
            }
        })
        .catch(() => {
            alert('Erreur réseau ou serveur');
        });
    }
</script>
<style>
    .dropdown-menu { max-height: none !important; overflow: visible !important; }
    table.table-lg th, table.table-lg td { padding-top: 1.2rem !important; padding-bottom: 1.2rem !important; }
</style>
<?= $this->endSection() ?>