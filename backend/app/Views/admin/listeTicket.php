<?php
$statusColors = [
    'ouvert' => 'danger',
    'en_cours' => 'info',
    'resolu' => 'success',
    'ferme' => 'secondary'
];
?>

<div class="container-fluid">
    <div class="row">
        <div class="col-12">
            <div class="card">
                <div class="card-header">
                    <h3 class="card-title">Liste des Tickets</h3>
                </div>
                <div class="card-body">
                    <?php if (session()->getFlashdata('success')): ?>
                        <div class="alert alert-success">
                            <?= session()->getFlashdata('success') ?>
                        </div>
                    <?php endif; ?>

                    <?php if (session()->getFlashdata('error')): ?>
                        <div class="alert alert-danger">
                            <?= session()->getFlashdata('error') ?>
                        </div>
                    <?php endif; ?>

                    <div class="table-responsive">
                        <table class="table table-bordered table-striped">
                            <thead>
                                <tr>
                                    <th>ID</th>
                                    <th>Titre</th>
                                    <th>Client</th>
                                    <th>Catégorie</th>
                                    <th>Agents assignés</th>
                                    <th>Groupe</th>
                                    <th>Priorité</th>
                                    <th>Statut</th>
                                </tr>
                            </thead>
                            <tbody>
                                <?php foreach ($tickets as $ticket): ?>
                                    <tr>
                                        <td><?= $ticket['id'] ?></td>
                                        <td>
                                            <a href="#" data-bs-toggle="modal" data-bs-target="#ticketModal<?= $ticket['id'] ?>">
                                                <?= $ticket['titre'] ?>
                                            </a>
                                        </td>
                                        <td><?= $ticket['client_nom'] ?></td>
                                        <td><?= $ticket['categorie_nom'] ?></td>
                                        <td>
                                            <?php if (!empty($ticket['agents_assignes'])): ?>
                                                <?php foreach ($ticket['agents_assignes'] as $index => $agent): ?>
                                                    <span class="badge bg-primary me-1">
                                                        <?= $agent['agent_nom'] ?>
                                                    </span>
                                                    <?php if ($index < count($ticket['agents_assignes']) - 1): ?>
                                                        <br>
                                                    <?php endif; ?>
                                                <?php endforeach; ?>
                                            <?php else: ?>
                                                <span class="text-muted">Aucun agent assigné</span>
                                            <?php endif; ?>
                                        </td>
                                        <td><?= $ticket['groupe_nom'] ?? 'Non assigné' ?></td>
                                        <td>
                                            <span class="badge bg-<?= $ticket['priorite'] === 'haute' ? 'danger' : ($ticket['priorite'] === 'moyenne' ? 'warning' : 'info') ?>">
                                                <?= ucfirst($ticket['priorite']) ?>
                                            </span>
                                        </td>
                                        <td>
                                            <span class="badge bg-<?= $statusColors[$ticket['statut']] ?? 'secondary' ?>">
                                                <?= ucfirst(str_replace('_', ' ', $ticket['statut'])) ?>
                                            </span>
                                        </td>
                                    </tr>

                                    <!-- Modal pour les détails du ticket -->
                                    <div class="modal fade" id="ticketModal<?= $ticket['id'] ?>" tabindex="-1">
                                        <div class="modal-dialog modal-lg">
                                            <div class="modal-content">
                                                <div class="modal-header">
                                                    <h5 class="modal-title">Détails du Ticket #<?= $ticket['id'] ?></h5>
                                                    <button type="button" class="btn-close" data-bs-dismiss="modal"></button>
                                                </div>
                                                <div class="modal-body">
                                                    <div class="row">
                                                        <div class="col-md-6">
                                                            <h6>Titre</h6>
                                                            <p><?= $ticket['titre'] ?></p>
                                                            
                                                            <h6>Description</h6>
                                                            <p><?= $ticket['description'] ?></p>
                                                            
                                                            <h6>Client</h6>
                                                            <p><?= $ticket['client_nom'] ?></p>
                                                            
                                                            <h6>Agents assignés</h6>
                                                            <?php if (!empty($ticket['agents_assignes'])): ?>
                                                                <ul class="list-unstyled">
                                                                    <?php foreach ($ticket['agents_assignes'] as $agent): ?>
                                                                        <li>
                                                                            <span class="badge bg-primary">
                                                                                <?= $agent['agent_nom'] ?>
                                                                            </span>
                                                                            <small class="text-muted">(<?= $agent['agent_email'] ?>)</small>
                                                                        </li>
                                                                    <?php endforeach; ?>
                                                                </ul>
                                                            <?php else: ?>
                                                                <p class="text-muted">Aucun agent assigné</p>
                                                            <?php endif; ?>
                                                        </div>
                                                        <div class="col-md-6">
                                                            <h6>Catégorie</h6>
                                                            <p><?= $ticket['categorie_nom'] ?></p>
                                                            
                                                            <h6>Groupe</h6>
                                                            <p><?= $ticket['groupe_nom'] ?? 'Non assigné' ?></p>
                                                            
                                                            <h6>Priorité</h6>
                                                            <p>
                                                                <span class="badge bg-<?= $ticket['priorite'] === 'haute' ? 'danger' : ($ticket['priorite'] === 'moyenne' ? 'warning' : 'info') ?>">
                                                                    <?= ucfirst($ticket['priorite']) ?>
                                                                </span>
                                                            </p>
                                                            
                                                            <h6>Statut</h6>
                                                            <p>
                                                                <span class="badge bg-<?= $statusColors[$ticket['statut']] ?? 'secondary' ?>">
                                                                    <?= ucfirst(str_replace('_', ' ', $ticket['statut'])) ?>
                                                                </span>
                                                            </p>
                                                            
                                                            <h6>Horaire de travail</h6>
                                                            <p><?= $ticket['THoraire'] ?> h</p>
                                                            
                                                            <h6>Période</h6>
                                                            <p>
                                                                Du <?= date('d/m/Y H:i', strtotime($ticket['date_heure_debut'])) ?><br>
                                                                Au <?= date('d/m/Y H:i', strtotime($ticket['date_heure_fin'])) ?>
                                                            </p>
                                                        </div>
                                                    </div>
                                                </div>
                                            </div>
                                        </div>
                                    </div>
                                <?php endforeach; ?>
                            </tbody>
                        </table>
                    </div>
                </div>
            </div>
        </div>
    </div>
</div>