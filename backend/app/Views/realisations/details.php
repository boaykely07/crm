<?= $this->extend('layout/main') ?>

<?= $this->section('content') ?>
<div class="container-fluid">
    <div class="d-flex justify-content-between align-items-center mb-4">
        <h2>Détails de la Réalisation</h2>
        <a href="<?= site_url('vue-annuel') ?>" class="btn btn-secondary">Retour</a>
    </div>

    <div class="card mb-4">
        <div class="card-header bg-success text-white">Informations Générales</div>
        <div class="card-body">
            <div class="row">
                <div class="col-md-3">
                    <strong>Titre:</strong> <?= esc($realisation['titre']) ?>
                </div>
                <div class="col-md-3">
                    <strong>Département:</strong> <?= esc($realisation['nom_departement']) ?>
                </div>
                <div class="col-md-3">
                    <strong>Mois:</strong> <?= date('F', mktime(0, 0, 0, $realisation['mois'], 10)) ?>
                </div>
                <div class="col-md-3">
                    <strong>Année:</strong> <?= $realisation['annee'] ?>
                </div>
            </div>
        </div>
    </div>

    <div class="card">
        <div class="card-header bg-info text-white">Détails</div>
        <div class="card-body">
            <table class="table table-striped">
                <thead>
                    <tr>
                        <th>Catégorie</th>
                        <th>Type</th>
                        <th class="text-end">Montant</th>
                    </tr>
                </thead>
                <tbody>
                    <?php foreach ($details as $detail): ?>
                    <tr>
                        <td><?= esc($detail['categorie_nom']) ?></td>
                        <td><?= ucfirst($detail['type']) ?></td>
                        <td class="text-end"><?= number_format($detail['montant'], 2) ?> €</td>
                    </tr>
                    <?php endforeach; ?>
                </tbody>
            </table>
        </div>
    </div>
</div>
<?= $this->endSection() ?>
