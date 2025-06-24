<div class="container-fluid">
    <div class="row">
        <div class="col-12">
            <div class="card">
                <div class="card-header">
                    <h3 class="card-title">Liste des Messages Clients</h3>
                </div>
                <div class="card-body">
                    <div class="table-responsive">
                        <table class="table table-bordered table-striped">
                            <thead>
                                <tr>
                                    <th>ID</th>
                                    <th>Client</th>
                                    <th>Message</th>
                                    <th>Date</th>
                                    <th>Ticket lié</th>
                                </tr>
                            </thead>
                            <tbody>
                                <?php if (!empty($messages)): ?>
                                    <?php foreach ($messages as $msg): ?>
                                        <tr>
                                            <td><?= $msg['id'] ?></td>
                                            <td><?= $msg['client_nom'] ?></td>
                                            <td><?= nl2br(htmlspecialchars($msg['message'])) ?></td>
                                            <td><?= $msg['date_message'] ?></td>
                                            <td>
                                                <?php if ($msg['id_ticket']): ?>
                                                    <a href="<?= site_url('/admin/tickets') ?>#ticket<?= $msg['id_ticket'] ?>">Ticket #<?= $msg['id_ticket'] ?></a>
                                                <?php else: ?>
                                                    -
                                                <?php endif; ?>
                                            </td>
                                        </tr>
                                    <?php endforeach; ?>
                                <?php else: ?>
                                    <tr><td colspan="5" class="text-center">Aucun message trouvé.</td></tr>
                                <?php endif; ?>
                            </tbody>
                        </table>
                    </div>
                </div>
            </div>
        </div>
    </div>
</div>
