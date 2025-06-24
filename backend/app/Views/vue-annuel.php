<?= $this->extend('layout/main') ?>

<?= $this->section('styles') ?>
<style>
    .page-header {
        background: var(--primary);
        padding: 2rem;
        border-radius: 12px;
        margin-bottom: 2rem;
        box-shadow: 0 4px 6px rgba(67, 97, 238, 0.15);
    }

    .filter-card {
        background: white;
        border: none;
        border-radius: 12px;
        box-shadow: 0 2px 4px rgba(67, 97, 238, 0.1);
        margin-bottom: 2rem;
    }

    .filter-card .card-body {
        padding: 1.5rem;
    }

    .form-label {
        font-weight: 500;
        color: #555;
    }

    .form-select, .form-control {
        padding: 0.75rem 1rem;
        border-radius: 8px;
        border: 1px solid #e0e0e0;
        transition: all 0.2s ease;
    }

    .form-select:focus, .form-control:focus {
        border-color: var(--primary);
        box-shadow: 0 0 0 3px rgba(67, 97, 238, 0.15);
    }

    .data-card {
        background: white;
        border: none;
        border-radius: 12px;
        box-shadow: 0 2px 4px rgba(67, 97, 238, 0.1);
        height: 100%;
    }

    .data-card .card-header {
        padding: 1.25rem 1.5rem;
        border-bottom: 1px solid rgba(0,0,0,0.05);
        border-radius: 12px 12px 0 0 !important;
    }

    .table {
        margin: 0;
    }

    .table th {
        font-weight: 600;
        color: #555;
        background: #f8f9fa;
        padding: 1rem;
    }

    .table td {
        padding: 1rem;
        vertical-align: middle;
    }

    .montant-cell {
        font-family: 'Monaco', monospace;
        font-weight: 500;
    }

    .btn-action {
        padding: 0.6rem 1rem;
        border-radius: 8px;
        font-size: 0.85rem;
        margin: 0 0.25rem;
        font-weight: 500;
        display: inline-flex;
        align-items: center;
        gap: 0.5rem;
        transition: all 0.2s ease;
    }

    .btn-info.btn-action {
        background: #60A5FA;
        border-color: #60A5FA;
        color: white;
    }

    .btn-info.btn-action:hover {
        background: #3B82F6;
        border-color: #3B82F6;
        transform: translateY(-2px);
        box-shadow: 0 4px 6px rgba(59, 130, 246, 0.25);
    }

    .btn-success.btn-action {
        background: var(--success);
        border-color: var(--success);
        color: white;
    }

    .btn-success.btn-action:hover {
        background: #39b4da;
        transform: translateY(-2px);
        box-shadow: 0 4px 6px rgba(76, 201, 240, 0.25);
    }

    .action-cell {
        white-space: nowrap;
        min-width: 160px;
    }

    .btn-action i {
        font-size: 0.9rem;
    }

    .btn-success {
        background: var(--success);
        border-color: var(--success);
    }

    .btn-success:hover {
        background: #39b4da;
        border-color: #39b4da;
        box-shadow: 0 4px 6px rgba(76, 201, 240, 0.25);
    }

    .header-badge {
        padding: 0.5rem 1.25rem;
        border-radius: 30px;
        font-size: 0.8rem;
        font-weight: 600;
        letter-spacing: 0.5px;
        text-transform: uppercase;
        display: inline-flex;
        align-items: center;
        gap: 0.5rem;
    }

    .header-badge.badge-validee {
        background-color: rgba(255, 255, 255, 0.2);
        color: white;
    }

    .header-badge.badge-attente {
        background-color: rgba(255, 255, 255, 0.2);
        color: white;
    }

    .header-badge i {
        font-size: 0.9rem;
    }

    .card-header .header-title {
        display: flex;
        align-items: center;
        gap: 0.75rem;
    }

    .card-header {
        display: flex;
        justify-content: space-between;
        align-items: center;
    }

    /* Styles pour les badges de statut dans les tableaux */
    .status-cell {
        white-space: nowrap;
    }

    .status-badge {
        padding: 0.4rem 0.8rem;
        border-radius: 6px;
        font-size: 0.75rem;
        font-weight: 600;
        letter-spacing: 0.5px;
        text-transform: uppercase;
        display: inline-flex;
        align-items: center;
        gap: 0.4rem;
    }

    .status-validee {
        background-color: rgba(76, 201, 240, 0.15);
        color: var(--success);
    }

    .status-attente {
        background-color: rgba(255, 193, 7, 0.15);
        color: #FFC107;
    }

    .status-termine {
        background-color: rgba(67, 97, 238, 0.15);
        color: var(--primary);
    }
    
    .status-en-cours {
        background-color: rgba(108, 117, 125, 0.15);
        color: var(--gray-600);
    }
</style>
<?= $this->endSection() ?>

<?= $this->section('content') ?>
    <div class="container-fluid mt-4">
        <!-- En-tête de page -->
        <div class="page-header text-white">
            <h2 class="mb-3 fw-bold">Vue Annuelle des Prévisions et Réalisations</h2>
            <p class="mb-0 opacity-75">Suivez vos prévisions et réalisations financières</p>
        </div>

        
        <!-- Filtres -->
        <div class="card filter-card mb-4">
            <div class="card-body">
                <form id="filterForm" class="row g-3 align-items-end">
                    <div class="col-md-4">
                        <label class="form-label text-muted fw-bold">Année</label>
                        <select class="form-select" id="annee" name="annee">
                            <?php for($i = 2020; $i <= date('Y') + 1; $i++): ?>
                                <option value="<?= $i ?>" <?= $i == date('Y') ? 'selected' : '' ?>><?= $i ?></option>
                            <?php endfor; ?>
                        </select>
                    </div>
                    <div class="col-md-4">
                        <label class="form-label text-muted fw-bold">Mois</label>
                        <select class="form-select" id="mois" name="mois">
                            <option value="">Tous les mois</option>
                            <?php for($i = 1; $i <= 12; $i++): ?>
                                <option value="<?= $i ?>"><?= date('F', mktime(0, 0, 0, $i, 1)) ?></option>
                            <?php endfor; ?>
                        </select>
                    </div>
                    <div class="col-md-4">
                        <button type="submit" class="btn btn-primary w-100 d-flex align-items-center justify-content-center">
                            <i class="fas fa-filter me-2"></i>
                            Filtrer
                        </button>
                    </div>
                </form>
                <!-- Ajouter le bouton d'export après le formulaire -->
                <div class="mt-3">
                    <button onclick="exportPDF()" class="btn btn-success d-flex align-items-center justify-content-center gap-2">
                        <i class="fas fa-file-pdf"></i>
                        Exporter en PDF
                    </button>
                </div>
            </div>
        </div>

        <!-- Tableaux des résultats -->
        <div class="row g-4">
            <!-- Prévisions -->
            <div class="col-md-6">
                <div class="card data-card h-100">
                    <div class="card-header bg-primary text-white">
                        <span class="header-title">
                            <i class="fas fa-chart-line"></i>
                            Prévisions
                        </span>
                    </div>
                    <div class="card-body">
                        <div id="previsionsData" class="table-responsive">
                            <table class="table">
                                <thead>
                                    <tr>
                                        <th>Mois</th>
                                        <th>Titre</th>
                                        <th>Solde départ</th>
                                        <th>Total Dépenses</th>
                                        <th>Total Revenus</th>
                                        <th>Solde Final</th>
                                        <th>Statut</th>
                                        <th>Actions</th>
                                    </tr>
                                </thead>
                                <tbody id="previsionsBody">
                                    <!-- Les données seront chargées ici via JavaScript -->
                                </tbody>
                            </table>
                        </div>
                    </div>
                </div>
            </div>

            <!-- Réalisations -->
            <div class="col-md-6">
                <div class="card data-card h-100">
                    <div class="card-header bg-success text-white">
                        <span class="header-title">
                            <i class="fas fa-check-circle"></i>
                            Réalisations
                        </span>
                    </div>
                    <div class="card-body">
                        <div id="realisationsData" class="table-responsive">
                            <table class="table">
                                <thead>
                                    <tr>
                                        <th>Mois</th>
                                        <th>Titre</th>
                                        <th>Total Dépenses</th>
                                        <th>Total Revenus</th>
                                        <th>Solde Final</th>
                                        <th>Statut</th>
                                        <th>Actions</th>
                                    </tr>
                                </thead>
                                <tbody id="realisationsBody">
                                    <!-- Les données seront chargées ici via JavaScript -->
                                </tbody>
                            </table>
                        </div>
                    </div>
                </div>
            </div>
        </div>
    </div>
<?= $this->endSection() ?>

<?= $this->section('scripts') ?>
<script src="https://kit.fontawesome.com/your-kit-code.js" crossorigin="anonymous"></script>
<script>
    document.getElementById('filterForm').addEventListener('submit', function(e) {
        e.preventDefault();
        loadData();
    });

    function loadData() {
        const annee = document.getElementById('annee').value;
        let mois = document.getElementById('mois').value;
        const departement = <?= session()->get('utilisateur')['id_departement'] ?>;

        // Si aucun mois n'est sélectionné, passer 'all' comme valeur
        if (!mois) {
            mois = 'all';
        }

        // Charger les prévisions
        fetch(`/api/previsions?departement_id=${departement}&annee=${annee}&mois=${mois}`)
            .then(response => response.json())
            .then(data => {
                if (data.status === 'success') {
                    console.log('Prévisions reçues:', data.data); // Pour le débogage
                    updatePrevisionsTable(data.data);
                }
            })
            .catch(error => console.error('Erreur:', error));

        // Charger les réalisations
        fetch(`/api/realisations?departement_id=${departement}&annee=${annee}&mois=${mois}`)
            .then(response => response.json())
            .then(data => {
                if (data.status === 'success') {
                    console.log('Réalisations reçues:', data.data); // Pour le débogage
                    updateRealisationsTable(data.data);
                }
            })
            .catch(error => console.error('Erreur:', error));
    }

    function updatePrevisionsTable(previsions) {
        const tbody = document.getElementById('previsionsBody');
        tbody.innerHTML = '';
        
        if (previsions.length === 0) {
            tbody.innerHTML = '<tr><td colspan="8" class="text-center">Aucune prévision trouvée</td></tr>';
            return;
        }

        previsions.forEach(prev => {
            const nomMois = new Date(prev.annee, prev.mois - 1).toLocaleString('fr-FR', { month: 'long' });
            
            // Calcul des totaux basés sur le type de catégorie
            const totalDepenses = prev.details_categories
                ? prev.details_categories
                    .filter(d => d.type === 'depense')
                    .reduce((sum, d) => sum + parseFloat(d.montant), 0)
                : 0;
            
            const totalRevenus = prev.details_categories
                ? prev.details_categories
                    .filter(d => d.type === 'gain')
                    .reduce((sum, d) => sum + parseFloat(d.montant), 0)
                : 0;

            const soldeFinal = parseFloat(prev.solde_depart) + totalRevenus - totalDepenses;

            let actionButtons = `
                <td class="action-cell">
                    <button class="btn btn-info btn-action" onclick="voirDetails('previsions', ${prev.id})">
                        <i class="fas fa-search"></i>
                        Détails
                    </button>`;

            // Ajouter le bouton "Commencer réalisation" uniquement si la prévision est validée 
            // et qu'il n'y a pas encore de réalisation
            if (prev.statut === 'validee' && !prev.has_realisation) {
                actionButtons += `
                    <button class="btn btn-success btn-action" onclick="commencerRealisation(${prev.id})">
                        <i class="fas fa-play"></i>
                        Réaliser
                    </button>`;
            }

            actionButtons += '</td>';

            // Définir le badge de statut
            const statusBadge = `
                <td class="status-cell">
                    <span class="status-badge status-${prev.statut || 'attente'}">
                        <i class="fas fa-${prev.statut === 'validee' ? 'check-circle' : 'clock'}"></i>
                        ${prev.statut ? prev.statut.replace('_', ' ').toUpperCase() : 'EN ATTENTE'}
                    </span>
                </td>`;

            const row = document.createElement('tr');
            row.innerHTML = `
                <td>${nomMois}</td>
                <td>${prev.titre}</td>
                <td class="montant-cell">${formatMontant(prev.solde_depart)}</td>
                <td class="text-danger montant-cell">-${formatMontant(totalDepenses)}</td>
                <td class="text-success montant-cell">+${formatMontant(totalRevenus)}</td>
                <td class="${soldeFinal >= 0 ? 'text-success' : 'text-danger'} montant-cell">${formatMontant(soldeFinal)}</td>
                ${statusBadge}
                ${actionButtons}
            `;
            tbody.appendChild(row);
        });
    }

    function updateRealisationsTable(realisations) {
        const tbody = document.getElementById('realisationsBody');
        tbody.innerHTML = '';
        
        if (realisations.length === 0) {
            tbody.innerHTML = '<tr><td colspan="7" class="text-center">Aucune réalisation trouvée</td></tr>';
            return;
        }

        realisations.forEach(real => {
            const nomMois = new Date(real.annee, real.mois - 1).toLocaleString('fr-FR', { month: 'long' });
            const totalDepenses = real.details ? real.details.filter(d => d.type === 'depense').reduce((sum, d) => sum + parseFloat(d.montant), 0) : 0;
            const totalRevenus = real.details ? real.details.filter(d => d.type === 'gain').reduce((sum, d) => sum + parseFloat(d.montant), 0) : 0;
            const soldeFinal = totalRevenus - totalDepenses;

            // Définir le badge de statut
            const statusBadge = `
                <td class="status-cell">
                    <span class="status-badge status-${real.statut || 'en-cours'}">
                        <i class="fas fa-${real.statut === 'termine' ? 'check-double' : 'clock'}"></i>
                        ${real.statut ? real.statut.replace('_', ' ').toUpperCase() : 'EN COURS'}
                    </span>
                </td>`;

            const row = document.createElement('tr');
            row.innerHTML = `
                <td>${nomMois}</td>
                <td>${real.titre}</td>
                <td class="text-danger montant-cell">-${formatMontant(totalDepenses)}</td>
                <td class="text-success montant-cell">+${formatMontant(totalRevenus)}</td>
                <td class="${soldeFinal >= 0 ? 'text-success' : 'text-danger'} montant-cell">${formatMontant(soldeFinal)}</td>
                ${statusBadge}
                <td class="action-cell">
                    <button class="btn btn-info btn-action" onclick="voirDetails('realisations', ${real.id})">
                        <i class="fas fa-search"></i>
                        Détails
                    </button>
                </td>
            `;
            tbody.appendChild(row);
        });
    }

    function formatMontant(montant) {
        return new Intl.NumberFormat('fr-FR', { 
            style: 'currency', 
            currency: 'EUR'
        }).format(montant);
    }

    function voirDetails(type, id) {
        window.location.href = `/${type}/details/${id}`;
    }

    // Ajouter la fonction pour commencer une réalisation
    function commencerRealisation(previsionId) {
        window.location.href = `/realisations/validation/${previsionId}`;
    }

    // Ajouter la fonction d'export PDF
    function exportPDF() {
        const annee = document.getElementById('annee').value;
        let mois = document.getElementById('mois').value;
        const departement = <?= session()->get('utilisateur')['id_departement'] ?>;

        // Construire l'URL avec les paramètres
        let url = `/export-pdf?departement_id=${departement}&annee=${annee}`;
        if (mois) {
            url += `&mois=${mois}`;
        }

        // Rediriger vers l'URL d'export
        window.open(url, '_blank');
    }

    // Charger les données au chargement de la page
    document.addEventListener('DOMContentLoaded', function() {
        loadData();
    });
</script>
<?= $this->endSection() ?>
