<?= $this->extend('layout/main') ?>

<?= $this->section('content') ?>
<div class="container-fluid">
    <div class="d-flex justify-content-between align-items-center mb-4">
        <h2>Détails de la Prévision</h2>
        <a href="<?= site_url('vue-annuel') ?>" class="btn btn-secondary">Retour</a>
    </div>

    <div class="card mb-4">
        <div class="card-header bg-primary text-white">Informations Générales</div>
        <div class="card-body">
            <div class="row">
                <div class="col-md-3">
                    <strong>Titre:</strong> <?= esc($prevision['titre']) ?>
                </div>
                <div class="col-md-3">
                    <strong>Département:</strong> <?= esc($prevision['nom_departement']) ?>
                </div>
                <div class="col-md-3">
                    <strong>Mois:</strong> <?= date('F', mktime(0, 0, 0, $prevision['mois'], 10)) ?>
                </div>
                <div class="col-md-3">
                    <strong>Année:</strong> <?= $prevision['annee'] ?>
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
                <tfoot>
                    <tr>
                        <th colspan="2" class="text-end">Total:</th>
                        <th class="text-end"><?= number_format(array_sum(array_column($details, 'montant')), 2) ?> €</th>
                    </tr>
                </tfoot>
            </table>
        </div>
    </div>
</div>
<?= $this->endSection() ?>
