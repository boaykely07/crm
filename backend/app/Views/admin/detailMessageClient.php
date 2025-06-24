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

<div class="container-fluid mt-4">
    <div class="row justify-content-center">
        <div class="col-lg-7 col-md-9">
            <div class="card shadow-lg border-0 rounded-4">
                <div class="card-header bg-info text-white d-flex align-items-center justify-content-between rounded-top-4">
                    <h4 class="mb-0"><i class="fas fa-envelope-open-text me-2"></i>Détail du Message Client</h4>
                    <a href="<?= site_url('/admin/listeMessageClient') ?>" class="btn btn-light btn-sm">
                        <i class="fas fa-arrow-left"></i> Retour à la liste
                    </a>
                </div>
                <div class="card-body p-4">
                    <?php if (isset($message) && $message): ?>
                        <div class="mb-3">
                            <span class="fw-bold text-secondary">ID :</span>
                            <span class="badge bg-primary ms-2">#<?= $message['id'] ?></span>
                        </div>
                        <div class="mb-3">
                            <span class="fw-bold text-secondary">Client :</span>
                            <span class="ms-2"><i class="fas fa-user"></i> <?= $message['client_nom'] ?? $message['id_client'] ?></span>
                        </div>
                        <div class="mb-3">
                            <span class="fw-bold text-secondary">Message :</span>
                            <div class="alert alert-light border mt-2" style="white-space: pre-line;">
                                <?= nl2br(htmlspecialchars($message['message'])) ?>
                            </div>
                        </div>
                        <div class="mb-3">
                            <span class="fw-bold text-secondary">Date :</span>
                            <span class="ms-2"><i class="fas fa-calendar-alt"></i> <?= $message['date_message'] ?></span>
                        </div>
                        <div class="mb-3">
                            <span class="fw-bold text-secondary">Ticket lié :</span>
                            <span class="ms-2">
                                <?php if ($message['id_ticket']): ?>
                                    <a href="<?= site_url('/admin/tickets') ?>#ticket<?= $message['id_ticket'] ?>" class="badge bg-info text-white text-decoration-none">Ticket #<?= $message['id_ticket'] ?></a>
                                <?php else: ?>
                                    <span class="badge bg-secondary">-</span>
                                <?php endif; ?>
                            </span>
                        </div>
                        <div class="mb-3">
                            <span class="fw-bold text-secondary">Fichier joint :</span>
                            <span class="ms-2">
                                <?php if (!empty($message['fichier_url'])): ?>
                                    <a href="<?= base_url('upload/' . basename($message['fichier_url'])) ?>" target="_blank" class="btn btn-outline-primary btn-sm">
                                        <i class="fas fa-file-pdf"></i> Voir le fichier
                                    </a>
                                <?php else: ?>
                                    <span class="text-muted">Aucun fichier joint</span>
                                <?php endif; ?>
                            </span>
                        </div>
                    <?php else: ?>
                        <div class="alert alert-warning">Aucun détail trouvé pour ce message.</div>
                    <?php endif; ?>
                </div>
            </div>
        </div>
    </div>
</div>
