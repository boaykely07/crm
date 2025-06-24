<!DOCTYPE html>
<html lang="fr">
<head>
    <meta charset="UTF-8">
    <title>CRM | Gestion</title>
    <meta name="viewport" content="width=device-width, initial-scale=1">
    <!-- Import des polices Google Fonts -->
    <link href="https://fonts.googleapis.com/css2?family=Inter:wght@400;500;600;700&display=swap" rel="stylesheet">
    <!-- Font Awesome pour les icônes -->
    <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/6.5.1/css/all.min.css">
    <link rel="stylesheet" href="<?= base_url('assets/css/stats.css') ?>"> <!-- Utilisation du même fichier CSS -->
    <style>
        /* Styles spécifiques à cette page qui ne seraient pas dans le SCSS commun */
        .stat-card {
            transition: transform 0.3s ease, box-shadow 0.3s ease;
        }
        .stat-card:hover {
            transform: translateY(-5px);
            box-shadow: 0 8px 16px rgba(0, 0, 0, 0.15);
        }
        .card-grid {
            display: grid;
            grid-template-columns: repeat(auto-fit, minmax(300px, 1fr));
            gap: 1.5rem;
        }
        .stat-card .card-title {
            display: flex;
            align-items: center;
            font-size: 1.25rem;
            font-weight: 600;
            color: #2c3e50;
            margin-bottom: 0.5rem;
        }
        .stat-card .card-title i {
            margin-right: 10px;
            color: #3498db;
        }
        .stat-card .card-text {
            color: #7f8c8d;
            font-size: 0.9rem;
            margin-bottom: 1rem;
            padding-left: 26px;
        }
        .stat-card h3 {
            font-size: 2rem;
            font-weight: 700;
        }
        .text-primary { color: #3498db; }
        .text-danger { color: #e74c3c; }
        .text-warning { color: #f1c40f; }
        .text-success { color: #2ecc71; }
    </style>
</head>
<body>
    <!-- Sidebar -->
    <aside class="sidebar">
        <div class="logo">
            <img src="https://cdn-icons-png.flaticon.com/512/3135/3135715.png" alt="CRM Logo">
            <span>CRM</span>
        </div>
        <ul class="nav">
            <li><a href="<?= site_url('crm') ?>" class="active"><i class="fas fa-home"></i><span>Accueil CRM</span></a></li>
            <li><a href="<?= site_url('crm/actions') ?>"><i class="fas fa-tasks"></i><span>Actions</span></a></li>
            <li><a href="<?= site_url('crm/stats') ?>"><i class="fas fa-chart-bar"></i><span>Statistiques</span></a></li>
        </ul>
        <a href="<?= site_url('vue-annuel') ?>" class="logout">
            <button type="submit"><i class="fas fa-sign-out-alt"></i><span>Quitter</span></button>
        </a>
    </aside>
    
    <!-- Main content -->
    <main class="main">
        <div class="header">
            <div>
                <h1>Bienvenue dans le module CRM</h1>
                <p>Gérez vos clients, vos actions et vos ventes ici.</p>
            </div>
            <div class="profile">
                <img src="https://via.placeholder.com/40" alt="Profile">
                <div class="info">
                    <div class="name">Jean Dupont</div>
                    <div class="role">Administrateur</div>
                </div>
            </div>
        </div>

        <!-- Statistiques -->
        <section>
            <h3 class="fw-bold mb-4"><i class="fas fa-chart-pie"></i> Statistiques</h3>
            <div class="card-grid">
                <!-- Nombre total de clients -->
                <div class="chart-card stat-card">
                    <h5 class="card-title"><i class="fas fa-users"></i> Nombre total de clients</h5>
                    <p class="card-text">Nombre de clients enregistrés</p>
                    <h3 class="text-primary"><?= esc($stats['total_clients']) ?></h3>
                </div>

                <!-- Nombre de ventes ce mois -->
                <div class="chart-card stat-card">
                    <h5 class="card-title"><i class="fas fa-shopping-cart"></i> Nombre de ventes ce mois</h5>
                    <p class="card-text">Nombre de ventes effectuées dans le mois</p>
                    <h3 class="text-primary"><?= esc($stats['ventes_mois']) ?></h3>
                </div>

                <!-- Produits en stock faible -->
                <div class="chart-card stat-card">
                    <h5 class="card-title"><i class="fas fa-exclamation-triangle"></i> Produits en stock faible</h5>
                    <p class="card-text">Produits dont le stock est en dessous du seuil critique</p>
                    <h3 class="text-danger"><?= esc($stats['produits_stock_faible']) ?></h3>
                </div>

                <!-- Montant total des ventes -->
                <div class="chart-card stat-card">
                    <h5 class="card-title"><i class="fas fa-money-bill-wave"></i> Montant total des ventes</h5>
                    <p class="card-text">Total (en Ariary) des ventes réalisées</p>
                    <h3 class="text-primary"><?= number_format($stats['total_ventes'], 0, ',', ' ') ?> Ar</h3>
                </div>

                <!-- Actions en attente -->
                <div class="chart-card stat-card">
                    <h5 class="card-title"><i class="fas fa-clock"></i> Actions en attente</h5>
                    <p class="card-text">Nombre d'actions CRM en attente</p>
                    <h3 class="text-warning"><?= esc($stats['actions_en_attente']) ?></h3>
                </div>

                <!-- Actions terminées -->
                <div class="chart-card stat-card">
                    <h5 class="card-title"><i class="fas fa-check-circle"></i> Actions terminées</h5>
                    <p class="card-text">Nombre d'actions CRM terminées</p>
                    <h3 class="text-success"><?= esc($stats['actions_terminees']) ?></h3>
                </div>
            </div>
        </section>
    </main>

    <script src="https://cdn.jsdelivr.net/npm/bootstrap@5.3.2/dist/js/bootstrap.bundle.min.js"></script>
</body>
</html>