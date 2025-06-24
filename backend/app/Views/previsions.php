<?= $this->extend('layout/main') ?>

<?= $this->section('styles') ?>
<style>
    .card {
        border: none;
        border-radius: 12px;
        box-shadow: 0 2px 4px rgba(67, 97, 238, 0.1);
    }

    .card-header {
        background: var(--primary);
        color: white;
        border-radius: 12px 12px 0 0 !important;
        padding: 1rem 1.5rem;
        font-weight: 600;
    }

    .card-body {
        padding: 2rem;
    }

    .form-label {
        font-weight: 500;
        color: #555;
        margin-bottom: 0.5rem;
    }

    .form-control, .form-select {
        padding: 0.75rem 1rem;
        border-radius: 8px;
        border: 1px solid #e0e0e0;
        transition: all 0.2s ease;
    }

    .form-control:focus, .form-select:focus {
        border-color: var(--primary);
        box-shadow: 0 0 0 3px rgba(67, 97, 238, 0.15);
    }

    .detail-item {
        background: #f8f9fa;
        padding: 1rem;
        border-radius: 8px;
        margin-bottom: 1rem;
    }

    .btn-remove {
        padding: 0.5rem 1rem;
        border-radius: 6px;
    }

    .btn-outline-primary {
        color: var(--primary);
        border-color: var(--primary);
    }

    .btn-outline-primary:hover {
        background: var(--primary);
        color: white;
    }

    .alert {
        border-radius: 8px;
        padding: 1rem;
        margin-bottom: 1.5rem;
    }
</style>
<?= $this->endSection() ?>

<?= $this->section('content') ?>
    <div class="container-fluid">
        <?php if (session()->getFlashdata('error')): ?>
            <div class="alert alert-danger">
                <ul class="mb-0">
                    <?php if (is_array(session()->getFlashdata('error'))): ?>
                        <?php foreach (session()->getFlashdata('error') as $err): ?>
                            <li><?= esc($err) ?></li>
                        <?php endforeach; ?>
                    <?php else: ?>
                        <li><?= esc(session()->getFlashdata('error')) ?></li>
                    <?php endif; ?>
                </ul>
            </div>
        <?php endif; ?>

        <?php if (session()->getFlashdata('success')): ?>
            <div class="alert alert-success">
                <?= esc(session()->getFlashdata('success')) ?>
            </div>
        <?php endif; ?>

        <div class="card mb-4">
            <div class="card-header bg-primary text-white">
                <?= isset($prevision) ? 'Modifier Prévision' : 'Nouvelle Prévision' ?>
            </div>
            <div class="card-body">
                <form id="previsionForm" method="post" action="<?= isset($prevision) ? site_url('previsions/update/' . $prevision['id']) : site_url('previsions/save') ?>">
                    <?= csrf_field() ?>
                    <input type="hidden" name="id" value="<?= $prevision['id'] ?? '' ?>">
                    <div class="row mb-3">
                        <div class="col-md-6">
                            <label class="form-label" for="titre">Titre</label>
                            <input type="text" class="form-control" name="titre" id="titre" value="<?= old('titre', $prevision['titre'] ?? '') ?>" required>
                        </div>
                        <div class="col-md-6">
                            <label class="form-label" for="solde_depart">Solde de départ</label>
                            <input type="number" step="0.01" class="form-control" name="solde_depart" id="solde_depart" value="<?= old('solde_depart', $prevision['solde_depart'] ?? '') ?>" required>
                        </div>
                    </div>
                    <div class="row mb-3">
                        <div class="col-md-6">
                            <label class="form-label" for="mois">Mois</label>
                            <select class="form-select" name="mois" id="mois" required>
                                <option value="">-- Choisir un mois --</option>
                                <?php for ($i = 1; $i <= 12; $i++): ?>
                                    <option value="<?= $i ?>" <?= old('mois', $prevision['mois'] ?? '') == $i ? 'selected' : '' ?>>
                                        <?= date('F', mktime(0, 0, 0, $i, 10)) ?>
                                    </option>
                                <?php endfor; ?>
                            </select>
                        </div>
                        <div class="col-md-6">
                            <label class="form-label" for="annee">Année</label>
                            <input type="number" class="form-control" name="annee" id="annee" value="<?= old('annee', $prevision['annee'] ?? date('Y')) ?>" required>
                        </div>
                    </div>

                    <hr>
                    <h5>Détails de la prévision</h5>

                    <div id="detailsContainer">
                        <?php if (!empty($details)): ?>
                            <?php foreach ($details as $detail): ?>
                                <div class="row mb-2 detail-item">
                                    <div class="col-md-6">
                                        <label class="form-label">Catégorie</label>
                                        <select class="form-select categorie" required>
                                            <option value="">-- Choisir --</option>
                                            <?php foreach ($categories as $cat): ?>
                                                <option value="<?= $cat['id'] ?>" <?= $detail['id_categorie'] == $cat['id'] ? 'selected' : '' ?>>
                                                    <?= esc($cat['nom']) ?>
                                                </option>
                                            <?php endforeach; ?>
                                        </select>
                                    </div>
                                    <div class="col-md-4">
                                        <label class="form-label">Montant</label>
                                        <input type="number" step="0.01" class="form-control montant" value="<?= $detail['montant'] ?>" required>
                                    </div>
                                    <div class="col-md-2 d-flex align-items-end">
                                        <button type="button" class="btn btn-danger btn-remove">X</button>
                                    </div>
                                </div>
                            <?php endforeach; ?>
                        <?php else: ?>
                            <div class="row mb-2 detail-item">
                                <div class="col-md-6">
                                    <label class="form-label">Catégorie</label>
                                    <select class="form-select categorie" required>
                                        <option value="">-- Choisir --</option>
                                        <?php foreach ($categories as $cat): ?>
                                            <option value="<?= $cat['id'] ?>"><?= esc($cat['nom']) ?></option>
                                        <?php endforeach; ?>
                                    </select>
                                </div>
                                <div class="col-md-4">
                                    <label class="form-label">Montant</label>
                                    <input type="number" step="0.01" class="form-control montant" required>
                                </div>
                                <div class="col-md-2 d-flex align-items-end">
                                    <button type="button" class="btn btn-danger btn-remove">X</button>
                                </div>
                            </div>
                        <?php endif; ?>
                    </div>

                    <button type="button" id="addDetail" class="btn btn-outline-primary mb-3">+ Ajouter une catégorie</button>

                    <input type="hidden" name="details" id="details">

                    <div class="d-grid">
                        <button type="submit" class="btn btn-success">Enregistrer</button>
                    </div>
                </form>
            </div>
        </div>
    </div>
<?= $this->endSection() ?>

<?= $this->section('scripts') ?>
    <script>
        // Créer un template de ligne de détail une seule fois
        const detailTemplate = document.querySelector('.detail-item').cloneNode(true);

        document.getElementById('addDetail').addEventListener('click', function() {
            addNewDetailRow();
        });

        document.getElementById('detailsContainer').addEventListener('click', function (e) {
            if (e.target.classList.contains('btn-remove')) {
                const rows = document.querySelectorAll('.detail-item');
                if (rows.length > 1) {
                    e.target.closest('.detail-item').remove();
                }
            }
        });

        document.getElementById('previsionForm').addEventListener('submit', function (e) {
            e.preventDefault();
            const details = [];
            const rows = document.querySelectorAll('.detail-item');
            rows.forEach(row => {
                const categorie = row.querySelector('.categorie').value;
                const montant = row.querySelector('.montant').value;
                if (categorie && montant) {
                    details.push({ categorie, montant });
                }
            });
            document.getElementById('details').value = JSON.stringify(details);

            fetch(this.action, {
                method: 'POST',
                body: new FormData(this),
                headers: {
                    'X-Requested-With': 'XMLHttpRequest'
                }
            })
            .then(response => {
                if (!response.ok) {
                    throw new Error('Erreur réseau');
                }
                return response.json();
            })
            .then(data => {
                if (data.status === 'success') {
                    resetForm();
                } else {
                    throw new Error(data.message || 'Erreur inconnue');
                }
            })
            .catch(error => {
                console.error('Erreur:', error);
                alert('Erreur lors de la sauvegarde: ' + error.message);
            });
        });

        function resetForm() {
            const form = document.getElementById('previsionForm');
            const container = document.getElementById('detailsContainer');
            
            // Réinitialiser le formulaire
            form.reset();
            
            // Vider le conteneur de détails
            container.innerHTML = '';
            
            // Ajouter une nouvelle ligne de détail
            addNewDetailRow();

            // Message de succès
            showSuccessMessage('Prévision enregistrée avec succès');
        }

        function addNewDetailRow() {
            const container = document.getElementById('detailsContainer');
            const newRow = detailTemplate.cloneNode(true);
            
            // Réinitialiser les valeurs
            newRow.querySelector('.categorie').value = '';
            newRow.querySelector('.montant').value = '';
            
            container.appendChild(newRow);
        }

        function showSuccessMessage(message) {
            const alert = document.createElement('div');
            alert.className = 'alert alert-success alert-dismissible fade show mt-3';
            alert.innerHTML = `
                ${message}
                <button type="button" class="btn-close" data-bs-dismiss="alert"></button>
            `;
            
            document.querySelector('.card-body').insertBefore(alert, document.getElementById('previsionForm'));
            
            // Auto-hide après 3 secondes
            setTimeout(() => {
                alert.remove();
            }, 3000);
        }
    </script>
<?= $this->endSection() ?>

