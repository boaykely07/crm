<!DOCTYPE html>
<html>
<head>
    <meta charset="UTF-8">
    <style>
        body {
            font-family: 'DejaVu Sans', sans-serif;
            margin: 20px;
        }
        h1 {
            color: #0d6efd;
            text-align: center;
            margin-bottom: 30px;
        }
        .section {
            margin-bottom: 30px;
        }
        table {
            width: 100%;
            border-collapse: collapse;
            margin-bottom: 20px;
        }
        th, td {
            border: 1px solid #ddd;
            padding: 8px;
            text-align: left;
        }
        th {
            background-color: #f8f9fa;
        }
        .montant {
            text-align: right;
            font-family: 'DejaVu Sans Mono', monospace;
        }
        .text-danger { color: #dc3545; }
        .text-success { color: #198754; }
        .subtitle {
            color: #6c757d;
            font-size: 14px;
            margin-bottom: 15px;
        }
    </style>
</head>
<body>
    <h1>Rapport Financier <?= $annee ?></h1>
    <p class="subtitle">Période : <?= $mois ?></p>

    <!-- Section Prévisions -->
    <div class="section">
        <h2>Prévisions</h2>
        <table>
            <thead>
                <tr>
                    <th>Mois</th>
                    <th>Titre</th>
                    <th>Solde départ</th>
                    <th>Dépenses</th>
                    <th>Revenus</th>
                    <th>Solde Final</th>
                </tr>
            </thead>
            <tbody>
                <?php foreach ($previsions as $prev): 
                    $nomMois = date('F', mktime(0, 0, 0, $prev['mois'], 1));
                    $totalDepenses = array_reduce(
                        array_filter($prev['details_categories'] ?? [], fn($d) => $d['type'] === 'depense'),
                        fn($sum, $d) => $sum + floatval($d['montant']),
                        0
                    );
                    $totalRevenus = array_reduce(
                        array_filter($prev['details_categories'] ?? [], fn($d) => $d['type'] === 'gain'),
                        fn($sum, $d) => $sum + floatval($d['montant']),
                        0
                    );
                    $soldeFinal = floatval($prev['solde_depart']) + $totalRevenus - $totalDepenses;
                ?>
                <tr>
                    <td><?= $nomMois ?></td>
                    <td><?= $prev['titre'] ?></td>
                    <td class="montant"><?= number_format($prev['solde_depart'], 2, ',', ' ') ?> €</td>
                    <td class="montant text-danger">-<?= number_format($totalDepenses, 2, ',', ' ') ?> €</td>
                    <td class="montant text-success">+<?= number_format($totalRevenus, 2, ',', ' ') ?> €</td>
                    <td class="montant <?= $soldeFinal >= 0 ? 'text-success' : 'text-danger' ?>">
                        <?= number_format($soldeFinal, 2, ',', ' ') ?> €
                    </td>
                </tr>
                <?php endforeach; ?>
            </tbody>
        </table>
    </div>

    <!-- Section Réalisations -->
    <div class="section">
        <h2>Réalisations</h2>
        <table>
            <thead>
                <tr>
                    <th>Mois</th>
                    <th>Titre</th>
                    <th>Dépenses</th>
                    <th>Revenus</th>
                    <th>Solde Final</th>
                </tr>
            </thead>
            <tbody>
                <?php foreach ($realisations as $real): 
                    $nomMois = date('F', mktime(0, 0, 0, $real['mois'], 1));
                    $totalDepenses = array_reduce(
                        array_filter($real['details'] ?? [], fn($d) => $d['type'] === 'depense'),
                        fn($sum, $d) => $sum + floatval($d['montant']),
                        0
                    );
                    $totalRevenus = array_reduce(
                        array_filter($real['details'] ?? [], fn($d) => $d['type'] === 'gain'),
                        fn($sum, $d) => $sum + floatval($d['montant']),
                        0
                    );
                    $soldeFinal = $totalRevenus - $totalDepenses;
                ?>
                <tr>
                    <td><?= $nomMois ?></td>
                    <td><?= $real['titre'] ?></td>
                    <td class="montant text-danger">-<?= number_format($totalDepenses, 2, ',', ' ') ?> €</td>
                    <td class="montant text-success">+<?= number_format($totalRevenus, 2, ',', ' ') ?> €</td>
                    <td class="montant <?= $soldeFinal >= 0 ? 'text-success' : 'text-danger' ?>">
                        <?= number_format($soldeFinal, 2, ',', ' ') ?> €
                    </td>
                </tr>
                <?php endforeach; ?>
            </tbody>
        </table>
    </div>
</body>
</html>
