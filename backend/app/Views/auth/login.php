<!DOCTYPE html>
<html lang="fr">
<head>
    <meta charset="UTF-8">
    <title>Connexion | CRM</title>
    <meta name="viewport" content="width=device-width, initial-scale=1">
    <style>
        :root {
            --primary: #4361EE;
            --primary-light: #4895EF;
            --primary-dark: #3F37C9;
            --success: #4CC9F0;
            --danger: #F72585;
            --gray-100: #f8f9fa;
            --gray-200: #e9ecef;
            --gray-300: #dee2e6;
            --gray-400: #ced4da;
            --gray-500: #adb5bd;
            --gray-600: #6c757d;
            --gray-700: #495057;
            --gray-800: #343a40;
            --gray-900: #212529;
            --shadow-sm: 0 2px 4px rgba(0,0,0,0.05);
            --shadow: 0 4px 6px rgba(0,0,0,0.1);
            --shadow-lg: 0 10px 15px rgba(0,0,0,0.1);
            --font-family: -apple-system, BlinkMacSystemFont, "Segoe UI", Roboto, Helvetica, Arial, sans-serif;
        }

        * {
            margin: 0;
            padding: 0;
            box-sizing: border-box;
        }

        body {
            font-family: var(--font-family);
            background: linear-gradient(135deg, #f5f7fa 0%, #e4e9f2 100%);
            min-height: 100vh;
            display: flex;
            align-items: center;
            justify-content: center;
            padding: 20px;
        }

        .auth-container {
            width: 100%;
            max-width: 400px;
            background-color: white;
            border-radius: 16px;
            box-shadow: var(--shadow-lg);
            overflow: hidden;
            position: relative;
        }

        .auth-header {
            text-align: center;
            padding: 30px 20px 20px;
        }

        .auth-logo {
            width: 60px;
            height: 60px;
            margin-bottom: 16px;
            padding: 14px;
            background-color: var(--primary-light);
            border-radius: 50%;
            display: inline-flex;
            align-items: center;
            justify-content: center;
        }

        .auth-logo svg {
            width: 28px;
            height: 28px;
            color: white;
        }

        .auth-title {
            font-size: 22px;
            font-weight: 700;
            color: var(--gray-800);
            margin-bottom: 6px;
        }

        .auth-subtitle {
            font-size: 14px;
            color: var(--gray-600);
            margin-bottom: 15px;
        }

        .auth-form {
            padding: 0 30px 30px;
        }

        .form-group {
            margin-bottom: 20px;
            position: relative;
        }

        .form-label {
            display: block;
            margin-bottom: 8px;
            font-size: 14px;
            font-weight: 500;
            color: var(--gray-700);
        }

        .form-control {
            width: 100%;
            padding: 12px 16px;
            font-size: 15px;
            border: 1px solid var(--gray-300);
            border-radius: 8px;
            background-color: white;
            transition: all 0.2s ease;
        }

        .form-control:focus {
            outline: none;
            border-color: var(--primary);
            box-shadow: 0 0 0 3px rgba(67, 97, 238, 0.15);
        }

        .form-select {
            width: 100%;
            padding: 12px 16px;
            font-size: 15px;
            border: 1px solid var(--gray-300);
            border-radius: 8px;
            background-color: white;
            appearance: none;
            background-image: url("data:image/svg+xml,%3Csvg xmlns='http://www.w3.org/2000/svg' width='16' height='16' viewBox='0 0 24 24' fill='none' stroke='%236c757d' stroke-width='2' stroke-linecap='round' stroke-linejoin='round'%3E%3Cpolyline points='6 9 12 15 18 9'%3E%3C/polyline%3E%3C/svg%3E");
            background-repeat: no-repeat;
            background-position: right 16px center;
            background-size: 16px;
        }

        .form-select:focus {
            outline: none;
            border-color: var(--primary);
            box-shadow: 0 0 0 3px rgba(67, 97, 238, 0.15);
        }

        .btn {
            display: inline-block;
            font-weight: 500;
            text-align: center;
            white-space: nowrap;
            vertical-align: middle;
            user-select: none;
            border: 1px solid transparent;
            padding: 12px 20px;
            font-size: 16px;
            line-height: 1.5;
            border-radius: 8px;
            transition: all 0.15s ease-in-out;
            cursor: pointer;
            width: 100%;
        }

        .btn-primary {
            color: white;
            background-color: var(--primary);
            border-color: var(--primary);
            box-shadow: 0 2px 4px rgba(67, 97, 238, 0.3);
        }

        .btn-primary:hover {
            background-color: var(--primary-dark);
            border-color: var(--primary-dark);
            transform: translateY(-1px);
            box-shadow: 0 4px 6px rgba(67, 97, 238, 0.35);
        }

        .btn-success {
            color: white;
            background-color: var(--success);
            border-color: var(--success);
            box-shadow: 0 2px 4px rgba(76, 201, 240, 0.3);
        }

        .btn-success:hover {
            background-color: #39b4da;
            border-color: #39b4da;
            transform: translateY(-1px);
            box-shadow: 0 4px 6px rgba(76, 201, 240, 0.35);
        }

        .auth-footer {
            text-align: center;
            padding: 15px 0 0;
        }

        .auth-footer-text {
            font-size: 14px;
            color: var(--gray-600);
        }

        .auth-link {
            color: var(--primary);
            font-weight: 600;
            text-decoration: none;
            transition: color 0.15s ease;
        }

        .auth-link:hover {
            color: var(--primary-dark);
            text-decoration: underline;
        }

        .alert {
            padding: 12px;
            border-radius: 8px;
            margin-bottom: 20px;
            font-size: 14px;
            text-align: center;
        }

        .alert-success {
            background-color: #d1fadf;
            color: #0f5132;
        }

        .alert-danger {
            background-color: #ffe2e5;
            color: #cc0022;
        }

        @media (max-width: 480px) {
            .auth-form {
                padding: 0 20px 20px;
            }

            .auth-container {
                border-radius: 12px;
            }

            .form-control, .form-select, .btn {
                padding: 10px 14px;
                font-size: 15px;
            }
        }
    </style>
</head>
<body>
    <!-- Page de connexion -->
    <div class="auth-container">
        <div class="auth-header">
            <a href="<?= site_url('/client/login') ?>" class="auth-logo" title="Retour à l'accueil">
                <svg xmlns="http://www.w3.org/2000/svg" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2" stroke-linecap="round" stroke-linejoin="round">
                    <path d="M17 21v-2a4 4 0 0 0-4-4H5a4 4 0 0 0-4 4v2"></path>
                    <circle cx="9" cy="7" r="4"></circle>
                    <path d="M23 21v-2a4 4 0 0 0-3-3.87"></path>
                    <path d="M16 3.13a4 4 0 0 1 0 7.75"></path>
                </svg>
            </a>
            <h1 class="auth-title">Bienvenue</h1>
            <p class="auth-subtitle">Connectez-vous à votre espace de gestion client</p>
        </div>

        <!-- Messages flash -->
        <?php if (session()->getFlashdata('success')): ?>
            <div class="auth-form">
                <div class="alert alert-success">
                    <?= esc(session()->getFlashdata('success')) ?>
                </div>
            </div>
        <?php endif; ?>
        
        <?php if (session()->getFlashdata('error')): ?>
            <div class="auth-form">
                <div class="alert alert-danger">
                    <?= esc(session()->getFlashdata('error')) ?>
                </div>
            </div>
        <?php endif; ?>

        <form class="auth-form" method="post" action="<?= site_url('auth/login') ?>">
            <div class="form-group">
                <label class="form-label">Email</label>
                <input type="email" name="email" required autocomplete="username" class="form-control">
            </div>
            <div class="form-group">
                <label class="form-label">Mot de passe</label>
                <input type="password" name="mot_de_passe" required autocomplete="current-password" class="form-control">
            </div>
            <button type="submit" class="btn btn-primary">Se connecter</button>
            
            <div class="auth-footer">
                <span class="auth-footer-text">Pas encore de compte ?</span>
                <a href="<?= site_url('register') ?>" class="auth-link">Inscrivez-vous</a>
            </div>
        </form>
    </div>
</body>
</html>