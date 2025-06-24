<!DOCTYPE html>
<html lang="fr">
<head>
    <meta charset="UTF-8">
    <title>CRM | Statistiques</title>
    <meta name="viewport" content="width=device-width, initial-scale=1">
    <link rel="stylesheet" href="<?= base_url('assets/css/stats.css') ?>"> <!-- Ce fichier sera généré à partir du SCSS -->
    <script src="https://cdn.jsdelivr.net/npm/chart.js@4.4.0/dist/chart.umd.min.js"></script>
    <!-- Font Awesome pour les icônes -->
    <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/6.5.1/css/all.min.css">
</head>
<body>
    <!-- Sidebar -->
    <aside class="sidebar">
        <div class="logo">
            <img src="https://cdn-icons-png.flaticon.com/512/3135/3135715.png" alt="CRM Logo">
            <span>CRM</span>
        </div>
        <ul class="nav" style="height: 60%;">
            
        <li><a href="<?= site_url('crm') ?>"><i class="fas fa-home"></i><span>Accueil CRM</span></a></li>
            <li><a href="<?= site_url('crm/actions') ?>"><i class="fas fa-tasks"></i><span>Actions</span></a></li>
            <li><a href="<?= site_url('crm/stats') ?>" class="active"><i class="fas fa-chart-bar"></i><span>Statistiques</span></a></li>
        </ul>

        <a href="<?= site_url('vue-annuel') ?>" class="logout">
            <button type="submit"><i class="fas fa-sign-out-alt"></i><span>Quitter</span></button>
        </a>
    </aside>

    <!-- Main content -->
    <main class="main">
        <div class="header">
            <div>
                <h1>Statistiques CRM</h1>
                <p>Visualisez les performances de vos ventes, clients et stocks</p>
            </div>
            <div class="profile">
                <img src="https://via.placeholder.com/40" alt="Profile">
                <div class="info">
                    <div class="name">Jean Dupont</div>
                    <div class="role">Administrateur</div>
                </div>
            </div>
        </div>

        <!-- Cards de synthèse -->
        <section class="summary-cards mb-4">
            <div class="row g-3">
                <div class="col-md-6">
                    <div class="card stats-card">
                        <div class="card-body">
                            <div class="d-flex justify-content-between align-items-center">
                                <div>
                                    <h6 class="card-subtitle mb-2 text-muted">Chiffre d'affaires total</h6>
                                    <h2 class="card-title mb-0"><?= number_format($chiffre_affaires, 2, ',', ' ') ?> Ar</h2>
                                </div>
                                <div class="icon-box bg-primary bg-opacity-10">
                                    <i class="fas fa-money-bill-wave text-primary"></i>
                                </div>
                            </div>
                        </div>
                    </div>
                </div>
            </div>
        </section>

        <!-- Graphiques -->
        <section class="chart-section">
            <!-- Évolution des ventes par mois -->
            <div class="chart-card">
                <h5><i class="fas fa-chart-line"></i> Évolution des ventes par mois</h5>
                <p>Montant des ventes par mois (en Ariary)</p>
                <div class="chart-container">
                    <canvas id="ventesParMois"></canvas>
                </div>
            </div>

            <!-- Top 5 produits les plus vendus -->
            <div class="chart-card">
                <h5><i class="fas fa-award"></i> Top 5 produits les plus vendus</h5>
                <p>Quantités vendues par produit</p>
                <div class="chart-container">
                    <canvas id="topProduits"></canvas>
                </div>
            </div>

            <!-- Répartition des clients par catégorie -->
            <div class="chart-card">
                <h5><i class="fas fa-user-tag"></i> Répartition des clients par catégorie</h5>
                <p>Nombre de clients par type</p>
                <div class="chart-container">
                    <canvas id="clientsParCategorie"></canvas>
                </div>
            </div>

            <!-- Répartition du stock par catégorie -->
            <div class="chart-card">
                <h5><i class="fas fa-warehouse"></i> Répartition du stock par catégorie</h5>
                <p>Quantité en stock par catégorie de produit</p>
                <div class="chart-container">
                    <canvas id="stockParCategorie"></canvas>
                </div>
            </div>
        </section>
    </main>

    <!-- Script Chart.js -->
    <script>
        // Injection des données PHP dans JS
        const stats = <?= $stats_json ?? '{}' ?>;

        // Évolution des ventes par mois
        const mois = (stats.ventes_par_mois ?? []).map(item => {
            // item.mois format YYYY-MM, on veut le nom du mois
            const moisNum = parseInt(item.mois.split('-')[1], 10);
            const moisNoms = ['Janvier', 'Février', 'Mars', 'Avril', 'Mai', 'Juin', 'Juillet', 'Août', 'Septembre', 'Octobre', 'Novembre', 'Décembre'];
            return moisNoms[moisNum - 1] + ' ' + item.mois.split('-')[0];
        });
        const ventesData = (stats.ventes_par_mois ?? []).map(item => parseFloat(item.total));

        // Top 5 produits les plus vendus
        const produitsNoms = (stats.top_produits ?? []).map(item => item.nom);
        const produitsQuantites = (stats.top_produits ?? []).map(item => parseInt(item.quantite, 10));

        // Répartition des clients par catégorie
        const clientsCategories = (stats.clients_par_categorie ?? []).map(item => item.nom);
        const clientsNombres = (stats.clients_par_categorie ?? []).map(item => parseInt(item.nombre, 10));

        // Répartition du stock par catégorie
        const stockCategories = (stats.stock_par_categorie ?? []).map(item => item.nom);
        const stockQuantites = (stats.stock_par_categorie ?? []).map(item => parseInt(item.quantite, 10));

        // Évolution des ventes par mois (Graphique en courbe)
        const ventesParMois = new Chart(document.getElementById('ventesParMois'), {
            type: 'line',
            data: {
                labels: mois,
                datasets: [{
                    label: 'Montant des ventes (Ar)',
                    data: ventesData,
                    borderColor: '#3498db',
                    backgroundColor: 'rgba(52, 152, 219, 0.1)',
                    fill: true,
                    tension: 0.4
                }]
            },
            options: {
                responsive: true,
                maintainAspectRatio: false,
                scales: {
                    y: { 
                        beginAtZero: true, 
                        title: { display: true, text: 'Ariary' },
                        ticks: {
                            callback: function(value) {
                                return value.toLocaleString() + ' Ar';
                            }
                        }
                    },
                    x: { title: { display: true, text: 'Mois' } }
                },
                plugins: {
                    legend: { display: true, position: 'top' }
                }
            }
        });

        // Top 5 produits les plus vendus (Diagramme en barre)
        const topProduits = new Chart(document.getElementById('topProduits'), {
            type: 'bar',
            data: {
                labels: produitsNoms,
                datasets: [{
                    label: 'Quantité vendue',
                    data: produitsQuantites,
                    backgroundColor: [
                        'rgba(52, 152, 219, 0.7)',
                        'rgba(46, 204, 113, 0.7)',
                        'rgba(155, 89, 182, 0.7)',
                        'rgba(241, 196, 15, 0.7)',
                        'rgba(231, 76, 60, 0.7)'
                    ],
                    borderColor: '#2c3e50',
                    borderWidth: 1
                }]
            },
            options: {
                responsive: true,
                maintainAspectRatio: false,
                scales: {
                    y: { beginAtZero: true, title: { display: true, text: 'Quantité' } },
                    x: { 
                        title: { display: true, text: 'Produit' },
                        ticks: { maxRotation: 45, minRotation: 45 }
                    }
                },
                plugins: {
                    legend: { display: false }
                }
            }
        });

        // Répartition des clients par catégorie (Camembert) 
        const clientsParCategorie = new Chart(document.getElementById('clientsParCategorie'), {
            type: 'pie',
            data: {
                labels: clientsCategories,
                datasets: [{
                    label: 'Nombre de clients',
                    data: clientsNombres,
                    backgroundColor: [
                        'rgba(52, 152, 219, 0.7)',
                        'rgba(231, 76, 60, 0.7)',
                        'rgba(46, 204, 113, 0.7)',
                        'rgba(241, 196, 15, 0.7)'
                    ],
                    borderColor: '#fff',
                    borderWidth: 2
                }]
            },
            options: {
                responsive: true,
                maintainAspectRatio: false,
                plugins: {
                    legend: { position: 'right' }
                }
            }
        });

        // Répartition du stock par catégorie (Donut)
        const stockParCategorie = new Chart(document.getElementById('stockParCategorie'), {
            type: 'doughnut',
            data: {
                labels: stockCategories,
                datasets: [{
                    label: 'Quantité en stock',
                    data: stockQuantites,
                    backgroundColor: [
                        'rgba(52, 152, 219, 0.7)',
                        'rgba(46, 204, 113, 0.7)',
                        'rgba(231, 76, 60, 0.7)',
                        'rgba(241, 196, 15, 0.7)'
                    ],
                    borderColor: '#fff',
                    borderWidth: 2
                }]
            },
            options: {
                responsive: true,
                maintainAspectRatio: false,
                cutout: '60%',
                plugins: {
                    legend: { position: 'right' }
                }
            }
        });
    </script>

    <style>
        .summary-cards .stats-card {
            transition: transform 0.2s;
            border: none;
            box-shadow: 0 2px 4px rgba(0,0,0,0.05);
        }
        .summary-cards .stats-card:hover {
            transform: translateY(-5px);
        }
        .icon-box {
            width: 48px;
            height: 48px;
            border-radius: 12px;
            display: flex;
            align-items: center;
            justify-content: center;
        }
        .icon-box i {
            font-size: 24px;
        }
    </style>
</body>
</html>