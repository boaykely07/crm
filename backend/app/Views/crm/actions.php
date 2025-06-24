<!DOCTYPE html>
<html lang="fr">
<head>
    <meta charset="UTF-8">
    <title>Liste des Actions | CRM</title>
    <meta name="viewport" content="width=device-width, initial-scale=1">
    <!-- Import des polices Google Fonts -->
    <link href="https://fonts.googleapis.com/css2?family=Inter:wght@400;500;600;700&display=swap" rel="stylesheet">
    <!-- Font Awesome pour les icônes -->
    <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/6.5.1/css/all.min.css">
    <link rel="stylesheet" href="<?= base_url('assets/css/stats.css') ?>">
    <style>
        /* Styles spécifiques à cette page */
        .actions-table {
            background: #ffffff;
            border-radius: 12px;
            box-shadow: 0 4px 12px rgba(0, 0, 0, 0.1);
            overflow: hidden;
            width: 100%;
            margin-bottom: 2rem;
        }
        
        .actions-table thead {
            background: #2c3e50;
            color: white;
        }
        
        .actions-table th, .actions-table td {
            padding: 1rem;
            border: none;
            border-bottom: 1px solid rgba(0, 0, 0, 0.05);
        }
        
        .actions-table tbody tr:hover {
            background-color: rgba(52, 152, 219, 0.05);
        }
        
        .status-badge {
            padding: 0.35rem 0.75rem;
            border-radius: 20px;
            font-size: 0.85rem;
            font-weight: 500;
            display: inline-block;
        }
        
        .status-pending {
            background-color: rgba(241, 196, 15, 0.2);
            color: #d35400;
        }
        
        .status-completed {
            background-color: rgba(46, 204, 113, 0.2);
            color: #27ae60;
        }

        .status-in-progress {
            background-color: rgba(52, 152, 219, 0.2);
            color: #2980b9;
        }
        
        .action-button {
            padding: 0.35rem 0.75rem;
            border-radius: 6px;
            font-size: 0.85rem;
            font-weight: 500;
            border: none;
            cursor: pointer;
            transition: all 0.3s ease;
            display: inline-flex;
            align-items: center;
        }
        
        .action-button i {
            margin-right: 5px;
        }
        
        .action-complete {
            background-color: #2ecc71;
            color: white;
        }
        
        .action-complete:hover {
            background-color: #27ae60;
            transform: translateY(-2px);
        }
        
        .action-reset {
            background-color: #f1c40f;
            color: white;
        }
        
        .action-reset:hover {
            background-color: #f39c12;
            transform: translateY(-2px);
        }
        
        .back-button {
            padding: 0.5rem 1.25rem;
            border-radius: 8px;
            font-size: 0.95rem;
            font-weight: 600;
            border: none;
            cursor: pointer;
            background-color: #7f8c8d;
            color: white;
            transition: all 0.3s ease;
            display: inline-flex;
            align-items: center;
        }
        
        .back-button i {
            margin-right: 6px;
        }
        
        .back-button:hover {
            background-color: #95a5a6;
            transform: translateY(-2px);
        }

        .modal {
            display: none;
            position: fixed;
            z-index: 1000;
            left: 0;
            top: 0;
            width: 100%;
            height: 100%;
            background-color: rgba(0, 0, 0, 0.4);
        }

        .modal-content {
            position: relative;
            background-color: #fefefe;
            margin: 15% auto;
            padding: 20px;
            border: 1px solid #888;
            width: 80%;
            max-width: 500px;
            border-radius: 8px;
            box-shadow: 0 4px 6px rgba(0, 0, 0, 0.1);
        }

        .close {
            color: #ff0000;
            position: absolute;
            right: 15px;
            top: 5px;
            font-size: 32px;
            font-weight: bold;
            transition: 0.3s;
        }

        .close:hover {
            color: #cc0000;
            cursor: pointer;
        }

        .form-group {
            margin-bottom: 15px;
        }

        .form-group label {
            display: block;
            margin-bottom: 5px;
        }

        .form-group input,
        .form-group select {
            width: 100%;
            padding: 8px;
            border: 1px solid #ddd;
            border-radius: 4px;
        }

        .date-fields {
            display: grid;
            grid-template-columns: 1fr 1fr;
            gap: 10px;
        }

        .date-fields select {
            width: 100%;
            padding: 8px;
            border: 1px solid #ddd;
            border-radius: 4px;
        }

        .text-danger {
            color: #dc3545;
        }

        .action-button:disabled {
            background-color: #cccccc;
            cursor: not-allowed;
        }
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
            <li><a href="<?= site_url('crm') ?>"><i class="fas fa-home"></i><span>Accueil CRM</span></a></li>
            <li><a href="<?= site_url('crm/actions') ?>" class="active"><i class="fas fa-tasks"></i><span>Actions</span></a></li>
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
                <h1>Liste des Actions</h1>
                <p>Gérez toutes vos actions CRM et leurs statuts</p>
            </div>
            <div class="profile">
                <img src="https://via.placeholder.com/40" alt="Profile">
                <div class="info">
                    <div class="name">Jean Dupont</div>
                    <div class="role">Administrateur</div>
                </div>
            </div>
        </div>

        <!-- Contenu principal -->
        <section class="chart-card">
            <div class="table-responsive">
                <table class="actions-table">
                    <thead>
                        <tr>
                            <th><i class="fas fa-hashtag"></i> #</th>
                            <th><i class="fas fa-heading"></i> Titre</th>
                            <th><i class="fas fa-user"></i> Client</th>
                            <th><i class="fas fa-calendar-alt"></i> Date</th>
                            <th><i class="fas fa-align-left"></i> Description</th>
                            <th><i class="fas fa-info-circle"></i> Status</th>
                            <th><i class="fas fa-cogs"></i> Action</th>
                        </tr>
                    </thead>
                    <tbody>
                        <?php if (!empty($actions)): ?>
                            <?php foreach ($actions as $action): ?>
                                <tr>
                                    <td><?= esc($action['id']) ?></td>
                                    <td><strong><?= esc($action['titre']) ?></strong></td>
                                    <td>
                                        <div><?= esc($action['client_nom']) ?></div>
                                        <small class="text-muted"><?= esc($action['client_email']) ?></small>
                                    </td>
                                    <td><?= esc($action['date_action']) ?></td>
                                    <td><?= esc($action['description']) ?></td>
                                    <td>
                                        <span class="status-badge <?= $action['status'] === 'En attente' ? 'status-pending' : ($action['status'] === 'En cours' ? 'status-in-progress' : 'status-completed') ?>">
                                            <?php if($action['status'] === 'En attente'): ?>
                                                <i class="fas fa-clock"></i>
                                            <?php elseif($action['status'] === 'En cours'): ?>
                                                <i class="fas fa-spinner fa-spin"></i>
                                            <?php else: ?>
                                                <i class="fas fa-check-circle"></i>
                                            <?php endif; ?>
                                            <?= esc($action['status']) ?>
                                        </span>
                                    </td>
                                    <td>
                                        <form method="post" action="<?= site_url('crm/actions/modifier-statut/' . $action['id']) ?>" class="d-inline">
                                            <?= csrf_field() ?>
                                            <?php if ($action['status'] === 'En attente'): ?>
                                                <button type="button" class="action-button action-complete" onclick="openModal(<?= esc($action['id']) ?>)">
                                                    <i class="fas fa-check"></i> Terminer
                                                </button>
                                            <?php elseif (($action['status'] === 'En cours')): ?>
                                                <button type="button" class="action-button action-complete" disabled style="opacity: 0.5; cursor: not-allowed;">
                                                    <i class="fas fa-spinner"></i> En cours
                                                </button>
                                                <?php else : ?>
                                                <button type="button" class="action-button action-complete" disabled style="opacity: 0.5; cursor: not-allowed;">
                                                    <i class="fas fa-check"></i> Terminer
                                                </button>

                                            <?php endif; ?>
                                        </form>
                                    </td>
                                </tr>
                            <?php endforeach; ?>
                        <?php else: ?>
                            <tr>
                                <td colspan="6" class="text-center py-4">
                                    <i class="fas fa-info-circle fa-2x mb-2 text-muted"></i>
                                    <p>Aucune action trouvée.</p>
                                </td>
                            </tr>
                        <?php endif; ?>
                    </tbody>
                </table>
            </div>
            
            <a href="<?= site_url('crm') ?>" class="back-button">
                <i class="fas fa-arrow-left"></i> Retour au CRM
            </a>
        </section>
    </main>

    <!-- Modal Budget -->
    <div id="budgetModal" class="modal">
        <div class="modal-content">
            <span class="close">&times;</span>
            <h2>Enregistrer le Budget</h2>
            
            <form id="budgetForm" method="post" action="<?= site_url('crm/create-budget') ?>">
                <?= csrf_field() ?>
                <input type="hidden" name="action_id" id="action_id">

                <div class="form-group">
                    <label for="date_periode">Période</label>
                    <div class="date-fields">
                        <select name="mois" id="mois" required>
                            <?php for ($m = 1; $m <= 12; $m++): ?>
                                <option value="<?= $m ?>"><?= date('F', mktime(0, 0, 0, $m, 1)) ?></option>
                            <?php endfor; ?>
                        </select>
                        <select name="annee" id="annee" required>
                            <?php for ($y = date('Y')-3; $y <= date('Y') + 10; $y++): ?>
                                <option value="<?= $y ?>"><?= $y ?></option>
                            <?php endfor; ?>
                        </select>
                    </div>
                </div>
                <div class="form-group">
                    <label for="montant">Montant (€)</label>
                    <input type="number" name="montant" id="montant" step="0.01" required>
                </div>
                <div class="form-group">
                    <label for="description">Description</label>
                    <textarea name="description" id="description" rows="4" style="width: 100%; padding: 8px; border: 1px solid #ddd; border-radius: 4px;" ></textarea>
                </div>
                <div class="form-group">
                    <button type="submit" class="action-button action-complete">
                        <i class="fas fa-save"></i> Enregistrer
                    </button>
                    <button type="button" class="action-button action-reset" onclick="closeModal()">
                        <i class="fas fa-times"></i> Annuler
                    </button>
                </div>
            </form>
        </div>
    </div>

    <script>
        // Get modal element
        const modal = document.getElementById('budgetModal');
        const closeBtn = document.querySelector('.close');

        // Function to open modal
        function openModal(actionId) {
            document.getElementById('action_id').value = actionId;
            modal.style.display = 'block';
        }

        // Function to close modal
        function closeModal() {
            modal.style.display = 'none';
            document.getElementById('budgetForm').reset();
        }

        // Close modal when clicking the close button
        closeBtn.addEventListener('click', closeModal);

        // Close modal when clicking outside the modal content
        window.addEventListener('click', function(event) {
            if (event.target === modal) {
                closeModal();
            }
        });

        // Form submission with fetch
        document.getElementById('budgetForm').addEventListener('submit', function(e) {
            e.preventDefault();
            fetch(this.action, {
                method: 'POST',
                body: new FormData(this)
            })
            .then(response => response.json())
            .then(data => {
                if (data.success) {
                    closeModal();
                    window.location.reload(); // Recharge la page pour mettre à jour l'affichage
                } else {
                    alert('Erreur : ' + data.message);
                }
            })
            .catch(error => {
                alert('Erreur lors de la soumission : ' + error.message);
            });
        });
    </script>
</body>
</html>