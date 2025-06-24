<!-- Section du contenu principal en pleine largeur -->

    <!-- Section pour afficher les messages d'erreur ou de succès -->
    <?php if (session()->has('error')): ?>
        <div class="alert alert-danger alert-dismissible fade show" role="alert">
            <?= session('error') ?>
            <button type="button" class="btn-close" data-bs-dismiss="alert" aria-label="Close"></button>
        </div>
    <?php endif; ?>

    <?php if (session()->has('success')): ?>
        <div class="alert alert-success alert-dismissible fade show" role="alert">
            <?= session('success') ?>
            <button type="button" class="btn-close" data-bs-dismiss="alert" aria-label="Close"></button>
        </div>
    <?php endif; ?>

    <div class="d-flex justify-content-between align-items-center mb-4">
        <h2 class="mb-0">
            <i class="fas fa-wallet text-primary me-2"></i>Liste des Budgets CRM
        </h2>
        <div class="d-flex gap-3">
            <div class="input-group" style="width: 280px;">
                <input type="text" class="form-control" id="searchBudgets" placeholder="Rechercher un budget..." aria-label="Rechercher">
                <button class="btn btn-outline-primary">
                    <i class="fas fa-search"></i>
                </button>
            </div>
            <select class="form-select" style="width: 180px;" id="filterStatus">
                <option value="">Tous les statuts</option>
                <option value="En attente">En attente</option>
                <option value="Validé">Validé</option>
            </select>
        </div>
    </div>

    <!-- Tableau des budgets -->
    <div class="card shadow-sm">
        <div class="card-body p-0">
            <div class="table-responsive">
                <table class="table table-hover align-middle mb-0">
                    <thead class="table-light">
                        <tr>
                            <th class="px-4 py-3" style="min-width: 280px;">Description</th>
                            <th class="px-4 py-3" style="min-width: 180px;">Département</th>
                            <th class="px-4 py-3" style="width: 150px;">Montant</th>
                            <th class="px-4 py-3" style="width: 150px;">Période</th>
                            <th class="px-4 py-3" style="width: 140px;">Statut</th>
                            <th class="px-4 py-3 text-center" style="width: 180px;">Actions</th>
                        </tr>
                    </thead>
                    <tbody>
                        <?php if (!empty($budgets)): ?>
                            <?php foreach ($budgets as $budget): ?>
                                <tr>
                                    <td class="px-4 py-3 fw-medium text-primary">
                                        <?= $budget['description'] ?>
                                    </td>
                                    <td class="px-4 py-3">
                                        <span class="d-inline-block text-truncate" style="max-width: 180px;" 
                                              title="<?= $departements[$budget['id_departement']] ?? 'Dép. #' . $budget['id_departement'] ?>">
                                            <?= $departements[$budget['id_departement']] ?? 'Dép. #' . $budget['id_departement'] ?>
                                        </span>
                                    </td>
                                    <td class="px-4 py-3 fw-semibold">
                                        <?= number_format($budget['montant'], 2, ',', ' ') ?> €
                                    </td>
                                    <td class="px-4 py-3">
                                        <?= date('F', mktime(0, 0, 0, $budget['mois'], 1)) ?> <?= $budget['annee'] ?>
                                    </td>
                                    <td class="px-4 py-3">
                                        <span class="badge rounded-pill px-3 py-2 <?= $budget['status'] === 'En attente' ? 'bg-warning text-dark' : 'bg-success' ?>">
                                            <i class="fas fa-<?= $budget['status'] === 'En attente' ? 'clock' : 'check-circle' ?> me-1"></i>
                                            <?= $budget['status'] ?>
                                        </span>
                                    </td>
                                    <td class="px-4 py-3">
                                        <div class="d-flex justify-content-center gap-2">
                                            <?php if ($budget['status'] === 'En attente'): ?>
                                                <a href="<?= site_url('admin/validerBudgetCRM/' . $budget['id']) ?>" 
                                                   class="btn btn-success btn-sm" 
                                                   data-bs-toggle="tooltip"
                                                   title="Valider ce budget">
                                                    <i class="fas fa-check"></i>
                                                </a>
                                            <?php endif; ?>
                                        </div>
                                    </td>
                                </tr>
                            <?php endforeach; ?>
                        <?php else: ?>
                            <tr>
                                <td colspan="6" class="text-center py-5">
                                    <div class="text-muted">
                                        <i class="fas fa-folder-open fa-3x mb-3"></i>
                                        <h5>Aucun budget CRM trouvé</h5>
                                        <p class="mb-0">Ajoutez un nouveau budget pour commencer</p>
                                    </div>
                                </td>
                            </tr>
                        <?php endif; ?>
                    </tbody>
                </table>
            </div>
        </div>
    </div>

    <!-- Statistiques -->
    <div class="row mt-4 g-3">
        <div class="col-md-4">
            <div class="card shadow-sm h-100">
                <div class="card-body">
                    <h5 class="card-title d-flex align-items-center">
                        <i class="fas fa-chart-line text-success me-2"></i>
                        Budget total
                    </h5>
                    <h3 class="fw-bold mt-3">
                        <?= number_format($totalBudget ?? 0, 2, ',', ' ') ?> €
                    </h3>
                    <p class="text-muted mb-0">Montant total des budgets</p>
                </div>
            </div>
        </div>

        <div class="col-md-4">
            <div class="card shadow-sm h-100">
                <div class="card-body">
                    <h5 class="card-title d-flex align-items-center">
                        <i class="fas fa-check-circle text-primary me-2"></i>
                        Budgets validés
                    </h5>
                    <h3 class="fw-bold mt-3"><?= $validBudgets ?? 0 ?></h3>
                    <p class="text-muted mb-0">Nombre de budgets validés</p>
                </div>
            </div>
        </div>

        <div class="col-md-4">
            <div class="card shadow-sm h-100">
                <div class="card-body">
                    <h5 class="card-title d-flex align-items-center">
                        <i class="fas fa-clock text-warning me-2"></i>
                        Budgets en attente
                    </h5>
                    <h3 class="fw-bold mt-3"><?= $pendingBudgets ?? 0 ?></h3>
                    <p class="text-muted mb-0">Nombre de budgets en attente</p>
                </div>
            </div>
        </div>
    </div>


<script>
    document.addEventListener('DOMContentLoaded', function() {
        // Activation des tooltips
        var tooltipTriggerList = [].slice.call(document.querySelectorAll('[data-bs-toggle="tooltip"]'));
        tooltipTriggerList.map(function (tooltipTriggerEl) {
            return new bootstrap.Tooltip(tooltipTriggerEl);
        });

        // Fonction de filtrage
        function filterTable() {
            var searchInput = document.getElementById('searchBudgets').value.toLowerCase();
            var statusFilter = document.getElementById('filterStatus').value;
            var rows = document.querySelectorAll('tbody tr');
            
            rows.forEach(function(row) {
                var rowText = row.textContent.toLowerCase();
                var statusCell = row.querySelector('td:nth-child(5)').textContent.trim();
                
                var matchesSearch = rowText.includes(searchInput);
                var matchesStatus = statusFilter === '' || statusCell.includes(statusFilter);
                
                row.style.display = (matchesSearch && matchesStatus) ? '' : 'none';
            });
        }

        // Event listeners
        document.getElementById('searchBudgets').addEventListener('keyup', filterTable);
        document.getElementById('filterStatus').addEventListener('change', filterTable);
    });
</script>