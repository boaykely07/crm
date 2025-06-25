<?php
// app/Views/admin/listeMessageClient.php
?>
<div class="container-fluid mt-4">
    <div class="row">
        <div class="col-12">
            <div class="card shadow-lg border-0 rounded-3">
                <div class="card-header bg-primary text-white rounded-top-3">
                    <h3 class="card-title mb-0"><i class="fas fa-envelope me-2"></i>Liste des Messages Clients</h3>
                </div>
                <div class="card-body">
                    <!-- Filtres -->
                    <div class="d-flex flex-wrap gap-3 mb-4">
                        <div class="input-group" style="max-width: 300px;">
                            <span class="input-group-text bg-primary text-white"><i class="fas fa-search"></i></span>
                            <input type="text" id="searchInput" class="form-control" placeholder="Rechercher par client ou message..." aria-label="Rechercher">
                        </div>
                        <select id="ticketStatusFilter" class="form-select" style="max-width: 200px;">
                            <option value="">Tous les statuts</option>
                            <option value="lié">Lié à un ticket</option>
                            <option value="non lié">Non lié</option>
                        </select>
                        <input type="date" id="dateDebutFilter" class="form-control" style="max-width: 200px;" placeholder="Date de début">
                        <input type="date" id="dateFinFilter" class="form-control" style="max-width: 200px;" placeholder="Date de fin">
                    </div>

                    <!-- Tableau des messages -->
                    <div class="table-responsive">
                        <table class="table table-hover align-middle mb-0">
                            <thead class="table-light">
                                <tr>
                                    <th class="px-4 py-3">ID</th>
                                    <th class="px-4 py-3">Client</th>
                                    <th class="px-4 py-3">Message</th>
                                    <th class="px-4 py-3">Date</th>
                                    <th class="px-4 py-3">Ticket lié</th>
                                    <th class="px-4 py-3">Action</th>
                                </tr>
                            </thead>
                            <tbody style="background-color: rgb(26, 8, 166); color: #fff;">
                                <?php if (!empty($messages)): ?>
                                    <?php foreach ($messages as $msg): ?>
                                        <tr class="table-row-hover">
                                            <td class="px-4 py-3"><?= esc($msg['id']) ?></td>
                                            <td class="px-4 py-3"><?= esc($msg['client_nom']) ?></td>
                                            <td class="px-4 py-3"><?= nl2br(esc($msg['message'])) ?></td>
                                            <td class="px-4 py-3"><?= date('d/m/Y H:i', strtotime($msg['date_message'])) ?></td>
                                            <td class="px-4 py-3">
                                                <?php if ($msg['id_ticket']): ?>
                                                    <a style ="color:#0000" href="<?= site_url('/admin/tickets') ?>#ticket<?= esc($msg['id_ticket'], 'attr') ?>" class="text-white text-decoration-underline">
                                                        Ticket #<?= esc($msg['id_ticket']) ?>
                                                    </a>
                                                <?php else: ?>
                                                    <span class="text-muted">-</span>
                                                <?php endif; ?>
                                            </td>
                                            <td class="px-4 py-3">
                                                <a href="<?= site_url('/admin/detailMessageClient/' . $msg['id']) ?>" class="btn btn-primary btn-sm">
                                                    <i class="fas fa-eye me-1"></i> Détails
                                                </a>
                                            </td>
                                        </tr>
                                    <?php endforeach; ?>
                                <?php else: ?>
                                    <tr>
                                        <td colspan="6" class="text-center py-5 text-muted">
                                            <i class="fas fa-envelope-open fa-3x mb-3"></i>
                                            <h5>Aucun message trouvé.</h5>
                                        </td>
                                    </tr>
                                <?php endif; ?>
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
    .btn-sm {
        font-size: 0.875rem;
        padding: 0.25rem 0.75rem;
    }
    @media (max-width: 768px) {
        .d-flex {
            flex-direction: column;
        }
        .input-group, .form-select, .form-control {
            max-width: 100% !important;
            margin-bottom: 0.5rem;
        }
    }
</style>

<!-- Script pour la filtration dynamique -->
<script>
    document.addEventListener('DOMContentLoaded', function() {
        const searchInput = document.getElementById('searchInput');
        const ticketStatusFilter = document.getElementById('ticketStatusFilter');
        const dateDebutFilter = document.getElementById('dateDebutFilter');
        const dateFinFilter = document.getElementById('dateFinFilter');
        const tableRows = document.querySelectorAll('tbody tr');

        function filterTable() {
            const searchValue = searchInput.value.toLowerCase();
            const ticketStatusValue = ticketStatusFilter.value.toLowerCase();
            const dateDebutValue = dateDebutFilter.value ? new Date(dateDebutFilter.value) : null;
            const dateFinValue = dateFinFilter.value ? new Date(dateFinFilter.value) : null;

            tableRows.forEach(row => {
                const client = row.cells[1].textContent.toLowerCase();
                const message = row.cells[2].textContent.toLowerCase();
                const dateText = row.cells[3].textContent;
                const ticketCell = row.cells[4].textContent.toLowerCase();
                const dateMessage = new Date(dateText.split('/').reverse().join('-')); // Convertir DD/MM/YYYY en YYYY-MM-DD

                const matchesSearch = client.includes(searchValue) || message.includes(searchValue);
                const matchesTicketStatus = !ticketStatusValue ||
                    (ticketStatusValue === 'lié' && ticketCell.includes('ticket')) ||
                    (ticketStatusValue === 'non lié' && ticketCell.includes('-'));
                const matchesDate = (!dateDebutValue || dateMessage >= dateDebutValue) &&
                                    (!dateFinValue || dateMessage <= dateFinValue);

                row.style.display = matchesSearch && matchesTicketStatus && matchesDate ? '' : 'none';
            });
        }

        searchInput.addEventListener('input', filterTable);
        ticketStatusFilter.addEventListener('change', filterTable);
        dateDebutFilter.addEventListener('change', filterTable);
        dateFinFilter.addEventListener('change', filterTable);
    });
</script>