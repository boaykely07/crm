<?= $this->extend('layout/main') ?>

<?= $this->section('content') ?>
<div class="container-fluid mt-4">
    <div class="card">
        <div class="card-header bg-primary text-white">
            <h3 class="card-title mb-0">Modifier la prévision</h3>
        </div>
        <div class="card-body">
            <form action="<?= site_url('previsions/update/' . $prevision['id']) ?>" method="POST" id="modificationForm">
                <?= csrf_field() ?>
                
                <div class="row mb-3">
                    <div class="col-md-6">
                        <label class="form-label">Titre</label>
                        <input type="text" name="titre" class="form-control" 
                               value="<?= $prevision['titre'] ?>" required>
                    </div>
                    <div class="col-md-6">
                        <label class="form-label">Solde départ</label>
                        <input type="number" step="0.01" name="solde_depart" class="form-control" 
                               value="<?= $prevision['solde_depart'] ?>" required>
                    </div>
                </div>

                <div class="row mb-3">
                    <div class="col-md-6">
                        <label class="form-label">Mois</label>
                        <select name="mois" class="form-select" required>
                            <?php for($i = 1; $i <= 12; $i++): ?>
                                <option value="<?= $i ?>" 
                                    <?= set_select('mois', $i, ($prevision['mois'] == $i)) ?>>
                                    <?= date('F', mktime(0, 0, 0, $i, 1)) ?>
                                </option>
                            <?php endfor; ?>
                        </select>
                    </div>
                    <div class="col-md-6">
                        <label class="form-label">Année</label>
                        <input type="number" name="annee" class="form-control" 
                               value="<?= old('annee', $prevision['annee']) ?>" required>
                    </div>
                </div>

                <div id="detailsContainer">
                    <!-- Les détails seront ajoutés ici -->
                </div>

                <input type="hidden" name="details" id="detailsInput">

                <div class="d-flex justify-content-end gap-2">
                    <a href="<?= site_url('admin/listePrevisions') ?>" class="btn btn-secondary">Annuler</a>
                    <button type="submit" class="btn btn-primary">Enregistrer</button>
                </div>
            </form>
        </div>
    </div>
</div>
<?= $this->endSection() ?>

<?= $this->section('scripts') ?>
<script>
const details = <?= json_encode($details ?? []) ?>;
// ...existing script code for handling details...

document.getElementById('modificationForm').addEventListener('submit', function(e) {
    e.preventDefault();
    const detailsData = [];
    document.querySelectorAll('.detail-item').forEach(item => {
        const categorie = item.querySelector('.categorie').value;
        const montant = item.querySelector('.montant').value;
        if (categorie && montant) {
            detailsData.push({ categorie, montant });
        }
    });
    document.getElementById('detailsInput').value = JSON.stringify(detailsData);
    this.submit();
});
</script>
<?= $this->endSection() ?>
