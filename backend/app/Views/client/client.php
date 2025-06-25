<?php
if (!isset($active)) $active = '';
?>
<style>
    :root {
        --primary-dark: #3F37C9;
        --primary: #4361EE;
        --primary-light: #4895EF;
        --accent: #4CC9F0;
        --light: #f5f7fa;
        --dark: #212529;
        --gray: #6c757d;
        --border-radius: 12px;
        --box-shadow: 0 4px 20px rgba(0, 0, 0, 0.08);
        --transition: all 0.3s ease;
    }

    body {
        background: var(--light);
        font-family: 'Segoe UI', Tahoma, Geneva, Verdana, sans-serif;
        color: var(--dark);
        min-height: 100vh;
    }

    .client-layout {
        display: flex;
        min-height: 100vh;
    }

    .sidebar-client {
        min-width: 260px;
        max-width: 260px;
        background: linear-gradient(180deg, var(--primary-dark) 0%, var(--primary) 100%);
        color: #fff;
        display: flex;
        flex-direction: column;
        box-shadow: 2px 0 15px rgba(67, 97, 238, 0.15);
        z-index: 100;
        transition: var(--transition);
    }

    .sidebar-client .sidebar-header {
        padding: 32px 0 24px 0;
        text-align: center;
        font-size: 1.6rem;
        font-weight: 700;
        background: var(--primary);
        letter-spacing: 1px;
        border-bottom: 1px solid var(--accent);
        box-shadow: 0 4px 12px rgba(0, 0, 0, 0.1);
    }

    .sidebar-client .nav {
        flex-direction: column;
        gap: 6px;
        margin-top: 24px;
        padding: 0 12px;
    }

    .sidebar-client .nav-link {
        color: rgba(255, 255, 255, 0.85);
        padding: 14px 24px;
        border-radius: var(--border-radius);
        font-size: 1.1rem;
        transition: var(--transition);
        text-decoration: none;
        display: flex;
        align-items: center;
    }

    .sidebar-client .nav-link i {
        margin-right: 12px;
        font-size: 1.2rem;
        width: 24px;
        text-align: center;
    }

    .sidebar-client .nav-link.active, 
    .sidebar-client .nav-link:focus, 
    .sidebar-client .nav-link:hover {
        background: rgba(255, 255, 255, 0.15);
        color: #fff;
        font-weight: 500;
        transform: translateX(5px);
        box-shadow: 0 4px 12px rgba(0, 0, 0, 0.1);
    }

    .sidebar-client .logout {
        margin-top: auto;
        padding: 24px;
        border-top: 1px solid rgba(255, 255, 255, 0.1);
    }

    .sidebar-client .logout .btn {
        width: 100%;
        background: rgba(255, 255, 255, 0.1);
        border: 1px solid rgba(255, 255, 255, 0.2);
        border-radius: var(--border-radius);
        color: white;
        font-weight: 500;
        transition: var(--transition);
    }

    .sidebar-client .logout .btn:hover {
        background: rgba(255, 255, 255, 0.2);
        transform: translateY(-2px);
    }

    .welcome-text {
        font-size: 0.95rem;
        opacity: 0.9;
        margin-bottom: 16px;
        text-align: center;
    }

    .client-content {
        flex: 1;
        padding: 40px 32px 80px 32px;
        min-width: 0;
        overflow-y: auto;
        background: #f8fafc;
    }

    @media (max-width: 992px) {
        .client-layout {
            flex-direction: column;
        }
        .sidebar-client {
            min-width: 100%;
            max-width: 100%;
            height: auto;
        }
        .sidebar-client .nav {
            flex-direction: row;
            flex-wrap: wrap;
            margin-top: 0;
            padding: 10px;
        }
        .sidebar-client .nav-link {
            padding: 10px 15px;
            margin: 5px;
            font-size: 0.95rem;
        }
        .sidebar-client .logout {
            padding: 15px;
            text-align: center;
        }
        .client-content {
            padding: 25px 15px 80px 15px;
        }
    }
</style>
<div class="client-layout">
    <aside class="sidebar-client">
        <div class="sidebar-header">Espace Client</div>
        <nav class="nav flex-column" aria-label="Menu client">
            <a class="nav-link<?= $active === 'dashboard' ? ' active' : '' ?>" href="<?= site_url('/client/dashboard') ?>">
                <i class="fas fa-tachometer-alt"></i> Dashboard
            </a>
            <a class="nav-link<?= $active === 'messages' ? ' active' : '' ?>" href="<?= site_url('/client/liste-messages') ?>">
                <i class="fas fa-envelope"></i> Liste des messages
            </a>
        </nav>
        <div class="logout mt-auto">
            <div class="welcome-text">Bienvenue, <?= session('client_nom') ?></div>
            <a href="<?= site_url('/client/logout') ?>" class="btn">
                <i class="fas fa-sign-out-alt me-2"></i> Déconnexion
            </a>
        </div>
    </aside>
    <main class="client-content">