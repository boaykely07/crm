<!DOCTYPE html>
<html lang="fr">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Tableau de bord Admin</title>
    <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.2/dist/css/bootstrap.min.css" rel="stylesheet">
    <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/6.4.0/css/all.min.css">
    <link rel="stylesheet" href="<?= base_url('assets/css/admin.css') ?>">

    <style>
        body { 
            min-height: 100vh; 
            overflow-x: hidden;
            background-color: #E8FFF3;
        }
        .sidebar {
            min-width: 250px;
            max-width: 250px;
            background: #99EDC3;
            color: #2C3E50;
            transition: all 0.3s;
            height: 100vh;
            position: fixed;
            box-shadow: 2px 0 10px rgba(0,0,0,0.1);
        }
        .sidebar a {
            color: #2C3E50;
            text-decoration: none;
            padding: 12px 20px;
            display: block;
            transition: all 0.3s;
            border-radius: 8px;
            margin: 5px 10px;
        }
        .sidebar a.active, .sidebar a:hover {
            background: #7DE2AD;
            color: #fff;
        }
        .content {
            margin-left: 250px;
            padding: 30px;
            width: calc(100% - 250px);
            transition: all 0.3s;
        }
        .sidebar-header {
            padding: 25px;
            text-align: center;
            border-bottom: 1px solid rgba(255, 255, 255, 0.2);
            background-color: #7DE2AD;
            color: #fff;
        }
        .nav-item {
            margin-bottom: 5px;
        }
        .toggle-btn {
            background: transparent;
            border: none;
            color: white;
            margin-right: 10px;
        }
        .card {
            box-shadow: 0 4px 15px rgba(0, 0, 0, 0.08);
            margin-bottom: 25px;
            border-radius: 15px;
            border: none;
            background-color: #fff;
        }
        .card-stat {
            border-left: 4px solid;
        }
        .card-stat.primary {
            border-left-color: #0d6efd;
        }
        .card-stat.success {
            border-left-color: #198754;
        }
        .card-stat.warning {
            border-left-color: #ffc107;
        }
        .card-stat.danger {
            border-left-color: #dc3545;
        }
        @media (max-width: 768px) {
            .sidebar {
                margin-left: -250px;
            }
            .content {
                margin-left: 0;
                width: 100%;
            }
            .sidebar.active {
                margin-left: 0;
            }
            .content.active {
                margin-left: 250px;
                width: calc(100% - 250px);
            }
        }
    </style>
</head>
<body>
    <header>
        <!-- Include your admin header here -->
    </header>
    <main>
        <div class="d-flex">
            <!-- Sidebar -->
            <div class="sidebar" id="sidebar">
                <div class="sidebar-header">
                    <h3>Admin Panel</h3>
                </div>
                <ul class="nav flex-column mt-3">
                    <li class="nav-item">
                        <a href="<?= site_url('/admin/listePrevisions') ?>" class="active">
                            <i class="fas fa-tachometer-alt me-2"></i> Listes des previsions
                        </a>
                    </li>
                    <li class="nav-item">
                    <a href="<?= site_url('/admin/listeRealisations') ?>" class="active">
                            <i class="fas fa-tachometer-alt me-2"></i> Listes realisations
                        </a>
                    </li>
                    <li class="nav-item">
                    <a href="<?= site_url('/admin/listeBudgetCRM') ?>" class="active">
                            <i class="fas fa-tachometer-alt me-2"></i> Listes Budget CRM
                        </a>
                    </li>
                    <li class="nav-item">
                        <a href="<?= site_url('/admin/tickets') ?>" class="active">
                            <i class="fas fa-ticket-alt me-2"></i> Gestion des Tickets
                        </a>
                    </li>
                    <form method="post" action="<?= site_url('auth/logout') ?>" class="mt-auto">
                        <button type="submit" class="btn btn-danger w-100 mt-4">
                            <i class="fas fa-sign-out-alt me-2"></i>Déconnexion
                        </button>
                    </form>
                    
                </ul>
            </div>

            <!-- Content -->
            <div class="container">
                <?= $content ?? '' ?>
            </div>


        </div>
    </main>
    <footer>
        <!-- Include your admin footer here -->
    </footer>

    <script src="https://cdn.jsdelivr.net/npm/bootstrap@5.3.2/dist/js/bootstrap.bundle.min.js"></script>
    <script>
        document.addEventListener('DOMContentLoaded', function() {
            const sidebarToggle = document.getElementById('sidebarToggle');
            const sidebar = document.getElementById('sidebar');
            const content = document.getElementById('content');

            sidebarToggle.addEventListener('click', function() {
                sidebar.classList.toggle('active');
                content.classList.toggle('active');
            });
        });
    </script>
</body>
</html>