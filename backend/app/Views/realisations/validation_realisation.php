<?= $this->extend('layout/main') ?>

<?= $this->section('content') ?>
<div class="container-fluid mt-4">
    <div class="card">
        <div class="card-header bg-primary text-white">
            <h3 class="card-title mb-0">Validation de la réalisation - <?= $prevision['titre'] ?></h3>
        </div>
        <div class="card-body">
            <!-- Solde Final Prévu -->
            <div class="alert alert-info mb-4">
                <h5 class="alert-heading">Solde Final Prévu</h5>
                <p class="mb-0">
                    <?php
                    $soldeInitial = floatval($prevision['solde_depart']);
                    $totalGains = is_array($gains) ? array_reduce($gains, fn($sum, $item) => $sum + floatval($item['montant']), 0) : 0;
                    $totalDepenses = is_array($depenses) ? array_reduce($depenses, fn($sum, $item) => $sum + floatval($item['montant']), 0) : 0;
                    $soldeFinal = $soldeInitial + $totalGains - $totalDepenses;
                    ?>
                    Solde final = <?= number_format($soldeFinal, 2, ',', ' ') ?> €
                    <br>
                    <small>(Solde initial: <?= number_format($soldeInitial, 2, ',', ' ') ?> € + Gains: <?= number_format($totalGains, 2, ',', ' ') ?> € - Dépenses: <?= number_format($totalDepenses, 2, ',', ' ') ?> €)</small>
                </p>
            </div>

            <form id="validationForm" method="POST" action="/realisations/save" onsubmit="return validateForm()">
                <input type="hidden" name="prevision_id" value="<?= $prevision['id'] ?>">
                
                <!-- Section Gains -->
                <div class="section-gains mb-5">
                    <h4 class="text-success mb-3">GAINS</h4>
                    <?php if (empty($gains)): ?>
                        <div class="alert alert-info">Aucun gain prévu</div>
                    <?php else: ?>
                        <div class="table-responsive">
                            <table class="table table-bordered">
                                <thead>
                                    <tr>
                                        <th>Date prévue</th>
                                        <th>Titre</th>
                                        <th>Montant prévu</th>
                                        <th>Montant réalisé</th>
                                    </tr>
                                </thead>
                                <tbody>
                                    <?php foreach ($gains ?? [] as $gain): ?>
                                    <tr>
                                        <td><?= date('d/m/Y', strtotime($gain['created_at'])) ?></td>
                                        <td><?= $gain['categorie_nom'] ?></td>
                                        <td class="text-end"><?= number_format($gain['montant'], 2, ',', ' ') ?> €</td>
                                        <td>
                                            <input type="number" 
                                                   step="0.01" 
                                                   class="form-control text-end" 
                                                   name="gains[<?= $gain['id'] ?>]" 
                                                   value="<?= $gain['montant_realise'] ?? $gain['montant'] ?>"
                                                   data-montant-prevu="<?= $gain['montant'] ?>"
                                                   required>
                                        </td>
                                    </tr>
                                    <?php endforeach; ?>
                                </tbody>
                            </table>
                        </div>
                    <?php endif; ?>
                </div>

                <!-- Section Dépenses -->
                <div class="section-depenses">
                    <h4 class="text-danger mb-3">DÉPENSES</h4>
                    <?php if (empty($depenses)): ?>
                        <div class="alert alert-info">Aucune dépense prévue</div>
                    <?php else: ?>
                        <div class="table-responsive">
                            <table class="table table-bordered">
                                <thead>
                                    <tr>
                                        <th>Date prévue</th>
                                        <th>Titre</th>
                                        <th>Montant prévu</th>
                                        <th>Montant réalisé</th>
                                    </tr>
                                </thead>
                                <tbody>
                                    <?php foreach ($depenses ?? [] as $depense): ?>
                                    <tr>
                                        <td><?= date('d/m/Y', strtotime($depense['created_at'])) ?></td>
                                        <td><?= $depense['categorie_nom'] ?></td>
                                        <td class="text-end"><?= number_format($depense['montant'], 2, ',', ' ') ?> €</td>
                                        <td>
                                            <input type="number" 
                                                   step="0.01" 
                                                   class="form-control text-end" 
                                                   name="depenses[<?= $depense['id'] ?>]" 
                                                   value="<?= $depense['montant_realise'] ?? $depense['montant'] ?>"
                                                   data-montant-prevu="<?= $depense['montant'] ?>"
                                                   required>
                                        </td>
                                    </tr>
                                    <?php endforeach; ?>
                                </tbody>
                            </table>
                        </div>
                    <?php endif; ?>
                </div>

                <div class="d-flex justify-content-end mt-4">
                    <a href="/vue-annuel" class="btn btn-secondary me-2">Annuler</a>
                    <button type="submit" class="btn btn-primary">Valider la réalisation</button>
                </div>
            </form>
        </div>
    </div>
</div>
<?= $this->endSection() ?>

<?= $this->section('scripts') ?>
<script>
function validateForm() {
    // Vérifier les gains
    const gainsInputs = document.querySelectorAll('input[name^="gains"]');
    for (let input of gainsInputs) {
        const montantRealise = parseFloat(input.value);
        const montantPrevu = parseFloat(input.getAttribute('data-montant-prevu'));
        
        if (montantRealise > montantPrevu) {
            alert('Erreur : Le montant réalisé ne peut pas être supérieur au montant prévu pour les gains');
            input.focus();
            return false;
        }
    }

    // Vérifier les dépenses
    const depensesInputs = document.querySelectorAll('input[name^="depenses"]');
    for (let input of depensesInputs) {
        const montantRealise = parseFloat(input.value);
        const montantPrevu = parseFloat(input.getAttribute('data-montant-prevu'));
        
        if (montantRealise > montantPrevu) {
            alert('Erreur : Le montant réalisé ne peut pas être supérieur au montant prévu pour les dépenses');
            input.focus();
            return false;
        }
    }
    
    return true;
}

// Fonction pour valider les entrées en temps réel
function validateInput(input, montantPrevu) {
    const montantRealise = parseFloat(input.value);
    if (montantRealise > montantPrevu) {
        input.classList.add('is-invalid');
        input.nextElementSibling.textContent = 'Le montant ne peut pas dépasser ' + montantPrevu;
    } else {
        input.classList.remove('is-invalid');
        input.nextElementSibling.textContent = '';
    }
}
</script>
<?= $this->endSection() ?>
<!-- dil -->