<!DOCTYPE html>
<html lang="fr">
<head>
    <meta charset="UTF-8">
    <title>Réalisation | CRM</title>
    <meta name="viewport" content="width=device-width, initial-scale=1">
    <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.2/dist/css/bootstrap.min.css" rel="stylesheet">
</head>
<body class="bg-light">
    <div class="container mt-4">
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
                <?= isset($realisation) ? 'Modifier Réalisation' : 'Nouvelle Réalisation' ?>
            </div>
            <div class="card-body">
                <form id="realisationForm" method="post" action="<?= isset($realisation) ? site_url('realisations/update/' . $realisation['id']) : site_url('realisations/save') ?>">
                    <?= csrf_field() ?>
                    <input type="hidden" name="id" value="<?= $realisation['id'] ?? '' ?>">
                    <div class="row mb-3">
                        <div class="col-md-6">
                            <label class="form-label" for="titre">Titre</label>
                            <input type="text" class="form-control" name="titre" id="titre" value="<?= old('titre', $realisation['titre'] ?? '') ?>" required>
                        </div>
                        <div class="col-md-6">
                            <label class="form-label" for="montant">Montant</label>
                            <input type="number" step="0.01" class="form-control" name="montant" id="montant" value="<?= old('montant', $realisation['montant'] ?? '') ?>" required>
                        </div>
                    </div>
                    <div class="row mb-3">
                        <div class="col-md-6">
                            <label class="form-label" for="mois">Mois</label>
                            <select class="form-select" name="mois" id="mois" required>
                                <option value="">-- Choisir un mois --</option>
                                <?php for ($i = 1; $i <= 12; $i++): ?>
                                    <option value="<?= $i ?>" <?= old('mois', $realisation['mois'] ?? '') == $i ? 'selected' : '' ?>>
                                        <?= date('F', mktime(0, 0, 0, $i, 10)) ?>
                                    </option>
                                <?php endfor; ?>
                            </select>
                        </div>
                        <div class="col-md-6">
                            <label class="form-label" for="annee">Année</label>
                            <input type="number" class="form-control" name="annee" id="annee" value="<?= old('annee', $realisation['annee'] ?? date('Y')) ?>" required>
                        </div>
                    </div>

                    <hr>
                    <h5>Détails de la réalisation</h5>

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

    <!-- JS -->
    <script>
        document.getElementById('addDetail').addEventListener('click', function () {
            const container = document.getElementById('detailsContainer');
            const newRow = document.querySelector('.detail-item').cloneNode(true);
            newRow.querySelector('.categorie').value = '';
            newRow.querySelector('.montant').value = '';
            container.appendChild(newRow);
        });

        document.getElementById('detailsContainer').addEventListener('click', function (e) {
            if (e.target.classList.contains('btn-remove')) {
                const rows = document.querySelectorAll('.detail-item');
                if (rows.length > 1) {
                    e.target.closest('.detail-item').remove();
                }
            }
        });

        document.getElementById('realisationForm').addEventListener('submit', function (e) {
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
        });
    </script>
</body>
</html>
