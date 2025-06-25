<?php $active = 'messages'; include __DIR__.'/client.php'; ?>
<style>
    .comments-container {
        max-width: 600px;
        margin: 0 auto;
        background: #fff;
        border-radius: 18px;
        box-shadow: 0 8px 32px rgba(67,97,238,0.13);
        padding: 0;
        display: flex;
        flex-direction: column;
        min-height: 70vh;
    }
    .comments-header {
        background: linear-gradient(90deg, var(--primary) 0%, var(--primary-light) 100%);
        color: #fff;
        border-top-left-radius: 18px;
        border-top-right-radius: 18px;
        padding: 22px 28px 16px 28px;
        font-size: 1.3rem;
        font-weight: 600;
        display: flex;
        align-items: center;
    }
    .comments-header i { margin-right: 12px; }
    .comments-list {
        flex: 1 1 auto;
        overflow-y: auto;
        padding: 24px 18px 8px 18px;
        background: #f8fafc;
        max-height: 60vh;
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
        padding: 18px 18px 18px 18px;
        display: flex;
        gap: 8px;
        align-items: center;
        border-bottom-left-radius: 18px;
        border-bottom-right-radius: 18px;
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
        width: 44px;
        height: 44px;
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
    <?php if (session()->getFlashdata('success')): ?>
        <div class="alert alert-success mt-4" role="alert">
            <?= session()->getFlashdata('success') ?>
        </div>
    <?php endif; ?>
    <?php if (session()->getFlashdata('error')): ?>
        <div class="alert alert-danger mt-4" role="alert">
            <?= session()->getFlashdata('error') ?>
        </div>
    <?php endif; ?>
    <div class="comments-container mt-4 mb-4">
        <div class="comments-header">
            <i class="fas fa-comments"></i> Commentaires du message #<?= $message['id'] ?>
        </div>
        <div class="comments-list" id="commentsList">
            <?php if (!empty($commentaires)): ?>
                <?php foreach ($commentaires as $c): ?>
                    <div class="comment-bubble <?= $c['auteur'] ?>">
                        <div class="comment-author">
                            <?= ucfirst($c['auteur']) ?>
                        </div>
                        <?= nl2br(htmlspecialchars($c['commentaire'])) ?>
                        <div class="comment-date">
                            <?= date('d/m/Y H:i', strtotime($c['date_commentaire'])) ?>
                        </div>
                    </div>
                <?php endforeach; ?>
            <?php else: ?>
                <div class="text-center text-muted">Aucun commentaire.</div>
            <?php endif; ?>
        </div>
        <form class="comment-input-row" method="post" action="<?= site_url('/client/commentaire-message/'.$message['id']) ?>">
            <input type="hidden" name="id_message_client" value="<?= $message['id'] ?>">
            <input type="text" name="commentaire" placeholder="Votre commentaire..." required maxlength="500">
            <button type="submit" title="Envoyer"><i class="fas fa-paper-plane"></i></button>
        </form>
    </div>
    <?php if (!empty($ticket) && $ticket['statut'] === 'resolu'): ?>
        <div class="text-center mt-4 mb-2">
            <span style="font-size:1.2rem; color:#222; font-weight:500;">Notez ce ticket :</span>
            <span id="starRating">
                <?php for ($i = 1; $i <= 5; $i++): ?>
                    <i class="fa-star fa-lg fas<?= ($ticket['etoiles'] ?? 0) >= $i ? ' text-warning' : ' text-secondary' ?> star-select" data-value="<?= $i ?>" style="cursor:pointer;"></i>
                <?php endfor; ?>
            </span>
            <span id="starMsg" class="ms-2 text-success"></span>
            <form method="post" action="<?= site_url('/client/ticket/etoiles/'.$ticket['id']) ?>" class="d-inline-block ms-3" style="vertical-align:middle;">
                <input type="number" name="etoiles" min="1" max="5" style="width:48px; border-radius:8px; border:1px solid #ccc; padding:2px 8px; text-align:center;">
                <button type="submit" class="btn btn-warning py-1 px-2 ms-1" title="Noter">
                    <i class="fas fa-star"></i>
                </button>
            </form>
        </div>
    <?php endif; ?>
</div>
<link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/6.4.0/css/all.min.css" />
<script>
// Gestion du rating étoile
<?php if (!empty($ticket) && $ticket['statut'] === 'resolu'): ?>
document.querySelectorAll('.star-select').forEach(star => {
    star.addEventListener('click', function() {
        const value = this.getAttribute('data-value');
        fetch('<?= site_url('/client/ticket/etoiles/') . $ticket['id'] ?>', {
            method: 'POST',
            headers: { 'Content-Type': 'application/json', 'Accept': 'application/json' },
            body: JSON.stringify({ etoiles: value })
        })
        .then(res => res.json())
        .then(data => {
            if (data.success) {
                document.getElementById('starMsg').textContent = 'Merci pour votre note !';
                // Mettre à jour l'affichage des étoiles
                document.querySelectorAll('.star-select').forEach((s, idx) => {
                    s.classList.remove('text-warning');
                    s.classList.remove('text-secondary');
                    if (idx < value) s.classList.add('text-warning');
                    else s.classList.add('text-secondary');
                });
            }
        });
    });
});
<?php endif; ?>
</script>
