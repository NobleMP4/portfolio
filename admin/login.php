<?php
session_start();
require_once '../config/database.php';
require_once '../includes/functions.php';

// Rediriger si déjà connecté
if (isLoggedIn()) {
    header('Location: index.php');
    exit();
}

$error_message = '';

// Traitement du formulaire de connexion
if ($_POST && isset($_POST['login'])) {
    if (!verify_csrf_token($_POST['csrf_token'])) {
        $error_message = 'Token de sécurité invalide.';
    } else {
        $username = sanitize_input($_POST['username']);
        $password = $_POST['password'];
        
        if (empty($username) || empty($password)) {
            $error_message = 'Veuillez remplir tous les champs.';
        } else {
            try {
                $sql = "SELECT id, username, password FROM users WHERE username = ?";
                $stmt = $pdo->prepare($sql);
                $stmt->execute([$username]);
                $user = $stmt->fetch();
                
                if ($user && password_verify($password, $user['password'])) {
                    $_SESSION['admin_logged_in'] = true;
                    $_SESSION['admin_id'] = $user['id'];
                    $_SESSION['admin_username'] = $user['username'];
                    
                    header('Location: index.php');
                    exit();
                } else {
                    $error_message = 'Nom d\'utilisateur ou mot de passe incorrect.';
                }
            } catch (PDOException $e) {
                $error_message = 'Erreur de connexion à la base de données.';
            }
        }
    }
}

$csrf_token = generate_csrf_token();
?>
<!DOCTYPE html>
<html lang="fr">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Esteban DERENNE - Administration</title>
    
    <!-- Favicon dynamique -->
    <link rel="icon" type="image/png" href="../assets/logo/logo-sombre.png?v=5">
    
    <!-- PWA Meta Tags -->
    <link rel="manifest" href="manifest.json">
    <meta name="theme-color" content="#58a6ff">
    <meta name="apple-mobile-web-app-capable" content="yes">
    <meta name="apple-mobile-web-app-status-bar-style" content="black-translucent">
    <meta name="apple-mobile-web-app-title" content="Portfolio Admin">
    <link rel="apple-touch-icon" href="../assets/logo/logo-clair.png">
    <link rel="apple-touch-startup-image" href="../assets/logo/logo-clair.png">
    <meta name="msapplication-TileColor" content="#0d1117">
    <meta name="msapplication-TileImage" content="../assets/logo/logo-clair.png">
    <meta name="application-name" content="Portfolio Admin">
    
    <link rel="stylesheet" href="../assets/css/admin.css">
    <link rel="stylesheet" href="../assets/css/style.css">
    <link href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/6.0.0/css/all.min.css" rel="stylesheet">
</head>
<body class="login-page">
    <div class="login-container">
        <div class="login-card">
            <div class="login-header">
                <h1><i class="fas fa-shield-alt"></i> Administration</h1>
                <p>Connectez-vous pour accéder au panel d'administration</p>
            </div>
            
            <?php if ($error_message): ?>
            <div class="alert alert-error">
                <i class="fas fa-exclamation-circle"></i>
                <?php echo $error_message; ?>
            </div>
            <?php endif; ?>
            
            <form method="POST" class="login-form">
                <input type="hidden" name="csrf_token" value="<?php echo $csrf_token; ?>">
                
                <div class="form-group">
                    <label for="username">
                        <i class="fas fa-user"></i>
                        Nom d'utilisateur
                    </label>
                    <input type="text" id="username" name="username" required autofocus>
                </div>
                
                <div class="form-group">
                    <label for="password">
                        <i class="fas fa-lock"></i>
                        Mot de passe
                    </label>
                    <input type="password" id="password" name="password" required>
                </div>
                
                <button type="submit" name="login" class="btn btn-primary btn-full">
                    <i class="fas fa-sign-in-alt"></i>
                    Se connecter
                </button>
            </form>
            
            <div class="login-footer">
                <p><a href="../index.php"><i class="fas fa-arrow-left"></i> Retour au site</a></p>
            </div>
        </div>
    </div>
    
    <script>
    // Focus automatique et validation simple
    document.addEventListener('DOMContentLoaded', function() {
        const form = document.querySelector('.login-form');
        const inputs = form.querySelectorAll('input');
        
        // Validation en temps réel
        inputs.forEach(input => {
            input.addEventListener('input', function() {
                if (this.value.trim()) {
                    this.classList.add('valid');
                } else {
                    this.classList.remove('valid');
                }
            });
        });
        
        // Prévenir la soumission avec des champs vides
        form.addEventListener('submit', function(e) {
            let isValid = true;
            inputs.forEach(input => {
                if (!input.value.trim()) {
                    input.classList.add('error');
                    isValid = false;
                } else {
                    input.classList.remove('error');
                }
            });
            
            if (!isValid) {
                e.preventDefault();
                alert('Veuillez remplir tous les champs.');
            }
        });
    });
    </script>
    
    <!-- PWA Installation Script -->
    <script src="pwa-install.js"></script>
    <script src="favicon-theme.js"></script>
</body>
</html>
