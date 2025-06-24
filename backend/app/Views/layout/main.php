<!DOCTYPE html>
<html lang="fr">
<head>
    <meta charset="UTF-8">
    <title><?= $title ?? 'CRM' ?></title>
    <meta name="viewport" content="width=device-width, initial-scale=1">
    <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/5.15.4/css/all.min.css">
    <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.2/dist/css/bootstrap.min.css" rel="stylesheet">
    <style>
        :root {
            --primary: #4361EE;
            --primary-light: #4895EF;
            --primary-dark: #3F37C9;
            --success: #4CC9F0;
            --danger: #F72585;
            --background: #F8FAFC;
            --sidebar-width: 240px;
            --shadow: 0 2px 4px rgba(0,0,0,0.05);
        }

        body {
            background: var(--background);
            min-height: 100vh;
            overflow-x: hidden;
        }

        .sidebar {
            min-width: var(--sidebar-width);
            max-width: var(--sidebar-width);
            background: white;
            color: #333;
            position: fixed;
            height: 100vh;
            transition: all 0.3s ease;
            box-shadow: var(--shadow);
            z-index: 1000;
            padding: 1rem;
        }

        .sidebar .nav-link {
            padding: 10px 15px;
            color: #555;
            border-radius: 6px;
            margin: 4px 0;
            transition: all 0.2s ease;
        }

        .sidebar .nav-link:hover {
            background: var(--primary-light);
            color: white;
        }

        .sidebar .nav-link.active {
            background: var(--primary);
            color: white;
            box-shadow: 0 2px 4px rgba(67, 97, 238, 0.3);
        }

        .logo-container {
            padding: 15px 10px;
            margin-bottom: 15px;
            border-bottom: 1px solid #eee;
        }

        .logo-container img {
            width: 32px;
            height: 32px;
        }

        .main-content {
            background: white;
            margin-left: var(--sidebar-width);
            width: calc(100% - var(--sidebar-width));
            min-height: 100vh;
            padding: 2rem;
        }

        .btn {
            padding: 8px 16px;
            border-radius: 6px;
        }

        .btn-warning {
            background: var(--primary);
            border-color: var(--primary);
            color: white;
            box-shadow: 0 2px 4px rgba(67, 97, 238, 0.3);
        }

        .btn-warning:hover {
            background: var(--primary-dark);
            border-color: var(--primary-dark);
            box-shadow: 0 4px 6px rgba(67, 97, 238, 0.35);
        }

        .btn-danger {
            background: var(--danger);
            border-color: var(--danger);
        }

        .btn-danger:hover {
            background: #ff7593;
            border-color: #ff7593;
        }

        @media (max-width: 768px) {
            .sidebar {
                transform: translateX(-100%);
            }
            .main-content {
                margin-left: 0;
                width: 100%;
            }
        }
    </style>
    <?= $this->renderSection('styles') ?>
</head>
<body class="bg-light">
    <!-- Sidebar -->
    <aside class="sidebar d-flex flex-column flex-shrink-0 p-3">
    <div class="logo-container">
        <img src="https://cdn-icons-png.flaticon.com/512/3135/3135715.png" alt="CRM Logo" class="me-2">
        <span class="fs-4 fw-bold">Dashboard</span>
    </div>
    <ul class="nav nav-pills flex-column mb-auto">
        <li>
            <a href="<?= site_url('vue-annuel') ?>" class="nav-link <?= current_url() == site_url('vue-annuel') ? 'active' : '' ?>">
                <i class="fas fa-calendar-alt me-2"></i>Vue Annuelle
            </a>
        </li>
        <li>
            <a href="<?= site_url('previsions') ?>" class="nav-link <?= current_url() == site_url('previsions') ? 'active' : '' ?>">
                <i class="fas fa-chart-line me-2"></i>Prévisions
            </a>
        </li>
    </ul>
        <div class="mt-3">
            <a href="<?= site_url('crm') ?>" class="btn btn-warning w-100">
                <i class="fas fa-users me-2"></i>CRM
            </a>
        </div>
    <form method="post" action="<?= site_url('auth/logout') ?>" class="mt-auto">
        <button type="submit" class="btn btn-danger w-100 mt-4">
            <i class="fas fa-sign-out-alt me-2"></i>Déconnexion
        </button>
    </form>
</aside>

    <!-- Main content -->
    <main class="main-content">
        <?= $this->renderSection('content') ?>
    </main>

    <script>
        // Gestion du menu responsive
        document.addEventListener('DOMContentLoaded', function() {
            const handleResize = () => {
                if (window.innerWidth <= 768) {
                    document.querySelector('.sidebar').classList.add('d-none');
                } else {
                    document.querySelector('.sidebar').classList.remove('d-none');
                }
            };

            window.addEventListener('resize', handleResize);
            handleResize();
        });
    </script>
    <?= $this->renderSection('scripts') ?>
</body>
</html>
