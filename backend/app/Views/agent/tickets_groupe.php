<?= $this->extend('agent/agent') ?>

<?= $this->section('content') ?>
<div class="row">
    <div class="col-12">
        <div class="card">
            <div class="card-header">
                <h6 class="m-0 font-weight-bold">Tickets du groupe</h6>
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
                                <th>Agent</th>
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
                                    <?php if ($ticket['agent_nom']): ?>
                                        <?= $ticket['agent_nom'] . ' ' . $ticket['agent_prenom'] ?>
                                    <?php else: ?>
                                        <span class="text-muted">Non assigné</span>
                                    <?php endif; ?>
                                </td>
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
                                    <?php if ($ticket['statut'] === 'nouveau' && !$ticket['agent_nom']): ?>
                                    <button type="button" class="btn btn-sm btn-primary" 
                                            onclick="takeTicket(<?= $ticket['id'] ?>)">
                                        Prendre en charge
                                    </button>
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

<script>
function takeTicket(ticketId) {
    if (confirm('Êtes-vous sûr de vouloir prendre en charge ce ticket ?')) {
        $.ajax({
            url: `/agent/tickets/${ticketId}/take`,
            method: 'POST',
            contentType: 'application/json',
            success: function(response) {
                if (response.success) {
                    location.reload();
                } else {
                    alert('Une erreur est survenue lors de la prise en charge du ticket.');
                }
            },
            error: function() {
                alert('Une erreur est survenue lors de la prise en charge du ticket.');
            }
        });
    }
}
</script>
<?= $this->endSection() ?> 