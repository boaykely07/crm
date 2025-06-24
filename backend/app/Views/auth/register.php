
<!DOCTYPE html>
<html lang="fr">
<head>
    <meta charset="UTF-8">
    <title>Inscription | CRM</title>
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
            background-color: var(--success);
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

        .form-row {
            display: flex;
            gap: 16px;
            margin-bottom: 0;
        }

        .form-col {
            flex: 1;
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

            .form-row {
                flex-direction: column;
                gap: 0;
            }
        }
    </style>
</head>
<body>
    <!-- Page d'inscription -->
    <div class="auth-container">
        <div class="auth-header">
            <div class="auth-logo">
                <svg xmlns="http://www.w3.org/2000/svg" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2" stroke-linecap="round" stroke-linejoin="round">
                    <path d="M16 21v-2a4 4 0 0 0-4-4H5a4 4 0 0 0-4 4v2"></path>
                    <circle cx="8.5" cy="7" r="4"></circle>
                    <line x1="20" y1="8" x2="20" y2="14"></line>
                    <line x1="23" y1="11" x2="17" y2="11"></line>
                </svg>
            </div>
            <h1 class="auth-title">Créer un compte</h1>
            <p class="auth-subtitle">Inscrivez-vous pour gérer vos clients</p>
        </div>

        <form class="auth-form" method="post" action="<?= site_url('auth/register') ?>">
            <div class="form-row">
                <div class="form-group form-col">
                    <label class="form-label">Nom</label>
                    <input type="text" name="nom" required class="form-control">
                </div>
                <div class="form-group form-col">
                    <label class="form-label">Prénom</label>
                    <input type="text" name="prenom" required class="form-control">
                </div>
            </div>
            <div class="form-group">
                <label class="form-label">Email</label>
                <input type="email" name="email" required class="form-control">
            </div>
            <div class="form-group">
                <label class="form-label">Mot de passe</label>
                <input type="password" name="mot_de_passe" required class="form-control">
            </div>
            <div class="form-group">
                <label class="form-label">Département</label>
                <select name="id_departement" required class="form-select">
                    <option value="">-- Sélectionnez un département --</option>
                    <?php if (isset($departements) && is_array($departements)): ?>
                        <?php foreach ($departements as $dep): ?>
                            <option value="<?= esc($dep['id']) ?>">
                                <?= esc($dep['nom']) ?>
                            </option>
                        <?php endforeach; ?>
                    <?php endif; ?>
                </select>
            </div>
            <button type="submit" class="btn btn-success">S'inscrire</button>
            
            <div class="auth-footer">
                <span class="auth-footer-text">Déjà un compte ?</span>
                <a href="<?= site_url('login') ?>" class="auth-link">Connectez-vous</a>
            </div>
        </form>
    </div>
</body>
</html>