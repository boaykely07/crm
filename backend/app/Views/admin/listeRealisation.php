<!-- Section du contenu principal en pleine largeur -->

<!-- En-tête avec barre de recherche -->
<div class="d-flex justify-content-between align-items-center mb-4">
    <h2 class="mb-0">
        <i class="fas fa-check-circle text-success me-2"></i>Liste des Réalisations
    </h2>
    <div class="d-flex gap-3">
        <div class="input-group" style="width: 280px;">
            <input type="text" class="form-control" id="searchRealisations" placeholder="Rechercher une réalisation..." aria-label="Rechercher">
            <button class="btn btn-outline-primary">
                <i class="fas fa-search"></i>
            </button>
        </div>
        <select class="form-select" style="width: 180px;" id="filterStatus">
            <option value="">Tous les statuts</option>
            <option value="validee">Validée</option>
            <option value="rejetee">Rejetée</option>
            <option value="en_attente">En attente</option>
        </select>
    </div>
</div>

<!-- Tableau des réalisations -->
<div class="card shadow-sm">
    <div class="card-body p-0">
        <div class="table-responsive">
            <table class="table table-hover align-middle mb-0">
                <thead class="table-light">
                    <tr>
                        <th class="px-4 py-3" style="width: 70px;">ID</th>
                        <th class="px-4 py-3" style="min-width: 200px;">Titre</th>
                        <th class="px-4 py-3" style="min-width: 180px;">Département</th>
                        <th class="px-4 py-3" style="width: 150px;">Montant</th>
                        <th class="px-4 py-3" style="width: 140px;">Statut</th>
                        <th class="px-4 py-3" style="width: 150px;">Période</th>
                        <th class="px-4 py-3 text-center" style="width: 180px;">Actions</th>
                    </tr>
                </thead>
                <tbody>
                    <?php if (!empty($realisations)): ?>
                        <?php foreach ($realisations as $realisation): ?>
                            <tr>
                                <td class="px-4 py-3 fw-medium">#<?= $realisation['id'] ?></td>
                                <td class="px-4 py-3 fw-medium text-primary">
                                    <?= $realisation['titre'] ?>
                                </td>
                                <td class="px-4 py-3">
                                    <span class="d-inline-block text-truncate" style="max-width: 180px;" title="<?= $realisation['nom_departement'] ?>">
                                        <?= $realisation['nom_departement'] ?>
                                    </span>
                                </td>
                                <td class="px-4 py-3 fw-semibold">
                                    <?= number_format($realisation['montant'], 2, ',', ' ') ?> €
                                </td>
                                <td class="px-4 py-3">
                                    <span class="badge rounded-pill px-3 py-2
                                        <?= $realisation['statut'] === 'validee' ? 'bg-success' : 
                                            ($realisation['statut'] === 'rejetee' ? 'bg-danger' : 'bg-warning text-dark') ?>">
                                        <i class="fas fa-<?= $realisation['statut'] === 'validee' ? 'check-circle' : 
                                            ($realisation['statut'] === 'rejetee' ? 'times-circle' : 'clock') ?> me-1"></i>
                                        <?= ucfirst($realisation['statut']) ?>
                                    </span>
                                </td>
                                <td class="px-4 py-3">
                                    <?= $realisation['mois'] ?> <?= $realisation['annee'] ?>
                                </td>
                                <td class="px-4 py-3">
                                    <div class="d-flex justify-content-center gap-2">
                                        <?php if ($realisation['statut'] !== 'validee'): ?>
                                            <a href="/admin/validerRealisation/<?= $realisation['id'] ?>" 
                                               class="btn btn-success btn-sm" 
                                               data-bs-toggle="tooltip" 
                                               title="Valider">
                                                <i class="fas fa-check"></i>
                                            </a>
                                        <?php endif; ?>
                                        <a href="/admin/modifierRealisation/<?= $realisation['id'] ?>" 
                                           class="btn btn-warning btn-sm" 
                                           data-bs-toggle="tooltip" 
                                           title="Modifier">
                                            <i class="fas fa-edit"></i>
                                        </a>
                                        <button onclick="confirmDelete(<?= $realisation['id'] ?>)" 
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
                                    <h5>Aucune réalisation trouvée</h5>
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
<div class="modal fade" id="deleteModal" tabindex="-1" aria-hidden="true">
    <div class="modal-dialog modal-sm modal-dialog-centered">
        <div class="modal-content">
            <div class="modal-header">
                <h5 class="modal-title">Confirmation</h5>
                <button type="button" class="btn-close" data-bs-dismiss="modal" aria-label="Fermer"></button>
            </div>
            <div class="modal-body">
                Êtes-vous sûr de vouloir supprimer cette réalisation ?
            </div>
            <div class="modal-footer">
                <button type="button" class="btn btn-sm btn-secondary" data-bs-dismiss="modal">Annuler</button>
                <a href="#" id="confirmDeleteBtn" class="btn btn-sm btn-danger">Supprimer</a>
            </div>
        </div>
    </div>
</div>

<!-- Scripts pour le fonctionnement du tableau amélioré -->
<script>
    document.addEventListener('DOMContentLoaded', function() {
        // Activation des tooltips
        var tooltipTriggerList = [].slice.call(document.querySelectorAll('[data-bs-toggle="tooltip"]'));
        tooltipTriggerList.map(function (tooltipTriggerEl) {
            return new bootstrap.Tooltip(tooltipTriggerEl);
        });

        // Recherche en temps réel
        document.getElementById('searchRealisations').addEventListener('keyup', function() {
            filterTable();
        });

        // Filtre par statut
        document.getElementById('filterStatus').addEventListener('change', function() {
            filterTable();
        });
    });

    function filterTable() {
        var input = document.getElementById('searchRealisations').value.toLowerCase();
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
        document.getElementById('confirmDeleteBtn').onclick = function() {
            window.location.href = '/admin/supprimerRealisation/' + id;
        };
        modal.show();
    }
</script>