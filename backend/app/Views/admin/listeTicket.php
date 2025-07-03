<?php
// app/Views/admin/listeTicket.php
$statusColors = [
    'ouvert' => 'danger',
    'en_cours' => 'info',
    'resolu' => 'success',
    'ferme' => 'secondary'
];
?>

<div class="container-fluid mt-4">
    <div class="row">
        <div class="col-12">
            <div class="card shadow-lg border-0 rounded-3">
                <div class="card-header bg-primary text-white rounded-top-3">
                    <h3 class="card-title mb-0"><i class="fas fa-ticket-alt me-2"></i>Liste des Tickets</h3>
                </div>
                <div class="card-body">
                    <!-- Messages de succès ou d'erreur -->
                    <?php if (session()->getFlashdata('success')): ?>
                        <div class="alert alert-success alert-dismissible fade show">
                            <i class="fas fa-check-circle me-2"></i><?= session()->getFlashdata('success') ?>
                            <button type="button" class="btn-close" data-bs-dismiss="alert"></button>
                        </div>
                    <?php endif; ?>
                    <?php if (session()->getFlashdata('error')): ?>
                        <div class="alert alert-danger alert-dismissible fade show">
                            <i class="fas fa-exclamation-triangle me-2"></i><?= session()->getFlashdata('error') ?>
                            <button type="button" class="btn-close" data-bs-dismiss="alert"></button>
                        </div>
                    <?php endif; ?>

                    <!-- Filtres -->
                    <form method="get" class="d-flex flex-wrap gap-3 mb-4">
                        <div class="input-group" style="max-width: 300px;">
                            <span class="input-group-text bg-primary text-white"><i class="fas fa-search"></i></span>
                            <input type="text" id="searchInput" name="mot_cle" class="form-control" placeholder="Rechercher par mot-clé..." value="<?= esc($_GET['mot_cle'] ?? '') ?>">
                        </div>
                        <select id="statusFilter" name="statut" class="form-select" style="max-width: 200px;">
                            <option value="">Tous les statuts</option>
                            <option value="ouvert" <?= (isset($_GET['statut']) && $_GET['statut'] == 'ouvert') ? 'selected' : '' ?>>Ouvert</option>
                            <option value="en_cours" <?= (isset($_GET['statut']) && $_GET['statut'] == 'en_cours') ? 'selected' : '' ?>>En cours</option>
                            <option value="resolu" <?= (isset($_GET['statut']) && $_GET['statut'] == 'resolu') ? 'selected' : '' ?>>Résolu</option>
                            <option value="ferme" <?= (isset($_GET['statut']) && $_GET['statut'] == 'ferme') ? 'selected' : '' ?>>Fermé</option>
                        </select>
                        <select id="priorityFilter" name="priorite" class="form-select" style="max-width: 200px;">
                            <option value="">Toutes les priorités</option>
                            <option value="basse" <?= (isset($_GET['priorite']) && $_GET['priorite'] == 'basse') ? 'selected' : '' ?>>Basse</option>
                            <option value="moyenne" <?= (isset($_GET['priorite']) && $_GET['priorite'] == 'moyenne') ? 'selected' : '' ?>>Moyenne</option>
                            <option value="haute" <?= (isset($_GET['priorite']) && $_GET['priorite'] == 'haute') ? 'selected' : '' ?>>Haute</option>
                        </select>
                        <select id="categoryFilter" name="categorie" class="form-select" style="max-width: 200px;">
                            <option value="">Toutes les catégories</option>
                            <?php foreach ($categories as $category): ?>
                                <option value="<?= esc($category['nom']) ?>" <?= (isset($_GET['categorie']) && $_GET['categorie'] == $category['nom']) ? 'selected' : '' ?>><?= esc($category['nom']) ?></option>
                            <?php endforeach; ?>
                        </select>
                        <button type="submit" class="btn btn-primary">Filtrer</button>
                    </form>

                    <!-- Tableau des tickets -->
                    <div class="table-responsive">
                        <table class="table table-hover align-middle mb-0">
                            <thead class="table-light">
                                <tr>
                                    <th class="px-4 py-3">ID</th>
                                    <th class="px-4 py-3">Titre</th>
                                    <th class="px-4 py-3">Client</th>
                                    <th class="px-4 py-3">Catégorie</th>
                                    <th class="px-4 py-3">Agents assignés</th>
                                    <th class="px-4 py-3">Groupe</th>
                                    <th class="px-4 py-3">Priorité</th>
                                    <th class="px-4 py-3">Statut</th>
                                </tr>
                            </thead>
                            <tbody style="background-color: rgb(26, 8, 166); color: #fff;">
                                <?php foreach ($tickets as $ticket): ?>
                                    <tr class="table-row-hover">
                                        <td class="px-4 py-3"><?= $ticket['id'] ?></td>
                                        <td class="px-4 py-3">
                                            <a href="#" class="text-white text-decoration-underline" data-bs-toggle="modal" data-bs-target="#ticketModal<?= $ticket['id'] ?>">
                                                <?= esc($ticket['titre']) ?>
                                            </a>
                                        </td>
                                        <td class="px-4 py-3"><?= esc($ticket['client_nom']) ?></td>
                                        <td class="px-4 py-3"><?= esc($ticket['categorie_nom']) ?></td>
                                        <td class="px-4 py-3">
                                            <?php if (!empty($ticket['agents_assignes'])): ?>
                                                <?php foreach ($ticket['agents_assignes'] as $index => $agent): ?>
                                                    <span class="badge bg-light text-dark me-1">
                                                        <?= esc($agent['agent_nom']) ?>
                                                    </span>
                                                    <?php if ($index < count($ticket['agents_assignes']) - 1): ?>
                                                        <br>
                                                    <?php endif; ?>
                                                <?php endforeach; ?>
                                            <?php else: ?>
                                                <span class="text-muted">Aucun agent assigné</span>
                                            <?php endif; ?>
                                        </td>
                                        <td class="px-4 py-3"><?= esc($ticket['groupe_nom'] ?? 'Non assigné') ?></td>
                                        <td class="px-4 py-3">
                                            <span class="badge bg-<?= $ticket['priorite'] === 'haute' ? 'danger' : ($ticket['priorite'] === 'moyenne' ? 'warning' : 'info') ?>">
                                                <?= ucfirst($ticket['priorite']) ?>
                                            </span>
                                        </td>
                                        <td class="px-4 py-3">
                                            <span class="badge bg-<?= $statusColors[$ticket['statut']] ?? 'secondary' ?>">
                                                <?= ucfirst(str_replace('_', ' ', $ticket['statut'])) ?>
                                            </span>
                                        </td>
                                    </tr>

                                    <!-- Modal pour les détails du ticket -->
                                    <div class="modal fade" id="ticketModal<?= $ticket['id'] ?>" tabindex="-1">
                                        <div class="modal-dialog modal-lg">
                                            <div class="modal-content">
                                                <div class="modal-header bg-primary text-white">
                                                    <h5 class="modal-title">Détails du Ticket #<?= $ticket['id'] ?></h5>
                                                    <button type="button" class="btn-close" data-bs-dismiss="modal"></button>
                                                </div>
                                                <div class="modal-body">
                                                    <div class="row">
                                                        <div class="col-md-6">
                                                            <h6>Titre</h6>
                                                            <p><?= esc($ticket['titre']) ?></p>
                                                            
                                                            <h6>Description</h6>
                                                            <p><?= esc($ticket['description']) ?></p>

                                                            <!-- Affichage des messages du ticket -->
                                                            <h6>Messages</h6>
                                                            <?php if (!empty($messagesParTicket[$ticket['id']])): ?>
                                                                <ul class="list-group mb-3">
                                                                    <?php foreach ($messagesParTicket[$ticket['id']] as $msg): ?>
                                                                        <li class="list-group-item">
                                                                            <strong><?= esc($msg['client_nom'] ?? 'Client') ?>:</strong>
                                                                            <?= esc($msg['message']) ?>
                                                                            <br>
                                                                            <small class="text-muted"><?= date('d/m/Y H:i', strtotime($msg['date_message'])) ?></small>
                                                                        </li>
                                                                    <?php endforeach; ?>
                                                                </ul>
                                                            <?php else: ?>
                                                                <p class="text-muted">Aucun message pour ce ticket.</p>
                                                            <?php endif; ?>
                                                            
                                                            <h6>Client</h6>
                                                            <p><?= esc($ticket['client_nom']) ?></p>
                                                            
                                                            <h6>Agents assignés</h6>
                                                            <?php if (!empty($ticket['agents_assignes'])): ?>
                                                                <ul class="list-unstyled">
                                                                    <?php foreach ($ticket['agents_assignes'] as $agent): ?>
                                                                        <li>
                                                                            <span class="badge bg-primary">
                                                                                <?= esc($agent['agent_nom']) ?>
                                                                            </span>
                                                                            <small class="text-muted">(<?= esc($agent['agent_email']) ?>)</small>
                                                                        </li>
                                                                    <?php endforeach; ?>
                                                                </ul>
                                                            <?php else: ?>
                                                                <p class="text-muted">Aucun agent assigné</p>
                                                            <?php endif; ?>
                                                        </div>
                                                        <div class="col-md-6">
                                                            <h6>Catégorie</h6>
                                                            <p><?= esc($ticket['categorie_nom']) ?></p>
                                                            
                                                            <h6>Groupe</h6>
                                                            <p><?= esc($ticket['groupe_nom'] ?? 'Non assigné') ?></p>
                                                            
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
                                                            <p><?= esc($ticket['THoraire']) ?> h</p>
                                                            
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

<!-- Styles personnalisés -->
<style>
    .card {
        transition: transform 0.3s ease, box-shadow 0.3s ease;
    }
    .card:hover {
        transform: translateY(-5px);
        box-shadow: 0 8px 20px rgba(0, 0, 0, 0.15) !important;
    }
    .table-row-hover:hover {
        background-color: rgb(40, 20, 180) !important;
        transition: background-color 0.2s ease;
    }
    .table th, .table td {
        border: none;
    }
    .table thead th {
        font-weight: 600;
        background-color: #f8f9fa;
    }
    .badge {
        font-size: 0.9rem;
        padding: 0.5em 1em;
    }
    @media (max-width: 768px) {
        .d-flex {
            flex-direction: column;
        }
        .input-group, .form-select {
            max-width: 100% !important;
            margin-bottom: 0.5rem;
        }
    }
</style>

<!-- Script pour la filtration dynamique -->
<script>
    document.addEventListener('DOMContentLoaded', function() {
        const searchInput = document.getElementById('searchInput');
        const statusFilter = document.getElementById('statusFilter');
        const priorityFilter = document.getElementById('priorityFilter');
        const categoryFilter = document.getElementById('categoryFilter');
        const tableRows = document.querySelectorAll('tbody tr');

        function filterTable() {
            const searchValue = searchInput.value.toLowerCase();
            const statusValue = statusFilter.value.toLowerCase();
            const priorityValue = priorityFilter.value.toLowerCase();
            const categoryValue = categoryFilter.value.toLowerCase();

            tableRows.forEach(row => {
                const title = row.cells[1].textContent.toLowerCase();
                const client = row.cells[2].textContent.toLowerCase();
                const category = row.cells[3].textContent.toLowerCase();
                const priority = row.cells[6].textContent.toLowerCase();
                const status = row.cells[7].textContent.toLowerCase();

                const matchesSearch = title.includes(searchValue) || client.includes(searchValue);
                const matchesStatus = !statusValue || status.includes(statusValue);
                const matchesPriority = !priorityValue || priority.includes(priorityValue);
                const matchesCategory = !categoryValue || category.includes(categoryValue);

                row.style.display = matchesSearch && matchesStatus && matchesPriority && matchesCategory ? '' : 'none';
            });
        }

        searchInput.addEventListener('input', filterTable);
        statusFilter.addEventListener('change', filterTable);
        priorityFilter.addEventListener('change', filterTable);
        categoryFilter.addEventListener('change', filterTable);
    });
</script>