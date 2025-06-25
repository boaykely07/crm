<?php $active = 'dashboard'; include __DIR__.'/client.php'; ?>
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

    .big-card-row {
        display: flex;
        gap: 32px;
        margin-bottom: 40px;
        flex-wrap: wrap;
    }
    .big-card {
        width: 340px;
        min-width: 220px;
        height: 170px;
        background: linear-gradient(120deg, #4361EE 60%, #4cc9f0 100%);
        color: #fff;
        border-radius: 18px;
        box-shadow: 0 8px 32px rgba(67,97,238,0.13);
        display: flex;
        flex-direction: column;
        align-items: center;
        justify-content: center;
        padding: 24px 18px;
        font-size: 1.1rem;
        position: relative;
        margin-bottom: 16px;
    }
    .big-card .icon {
        font-size: 2.2rem;
        margin-bottom: 10px;
        opacity: 0.92;
    }
    .big-card .big-label {
        font-size: 1.08rem;
        color: #e0e0e0;
        margin-bottom: 6px;
    }
    .big-card .big-value {
        font-size: 2.1rem;
        font-weight: 700;
        color: #fff;
        margin-bottom: 4px;
    }
    .big-card .big-msg {
        font-size: 1.08rem;
        color: #fff;
        text-overflow: ellipsis;
        white-space: nowrap;
        overflow: hidden;
        max-width: 260px;
    }
    @media (max-width: 900px) {
        .big-card-row { flex-direction: column; gap: 18px; }
        .big-card { width: 100%; min-width: 0; }
    }

    .dashboard-card .icon {
        font-size: 2.8rem;
        color: rgba(255, 255, 255, 0.2);
        margin-bottom: 20px;
        text-align: right;
    }

    .dashboard-card .display-4 {
        font-weight: 700;
        color: #fff;
        font-size: 2.8rem;
        margin-bottom: 8px;
    }

    .dashboard-label {
        font-size: 1.15rem;
        color: rgba(255, 255, 255, 0.85);
        font-weight: 400;
    }

    /* Cadre spécifique pour le dernier message (5cm x 3cm) */
    .small-message-box {
        width: 200px; /* ~5cm */
        height: 120px; /* ~3cm */
        background: #fff;
        color: var(--primary-dark);
        border-radius: var(--border-radius);
        padding: 15px;
        box-shadow: var(--box-shadow);
        transition: var(--transition);
        overflow: hidden;
        position: relative;
    }

    .small-message-box:hover {
        transform: translateY(-3px);
    }

    .small-message-box .title {
        display: flex;
        align-items: center;
        font-size: 0.9rem;
        font-weight: 600;
        margin-bottom: 8px;
        color: var(--primary);
    }

    .small-message-box .title i {
        margin-right: 6px;
        background: var(--primary-light);
        color: white;
        width: 24px;
        height: 24px;
        border-radius: 50%;
        display: flex;
        align-items: center;
        justify-content: center;
        font-size: 0.8rem;
    }

    .small-message-box .content {
        font-size: 0.85rem;
        line-height: 1.4;
        max-height: 50px;
        overflow: hidden;
        text-overflow: ellipsis;
        display: -webkit-box;
        -webkit-line-clamp: 2;
        -webkit-box-orient: vertical;
    }

    .small-message-box .date {
        font-size: 0.7rem;
        color: var(--gray);
        position: absolute;
        bottom: 10px;
        right: 15px;
    }
    
    .expand-icon {
        position: absolute;
        bottom: 10px;
        left: 15px;
        color: var(--primary);
        cursor: pointer;
        font-size: 0.8rem;
    }

    .mini-card-row {
        display: flex;
        gap: 24px;
        margin-bottom: 32px;
    }
    .mini-card {
        width: 190px; /* ~5cm */
        height: 110px; /* ~3cm */
        min-width: 150px;
        min-height: 90px;
        background: linear-gradient(120deg, #4361EE 60%, #4cc9f0 100%);
        color: #fff;
        border-radius: 14px;
        box-shadow: 0 4px 16px rgba(67,97,238,0.10);
        display: flex;
        flex-direction: column;
        align-items: center;
        justify-content: center;
        padding: 12px 10px;
        font-size: 1.05rem;
        position: relative;
    }
    .mini-card .icon {
        font-size: 1.5rem;
        margin-bottom: 4px;
        opacity: 0.85;
    }
    .mini-card .mini-label {
        font-size: 0.95rem;
        color: #e0e0e0;
        margin-bottom: 2px;
    }
    .mini-card .mini-value {
        font-size: 1.3rem;
        font-weight: 700;
        color: #fff;
        margin-bottom: 2px;
    }
    .mini-card .mini-msg {
        font-size: 0.95rem;
        color: #fff;
        text-overflow: ellipsis;
        white-space: nowrap;
        overflow: hidden;
        max-width: 150px;
    }
</style>
<div class="container-fluid">
    <h1 class="page-title">
        <i class="fas fa-tachometer-alt"></i> Tableau de bord
    </h1>

    <div class="big-card-row">
        <div class="big-card">
            <div class="icon"><i class="fas fa-envelope"></i></div>
            <div class="big-label">Messages envoyés</div>
            <div class="big-value"><?= $nbMessages ?></div>
        </div>
        <div class="big-card">
            <div class="icon"><i class="fas fa-paper-plane"></i></div>
            <div class="big-label">Dernier message</div>
            <div class="big-msg">
                <?php if (!empty($lastMessage)): ?>
                    <?= htmlspecialchars(mb_strimwidth($lastMessage['message'], 0, 60, '...')) ?>
                    <div class="date"><?= date('d/m/Y H:i', strtotime($lastMessage['date_message'])) ?></div>
                <?php else: ?>
                    <span class="text-light">Aucun</span>
                <?php endif; ?>
            </div>
        </div>
    </div>

</div>
</main>
</div>
<!-- FontAwesome pour les icônes -->
<link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/6.4.0/css/all.min.css" />