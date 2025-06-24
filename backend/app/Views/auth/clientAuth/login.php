<!DOCTYPE html>
<html lang="fr">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Connexion Client</title>
    <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.2/dist/css/bootstrap.min.css" rel="stylesheet">
    <style>
        body {
            background: linear-gradient(135deg, #f5f7fa 0%, #e4e9f2 100%);
            min-height: 100vh;
            display: flex;
            align-items: center;
            justify-content: center;
        }
        .auth-container {
            width: 100%;
            max-width: 400px;
            background-color: white;
            border-radius: 16px;
            box-shadow: 0 10px 15px rgba(67,97,238,0.08);
            overflow: hidden;
            position: relative;
            padding: 32px 24px 24px 24px;
        }
        .auth-logo {
            width: 64px;
            height: 64px;
            margin: 0 auto 18px auto;
            display: flex;
            align-items: center;
            justify-content: center;
            background: #4895EF;
            border-radius: 50%;
            box-shadow: 0 2px 8px rgba(67,97,238,0.15);
            transition: box-shadow 0.2s;
        }
        .auth-logo:hover {
            box-shadow: 0 4px 16px rgba(67,97,238,0.25);
        }
        .auth-logo svg {
            width: 32px;
            height: 32px;
            color: #fff;
        }
        .auth-title {
            text-align: center;
            font-size: 1.5rem;
            font-weight: 700;
            color: #3F37C9;
            margin-bottom: 8px;
        }
        .auth-subtitle {
            text-align: center;
            color: #6c757d;
            margin-bottom: 20px;
        }
        .form-label {
            font-weight: 500;
        }
        .btn-primary {
            background: #4361EE;
            border-color: #4361EE;
        }
        .btn-primary:hover {
            background: #3F37C9;
            border-color: #3F37C9;
        }
        .alert {
            font-size: 0.95rem;
        }
    </style>
</head>
<body>
    <div class="auth-container">
        <a href="<?= site_url('/') ?>" class="auth-logo" title="Retour à l'accueil">
            <svg xmlns="http://www.w3.org/2000/svg" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2" stroke-linecap="round" stroke-linejoin="round">
                <path d="M17 21v-2a4 4 0 0 0-4-4H5a4 4 0 0 0-4 4v2"></path>
                <circle cx="9" cy="7" r="4"></circle>
                <path d="M23 21v-2a4 4 0 0 0-3-3.87"></path>
                <path d="M16 3.13a4 4 0 0 1 0 7.75"></path>
            </svg>
        </a>
        <div class="auth-title">Connexion Client</div>
        <div class="auth-subtitle">Accédez à votre espace personnel</div>
        <?php if (session()->getFlashdata('error')): ?>
            <div class="alert alert-danger">
                <?= session()->getFlashdata('error') ?>
            </div>
        <?php endif; ?>
        <form method="post" action="<?= site_url('client/auth/login') ?>">
            <div class="mb-3">
                <label for="email" class="form-label">Email</label>
                <input type="email" class="form-control" id="email" name="email" required>
            </div>
            <div class="mb-3">
                <label for="mot_de_passe" class="form-label">Mot de passe</label>
                <input type="password" class="form-control" id="mot_de_passe" name="mot_de_passe" required>
            </div>
            <button type="submit" class="btn btn-primary w-100">Se connecter</button>
        </form>
    </div>
</body>
</html>
