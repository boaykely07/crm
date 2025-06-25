<?php $active = 'messages'; include __DIR__.'/client.php'; ?>
<style>
    .page-title {
        font-size: 2rem;
        font-weight: 700;
        color: var(--primary-dark);
        margin-bottom: 32px;
        display: flex;
        align-items: center;
    }

    .page-title i {
        margin-right: 12px;
        background: var(--primary);
        color: white;
        width: 50px;
        height: 50px;
        border-radius: 50%;
        display: flex;
        align-items: center;
        justify-content: center;
        box-shadow: 0 4px 12px rgba(67, 97, 238, 0.25);
    }

    .card {
        border-radius: var(--border-radius);
        box-shadow: var(--box-shadow);
        border: none;
        overflow: hidden;
        margin-bottom: 30px;
        transition: var(--transition);
    }

    .card:hover {
        box-shadow: 0 8px 30px rgba(0, 0, 0, 0.12);
    }

    .card-header {
        background: linear-gradient(90deg, var(--primary) 0%, var(--primary-light) 100%);
        color: white;
        border: none;
        padding: 20px 25px;
        font-size: 1.3rem;
        font-weight: 600;
    }

    .card-header i {
        margin-right: 12px;
    }

    .table-messages {
        width: 100%;
        border-collapse: separate;
        border-spacing: 0;
    }

    .table-messages thead th {
        background-color: #f1f5f9;
        color: var(--primary-dark);
        font-weight: 600;
        padding: 16px 20px;
        border-bottom: 2px solid #e2e8f0;
    }

    .table-messages tbody tr {
        transition: var(--transition);
    }

    .table-messages tbody tr:hover {
        background-color: #f8fafc;
        transform: translateX(3px);
    }

    .table-messages td {
        padding: 16px 20px;
        border-bottom: 1px solid #edf2f7;
        vertical-align: top;
    }

    .msg-content {
        max-width: 500px;
        white-space: pre-line;
        word-break: break-word;
        font-size: 1.05rem;
        line-height: 1.7;
    }

    .badge-ticket {
        background: var(--primary);
        color: #fff;
        font-size: 0.95rem;
        padding: 6px 12px;
        border-radius: 20px;
        font-weight: 500;
        display: inline-flex;
        align-items: center;
    }

    .icon-ticket {
        margin-right: 6px;
    }

    .file-badge {
        background: var(--accent);
        color: white;
        padding: 5px 12px;
        border-radius: 20px;
        font-size: 0.9rem;
        display: inline-flex;
        align-items: center;
        margin-top: 8px;
        transition: var(--transition);
    }

    .file-badge:hover {
        background: var(--primary-light);
        transform: translateY(-2px);
    }

    .file-badge i {
        margin-right: 6px;
    }

    /* Floating Action Button */
    .fab-add-message {
        position: fixed;
        right: 32px;
        bottom: 32px;
        z-index: 1000;
        background: linear-gradient(135deg, var(--primary) 0%, var(--primary-light) 100%);
        color: #fff;
        border: none;
        border-radius: 50%;
        width: 64px;
        height: 64px;
        box-shadow: 0 6px 20px rgba(67, 97, 238, 0.3);
        display: flex;
        align-items: center;
        justify-content: center;
        font-size: 1.8rem;
        transition: var(--transition);
        cursor: pointer;
        animation: pulse 2s infinite;
    }

    .fab-add-message:focus, 
    .fab-add-message:hover {
        background: linear-gradient(135deg, var(--primary-dark) 0%, var(--primary) 100%);
        box-shadow: 0 8px 25px rgba(67, 97, 238, 0.4);
        transform: scale(1.1) rotate(90deg);
    }

    @keyframes pulse {
        0% { box-shadow: 0 0 0 0 rgba(67, 97, 238, 0.5); }
        70% { box-shadow: 0 0 0 12px rgba(67, 97, 238, 0); }
        100% { box-shadow: 0 0 0 0 rgba(67, 97, 238, 0); }
    }

    /* Modal */
    .modal-dialog {
        max-width: 700px;
        display: flex;
        align-items: center;
        min-height: calc(100vh - 3.5rem);
    }

    .modal-content {
        border-radius: var(--border-radius);
        border: none;
        box-shadow: 0 12px 40px rgba(0, 0, 0, 0.2);
        overflow: hidden;
    }

    .modal-header {
        background: linear-gradient(90deg, var(--primary) 0%, var(--primary-light) 100%);
        color: white;
        border: none;
        padding: 20px 25px;
    }

    .modal-title {
        font-weight: 600;
        font-size: 1.5rem;
        display: flex;
        align-items: center;
    }

    .modal-title i {
        margin-right: 12px;
    }

    .btn-close {
        filter: invert(1);
        opacity: 0.8;
    }

    .modal-body {
        padding: 30px;
    }

    .form-label {
        font-weight: 600;
        color: var(--primary-dark);
        margin-bottom: 8px;
    }

    .form-control, .form-control:focus {
        border-radius: 10px;
        padding: 12px 16px;
        border: 1px solid #cbd5e1;
        box-shadow: none;
    }

    .form-control:focus {
        border-color: var(--primary-light);
        box-shadow: 0 0 0 3px rgba(67, 97, 238, 0.15);
    }

    textarea.form-control {
        min-height: 200px;
        resize: vertical;
    }

    .modal-footer {
        border-top: 1px solid #edf2f7;
        padding: 20px 30px;
    }

    .btn {
        padding: 10px 24px;
        border-radius: 10px;
        font-weight: 500;
        transition: var(--transition);
    }

    .btn-primary {
        background: linear-gradient(135deg, var(--primary) 0%, var(--primary-light) 100%);
        border: none;
    }

    .btn-primary:hover {
        background: linear-gradient(135deg, var(--primary-dark) 0%, var(--primary) 100%);
        transform: translateY(-2px);
        box-shadow: 0 4px 15px rgba(67, 97, 238, 0.3);
    }

    .btn-secondary {
        background: #f1f5f9;
        color: var(--dark);
        border: 1px solid #cbd5e1;
    }

    .btn-secondary:hover {
        background: #e2e8f0;
        transform: translateY(-2px);
    }

    @media (max-width: 768px) {
        .table-messages thead {
            display: none;
        }
        .table-messages, .table-messages tbody, 
        .table-messages tr, .table-messages td {
            display: block;
            width: 100%;
        }
        .table-messages tr {
            margin-bottom: 20px;
            border-radius: var(--border-radius);
            box-shadow: var(--box-shadow);
            overflow: hidden;
        }
        .table-messages td {
            padding: 12px 15px;
            text-align: left;
            position: relative;
            padding-left: 40%;
        }
        .table-messages td:before {
            content: attr(data-label);
            position: absolute;
            left: 15px;
            top: 12px;
            font-weight: 600;
            color: var(--primary);
        }
    }

    .action-col {
        text-align: center;
        width: 60px;
    }
    .comment-btn {
        background: none;
        border: none;
        color: var(--primary-dark);
        font-size: 1.3rem;
        cursor: pointer;
        transition: color 0.2s;
    }
    .comment-btn:hover { color: var(--primary-light); }
    /* Modal commentaires façon Facebook */
    .modal-comments .modal-dialog {
        max-width: 420px;
        margin: 2.5rem auto;
    }
    .modal-comments .modal-content {
        border-radius: 18px;
        box-shadow: 0 8px 32px rgba(67,97,238,0.13);
        border: none;
        padding: 0 2px;
        min-height: 480px;
        display: flex;
        flex-direction: column;
    }
    .modal-comments .modal-header {
        background: linear-gradient(90deg, var(--primary) 0%, var(--primary-light) 100%);
        color: #fff;
        border-top-left-radius: 18px;
        border-top-right-radius: 18px;
        border-bottom: none;
        padding: 18px 24px;
    }
    .modal-comments .modal-title { font-weight: 600; }
    .comments-list {
        flex: 1 1 auto;
        overflow-y: auto;
        padding: 18px 16px 8px 16px;
        background: #f8fafc;
        max-height: 350px;
    }
    .comment-bubble {
        max-width: 80%;
        margin-bottom: 18px;
        padding: 12px 18px;
        border-radius: 18px;
        background: #e9f0fb;
        color: #222;
        box-shadow: 0 2px 8px rgba(67,97,238,0.07);
        position: relative;
        word-break: break-word;
        font-size: 1.05rem;
    }
    .comment-bubble.client { background: #4361EE; color: #fff; margin-left: auto; }
    .comment-bubble.agent { background: #fff; color: #222; border: 1px solid #cbd5e1; margin-right: auto; }
    .comment-bubble.admin { background: #4cc9f0; color: #fff; margin-right: auto; }
    .comment-author {
        font-size: 0.92rem;
        font-weight: 600;
        margin-bottom: 2px;
        color: var(--primary-dark);
    }
    .comment-date {
        font-size: 0.85rem;
        color: #888;
        margin-top: 2px;
        text-align: right;
    }
    .comment-input-row {
        border-top: 1px solid #e2e8f0;
        background: #f8fafc;
        padding: 12px 16px;
        display: flex;
        gap: 8px;
        align-items: center;
    }
    .comment-input-row input[type="text"] {
        flex: 1;
        border-radius: 16px;
        border: 1px solid #cbd5e1;
        padding: 10px 16px;
        font-size: 1rem;
    }
    .comment-input-row button {
        border-radius: 50%;
        width: 40px;
        height: 40px;
        background: var(--primary);
        color: #fff;
        border: none;
        font-size: 1.2rem;
        display: flex;
        align-items: center;
        justify-content: center;
        transition: background 0.2s;
    }
    .comment-input-row button:hover { background: var(--primary-light); }
</style>
<div class="container-fluid">
    <h1 class="page-title">
        <i class="fas fa-envelope"></i> Mes messages envoyés
    </h1>
    
    <div class="card">
        <div class="card-header">
            <i class="fas fa-list"></i> Historique des messages
        </div>
        <div class="card-body">
            <div class="table-responsive">
                <table class="table table-messages">
                    <thead>
                        <tr>
                            <th>Message</th>
                            <th>Date</th>
                            <th>Ticket lié</th>
                            <th class="action-col">Action</th>
                        </tr>
                    </thead>
                    <tbody>
                        <?php if (!empty($messages)): ?>
                            <?php foreach ($messages as $msg): ?>
                                <tr>
                                    <td data-label="Message">
                                     
                                            <?= nl2br(htmlspecialchars($msg['message'])) ?>
                                            <?php if (!empty($msg['fichier_url'])): ?>
                                                <a href="<?= base_url('upload/'.$msg['fichier_url']) ?>" target="_blank" class="file-badge">
                                                    <i class="fas fa-file-pdf"></i> Voir le PDF
                                                </a>
                                            <?php endif; ?>
                                     
                                    </td>
                                    <td data-label="Date"><?= date('d/m/Y H:i', strtotime($msg['date_message'])) ?></td>
                                    <td data-label="Ticket lié">
                                        <?php if ($msg['id_ticket']): ?>
                                            <span class="badge-ticket">
                                                <i class="fas fa-ticket-alt icon-ticket"></i> #<?= $msg['id_ticket'] ?>
                                                <?php if (!empty($msg['ticket_statut'])): ?>
                                                    <span class="badge bg-info ms-2" style="font-size:0.95em; vertical-align:middle;">
                                                        - <?= ucfirst(str_replace('_', ' ', $msg['ticket_statut'])) ?>
                                                    </span>
                                                <?php endif; ?>
                                            </span>
                                        <?php else: ?>
                                            <span class="text-muted">-</span>
                                        <?php endif; ?>
                                    </td>
                                    <td class="action-col">
                                        <a class="comment-btn" href="<?= site_url('/client/commentaire-message/'.$msg['id']) ?>" title="Voir les commentaires">
                                            <i class="fas fa-comment-dots"></i>
                                        </a>
                                    </td>
                                </tr>
                            <?php endforeach; ?>
                        <?php else: ?>
                            <tr>
                                <td colspan="5" class="text-center py-5">
                                    <i class="fas fa-inbox fa-3x mb-3 text-muted"></i>
                                    <h5 class="text-muted">Aucun message trouvé</h5>
                                </td>
                            </tr>
                        <?php endif; ?>
                    </tbody>
                </table>
            </div>
        </div>
    </div>
</div>



<!-- Bouton flottant pour ajouter un message -->
<button class="fab-add-message" data-bs-toggle="modal" data-bs-target="#addMessageModal" title="Nouveau message">
    <i class="fas fa-plus"></i>
</button>

<!-- Modal d'ajout de message -->
<div class="modal fade" id="addMessageModal" tabindex="-1" aria-labelledby="addMessageModalLabel" aria-hidden="true">
  <div class="modal-dialog modal-dialog-centered">
    <div class="modal-content">
      <form method="post" action="<?= site_url('/client/message/add') ?>" enctype="multipart/form-data">
        <div class="modal-header">
          <h5 class="modal-title" id="addMessageModalLabel">
              <i class="fas fa-paper-plane me-2"></i>Envoyer un message
          </h5>
          <button type="button" class="btn-close btn-close-white" data-bs-dismiss="modal" aria-label="Close"></button>
        </div>
        <div class="modal-body">
          <div class="mb-4">
            <label for="message" class="form-label">Message <span class="text-danger">*</span></label>
            <textarea class="form-control" id="message" name="message" rows="6" required placeholder="Écrivez votre message ici..."></textarea>
          </div>
          <div class="mb-3">
            <label for="fichier" class="form-label">Fichier PDF (optionnel, max 5MB)</label>
            <input type="file" class="form-control" id="fichier" name="fichier" accept="application/pdf">
          </div>
        </div>
        <div class="modal-footer">
          <button type="button" class="btn btn-secondary" data-bs-dismiss="modal">Annuler</button>
          <button type="submit" class="btn btn-primary">Envoyer</button>
        </div>
      </form>
    </div>
  </div>
</div>
</main>
</div>
<!-- FontAwesome pour les icônes -->
<link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/6.4.0/css/all.min.css" />
<script src="https://cdn.jsdelivr.net/npm/bootstrap@5.3.2/dist/js/bootstrap.bundle.min.js"></script>
<script>
// JS pour charger et afficher les commentaires dynamiquement
const commentsModal = new bootstrap.Modal(document.getElementById('commentsModal'));
document.querySelectorAll('.comment-btn').forEach(btn => {
    btn.addEventListener('click', function() {
        const messageId = this.getAttribute('data-message-id');
        document.getElementById('id_message_client').value = messageId;
        document.getElementById('commentsList').innerHTML = '<div class="text-center text-muted my-4"><i class="fas fa-spinner fa-spin"></i> Chargement...</div>';
        commentsModal.show();
        fetch('<?= site_url('/client/comments/') ?>' + messageId)
            .then(res => res.json())
            .then(data => {
                let html = '';
                if (data.length === 0) {
                    html = '<div class="text-center text-muted">Aucun commentaire.</div>';
                } else {
                    data.forEach(c => {
                        let bubbleClass = c.auteur;
                        let author = c.auteur.charAt(0).toUpperCase() + c.auteur.slice(1);
                        html += `<div class="comment-bubble ${bubbleClass}">
                            <div class="comment-author">${author}</div>
                            ${c.commentaire}
                            <div class="comment-date">${c.date_commentaire}</div>
                        </div>`;
                    });
                }
                document.getElementById('commentsList').innerHTML = html;
                document.getElementById('commentsList').scrollTop = document.getElementById('commentsList').scrollHeight;
            });
    });
});
// JS pour ajouter un commentaire sans recharger la page
const commentForm = document.getElementById('commentForm');
commentForm.addEventListener('submit', function(e) {
    e.preventDefault();
    const id_message_client = document.getElementById('id_message_client').value;
    const commentaire = document.getElementById('commentaireInput').value.trim();
    if (!commentaire) return;
    fetch('<?= site_url('/client/comments/add') ?>', {
        method: 'POST',
        headers: { 'Content-Type': 'application/json', 'Accept': 'application/json' },
        body: JSON.stringify({ id_message_client, commentaire })
    })
    .then(res => res.json())
    .then(data => {
        if (data.success) {
            // Recharge les commentaires
            fetch('<?= site_url('/client/comments/') ?>' + id_message_client)
                .then(res => res.json())
                .then(data => {
                    let html = '';
                    if (data.length === 0) {
                        html = '<div class="text-center text-muted">Aucun commentaire.</div>';
                    } else {
                        data.forEach(c => {
                            let bubbleClass = c.auteur;
                            let author = c.auteur.charAt(0).toUpperCase() + c.auteur.slice(1);
                            html += `<div class="comment-bubble ${bubbleClass}">
                                <div class="comment-author">${author}</div>
                                ${c.commentaire}
                                <div class="comment-date">${c.date_commentaire}</div>
                            </div>`;
                        });
                    }
                    document.getElementById('commentsList').innerHTML = html;
                    document.getElementById('commentsList').scrollTop = document.getElementById('commentsList').scrollHeight;
                });
            document.getElementById('commentaireInput').value = '';
        }
    });
});
</script>