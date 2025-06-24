<!-- Section du contenu principal en pleine largeur -->

    <!-- En-tête avec barre de recherche -->
    <div class="d-flex justify-content-between align-items-center mb-4">
        <h2 class="mb-0">
            <i class="fas fa-chart-line text-primary me-2"></i>Liste des Prévisions
        </h2>
        <div class="d-flex gap-3">
            <div class="input-group" style="width: 280px;">
                <input type="text" class="form-control" id="searchPrevisions" placeholder="Rechercher une prévision..." aria-label="Rechercher">
                <button class="btn btn-outline-primary">
                    <i class="fas fa-search"></i>
                </button>
            </div>
            <select class="form-select" style="width: 180px;" id="filterStatus">
                <option value="">Tous les statuts</option>
                <option value="en_attente">En attente</option>
                <option value="validee">Validée</option>
                <option value="rejetee">Rejetée</option>
            </select>
        </div>
    </div>

    <!-- Tableau des prévisions -->
    <div class="card shadow-sm">
        <div class="card-body p-0">
            <div class="table-responsive">
                <table class="table table-hover align-middle mb-0">
                    <thead class="table-light">
                        <tr>
                            <th class="px-4 py-3" style="width: 70px;">ID</th>
                            <th class="px-4 py-3" style="min-width: 200px;">Titre</th>
                            <th class="px-4 py-3" style="min-width: 180px;">Département</th>
                            <th class="px-4 py-3" style="width: 150px;">Solde de départ</th>
                            <th class="px-4 py-3" style="width: 140px;">Statut</th>
                            <th class="px-4 py-3" style="width: 150px;">Période</th>
                            <th class="px-4 py-3 text-center" style="width: 180px;">Actions</th>
                        </tr>
                    </thead>
                    <tbody>
                        <?php if (!empty($previsions)): ?>
                            <?php foreach ($previsions as $prevision): ?>
                                <tr>
                                    <td class="px-4 py-3 fw-medium">#<?= $prevision['id'] ?></td>
                                    <td class="px-4 py-3 fw-medium text-primary">
                                        <?= $prevision['titre'] ?>
                                    </td>
                                    <td class="px-4 py-3">
                                        <span class="d-inline-block text-truncate" style="max-width: 180px;" title="<?= $prevision['nom_departement'] ?>">
                                            <?= $prevision['nom_departement'] ?>
                                        </span>
                                    </td>
                                    <td class="px-4 py-3 fw-semibold">
                                        <?= number_format($prevision['solde_depart'], 2, ',', ' ') ?> €
                                    </td>
                                    <td class="px-4 py-3">
                                        <span class="badge rounded-pill px-3 py-2
                                            <?= $prevision['statut'] === 'validee' ? 'bg-success' : 
                                                ($prevision['statut'] === 'rejetee' ? 'bg-danger' : 'bg-warning text-dark') ?>">
                                            <i class="fas fa-<?= $prevision['statut'] === 'validee' ? 'check-circle' : 
                                                ($prevision['statut'] === 'rejetee' ? 'times-circle' : 'clock') ?> me-1"></i>
                                            <?= ucfirst($prevision['statut']) ?>
                                        </span>
                                    </td>
                                    <td class="px-4 py-3">
                                        <?= date('F', mktime(0, 0, 0, $prevision['mois'], 1)) ?> <?= $prevision['annee'] ?>
                                    </td>
                                    <td class="px-4 py-3">
                                        <div class="d-flex justify-content-center gap-2">
                                            <?php if ($prevision['statut'] !== 'validee'): ?>
                                                <button onclick="validerPrevision(<?= $prevision['id'] ?>)" 
                                                        class="btn btn-success btn-sm" 
                                                        data-bs-toggle="tooltip" 
                                                        title="Valider">
                                                    <i class="fas fa-check"></i>
                                                </button>
                                            <?php endif; ?>
                                            <button onclick="modifierPrevision(<?= $prevision['id'] ?>)" 
                                                    class="btn btn-warning btn-sm" 
                                                    data-bs-toggle="tooltip" 
                                                    title="Modifier">
                                                <i class="fas fa-edit"></i>
                                            </button>
                                            <button onclick="confirmDelete(<?= $prevision['id'] ?>)" 
                                                    class="btn btn-danger btn-sm" 
                                                    data-bs-toggle="tooltip" 
                                                    title="Supprimer">
                                                <i class="fas fa-trash"></i>
                                            </button>
                                        </div>
                                    </td>
                                </tr>
                            <?php endforeach; ?>
                        <?php else: ?>
                            <tr>
                                <td colspan="7" class="text-center py-5">
                                    <div class="text-muted">
                                        <i class="fas fa-folder-open fa-3x mb-3"></i>
                                        <h5>Aucune prévision trouvée</h5>
                                        <p class="mb-0">Aucune donnée disponible pour le moment</p>
                                    </div>
                                </td>
                            </tr>
                        <?php endif; ?>
                    </tbody>
                </table>
            </div>
        </div>
    </div>


<!-- Modal de confirmation de suppression -->
<div class="modal fade" id="deleteModal" tabindex="-1">
    <div class="modal-dialog modal-sm modal-dialog-centered">
        <div class="modal-content">
            <div class="modal-header">
                <h5 class="modal-title">Confirmation</h5>
                <button type="button" class="btn-close" data-bs-dismiss="modal"></button>
            </div>
            <div class="modal-body">
                Êtes-vous sûr de vouloir supprimer cette prévision ?
            </div>
            <div class="modal-footer">
                <button type="button" class="btn btn-secondary" data-bs-dismiss="modal">Annuler</button>
                <button type="button" class="btn btn-danger" id="confirmDelete">Supprimer</button>
            </div>
        </div>
    </div>
</div>

<!-- Scripts -->
<script>
    document.addEventListener('DOMContentLoaded', function() {
        // Activation des tooltips
        var tooltipTriggerList = [].slice.call(document.querySelectorAll('[data-bs-toggle="tooltip"]'));
        tooltipTriggerList.map(function (tooltipTriggerEl) {
            return new bootstrap.Tooltip(tooltipTriggerEl);
        });

        // Recherche en temps réel
        document.getElementById('searchPrevisions').addEventListener('keyup', function() {
            filterTable();
        });

        // Filtre par statut
        document.getElementById('filterStatus').addEventListener('change', function() {
            filterTable();
        });
    });

    function filterTable() {
        var input = document.getElementById('searchPrevisions').value.toLowerCase();
        var status = document.getElementById('filterStatus').value;
        var rows = document.querySelectorAll('tbody tr');

        rows.forEach(function(row) {
            var text = row.textContent.toLowerCase();
            var statusCell = row.querySelector('td:nth-child(5)').textContent.toLowerCase();
            var matchesSearch = text.includes(input);
            var matchesStatus = status === '' || statusCell.includes(status);
            row.style.display = matchesSearch && matchesStatus ? '' : 'none';
        });
    }

    function confirmDelete(id) {
        var modal = new bootstrap.Modal(document.getElementById('deleteModal'));
        document.getElementById('confirmDelete').onclick = function() {
            window.location.href = '/admin/supprimerPrevision/' + id;
        };
        modal.show();
    }

    function modifierPrevision(id) {
        window.location.href = '/admin/modifierPrevision/' + id;
    }

    function validerPrevision(id) {
        window.location.href = '/admin/validerPrevision/' + id;
    }
</script>