<!DOCTYPE html>
<html lang="fr">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Tableau de bord Agent</title>
    <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.2/dist/css/bootstrap.min.css" rel="stylesheet">
    <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/6.4.0/css/all.min.css">
    <style>
        :root {
            --primary-color: #4e73df;
            --secondary-color: #858796;
            --success-color: #1cc88a;
            --info-color: #36b9cc;
            --warning-color: #f6c23e;
            --danger-color: #e74a3b;
            --light-color: #f8f9fc;
            --dark-color: #5a5c69;
        }

        body {
            background-color: #f8f9fc;
            font-family: 'Nunito', -apple-system, BlinkMacSystemFont, "Segoe UI", Roboto, "Helvetica Neue", Arial, sans-serif;
        }

        .navbar {
            background-color: white;
            box-shadow: 0 0.15rem 1.75rem 0 rgba(58, 59, 69, 0.15);
            padding: 1rem 0;
        }

        .navbar-brand {
            color: var(--primary-color);
            font-weight: 800;
            font-size: 1.5rem;
        }

        .nav-link {
            color: var(--secondary-color);
            font-weight: 600;
            padding: 0.5rem 1rem;
            transition: color 0.15s ease-in-out;
        }

        .nav-link:hover {
            color: var(--primary-color);
        }

        .nav-link.active {
            color: var(--primary-color);
        }

        .card {
            border: none;
            border-radius: 0.35rem;
            box-shadow: 0 0.15rem 1.75rem 0 rgba(58, 59, 69, 0.15);
            margin-bottom: 1.5rem;
        }

        .card-header {
            background-color: #f8f9fc;
            border-bottom: 1px solid #e3e6f0;
            padding: 1rem 1.25rem;
        }

        .card-header h6 {
            color: var(--primary-color);
            font-weight: 700;
            margin: 0;
        }

        .stat-card {
            border-left: 0.25rem solid;
            padding: 1.25rem;
        }

        .stat-card.primary {
            border-left-color: var(--primary-color);
        }

        .stat-card.success {
            border-left-color: var(--success-color);
        }

        .stat-card.warning {
            border-left-color: var(--warning-color);
        }

        .stat-card.danger {
            border-left-color: var(--danger-color);
        }

        .stat-card .stat-title {
            color: var(--secondary-color);
            font-size: 0.7rem;
            text-transform: uppercase;
            font-weight: 700;
        }

        .stat-card .stat-value {
            color: var(--dark-color);
            font-size: 1.5rem;
            font-weight: 700;
        }

        .table {
            margin-bottom: 0;
        }

        .table th {
            border-top: none;
            background-color: #f8f9fc;
            color: var(--secondary-color);
            font-weight: 700;
            text-transform: uppercase;
            font-size: 0.7rem;
        }

        .badge {
            font-weight: 600;
            padding: 0.5em 0.75em;
        }

        .btn-primary {
            background-color: var(--primary-color);
            border-color: var(--primary-color);
        }

        .btn-primary:hover {
            background-color: #2e59d9;
            border-color: #2e59d9;
        }

        .user-info {
            display: flex;
            align-items: center;
            gap: 0.5rem;
        }

        .user-info img {
            width: 40px;
            height: 40px;
            border-radius: 50%;
        }

        .user-info .user-name {
            font-weight: 600;
            color: var(--dark-color);
        }

        .user-info .user-role {
            font-size: 0.8rem;
            color: var(--secondary-color);
        }
    </style>
</head>
<body>
    <!-- Navbar -->
    <nav class="navbar navbar-expand-lg navbar-light">
        <div class="container-fluid">
            <a class="navbar-brand" href="<?= site_url('agent/dashboard') ?>">
                <i class="fas fa-ticket-alt me-2"></i>CRM Agent
            </a>
            <button class="navbar-toggler" type="button" data-bs-toggle="collapse" data-bs-target="#navbarNav">
                <span class="navbar-toggler-icon"></span>
            </button>
            <div class="collapse navbar-collapse" id="navbarNav">
                <ul class="navbar-nav me-auto">
                    <li class="nav-item">
                        <a class="nav-link <?= current_url() == site_url('agent/dashboard') ? 'active' : '' ?>" 
                           href="<?= site_url('agent/dashboard') ?>">
                            <i class="fas fa-tachometer-alt me-1"></i> Tableau de bord
                        </a>
                    </li>
                    <li class="nav-item">
                        <a class="nav-link <?= current_url() == site_url('agent/mes-tickets') ? 'active' : '' ?>" 
                           href="<?= site_url('agent/mes-tickets') ?>">
                            <i class="fas fa-ticket-alt me-1"></i> Mes tickets
                        </a>
                    </li>
                    <li class="nav-item">
                        <a class="nav-link <?= current_url() == site_url('agent/tickets-groupe') ? 'active' : '' ?>" 
                           href="<?= site_url('agent/tickets-groupe') ?>">
                            <i class="fas fa-users me-1"></i> Tickets du groupe
                        </a>
                    </li>
                </ul>
                <div class="d-flex align-items-center">
                    <div class="user-info me-3">
                        <img src="https://ui-avatars.com/api/?name=<?= urlencode($user['prenom'] . '+' . $user['nom']) ?>&background=4e73df&color=fff" 
                             alt="Avatar">
                        <div>
                            <div class="user-name"><?= $user['prenom'] . ' ' . $user['nom'] ?></div>
                            <div class="user-role">Agent</div>
                        </div>
                    </div>
                    <form method="post" action="<?= site_url('auth/logout') ?>">
                        <button type="submit" class="btn btn-outline-danger">
                            <i class="fas fa-sign-out-alt me-1"></i> Déconnexion
                        </button>
                    </form>
                </div>
            </div>
        </div>
    </nav>

    <!-- Main Content -->
    <div class="container-fluid py-4">
        <?= $this->renderSection('content') ?>
    </div>

    <script src="https://cdn.jsdelivr.net/npm/bootstrap@5.3.2/dist/js/bootstrap.bundle.min.js"></script>
    <script src="https://code.jquery.com/jquery-3.6.0.min.js"></script>
</body>
</html> 