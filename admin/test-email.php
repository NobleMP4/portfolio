<?php
session_start();
require_once '../config/database.php';
require_once '../config/email.php';
require_once '../includes/functions.php';
require_once '../includes/EmailSender.php';

requireLogin();

$test_result = '';
$test_sent = false;

if ($_POST && isset($_POST['test_email'])) {
    $test_email = sanitize_input($_POST['test_email']);
    
    if (validate_email($test_email)) {
        $emailSender = new EmailSender();
        
        $subject = "Test d'envoi d'email - Portfolio";
        $message = "Ceci est un test d'envoi d'email depuis votre portfolio.\n\nSi vous recevez ce message, la configuration fonctionne correctement !\n\nDate du test : " . date('d/m/Y à H:i:s');
        
        $email_sent = $emailSender->sendContactEmail($test_email, $subject, $message, 'Test Portfolio', 'noreply@portfolio.com');
        
        if ($email_sent) {
            $test_result = 'success';
        } else {
            $test_result = 'error';
        }
        $test_sent = true;
    } else {
        $test_result = 'invalid_email';
    }
}

$emailSender = new EmailSender();
$config_test = $emailSender->testConfiguration();
?>
<!DOCTYPE html>
<html lang="fr">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Test Email - Administration</title>
    <link rel="stylesheet" href="../assets/css/admin.css">
    <link rel="stylesheet" href="../assets/css/style.css">
    <link href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/6.0.0/css/all.min.css" rel="stylesheet">
</head>
<body>
    
    <div class="admin-layout">
        <!-- Sidebar -->
        <aside class="admin-sidebar">
            <div class="admin-logo">
                <h2><i class="fas fa-shield-alt"></i> Administration</h2>
            </div>
            <nav>
                <ul class="admin-nav">
                    <li><a href="index.php"><i class="fas fa-tachometer-alt"></i> Dashboard</a></li>
                    <li><a href="projects.php"><i class="fas fa-folder"></i> Projets</a></li>
                    <li><a href="experiences.php"><i class="fas fa-briefcase"></i> Expériences</a></li>
                    <li><a href="formations.php"><i class="fas fa-graduation-cap"></i> Formations</a></li>
                    <li><a href="skills.php"><i class="fas fa-cogs"></i> Compétences</a></li>
                    <li><a href="messages.php"><i class="fas fa-envelope"></i> Messages</a></li>
                    <li><a href="settings.php"><i class="fas fa-sliders-h"></i> Paramètres</a></li>
                </ul>
            </nav>
        </aside>

        <!-- Main Content -->
        <main class="admin-main">
            <!-- Header -->
            <header class="admin-header">
                <div>
                    <h1>Test de Configuration Email</h1>
                </div>
                <div class="admin-user">
                    <span>Bonjour, <?php echo htmlspecialchars($_SESSION['admin_username']); ?></span>
                    <a href="logout.php" class="btn btn-sm btn-secondary">
                        <i class="fas fa-sign-out-alt"></i> Déconnexion
                    </a>
                </div>
            </header>

            <!-- Content -->
            <div class="admin-content">
                
                <?php if ($test_sent): ?>
                <div class="alert <?php echo $test_result === 'success' ? 'alert-success' : 'alert-error'; ?>">
                    <i class="fas <?php echo $test_result === 'success' ? 'fa-check-circle' : 'fa-exclamation-circle'; ?>"></i>
                    <?php if ($test_result === 'success'): ?>
                        Email de test envoyé avec succès ! Vérifiez votre boîte de réception.
                    <?php elseif ($test_result === 'invalid_email'): ?>
                        Adresse email invalide.
                    <?php else: ?>
                        Erreur lors de l'envoi de l'email de test. Vérifiez votre configuration.
                    <?php endif; ?>
                </div>
                <?php endif; ?>

                <!-- Configuration actuelle -->
                <div class="card">
                    <div class="card-header">
                        <h3>Configuration Actuelle</h3>
                    </div>
                    <div class="card-body">
                        <div class="stats-grid" style="grid-template-columns: repeat(auto-fit, minmax(200px, 1fr));">
                            <div class="stat-card">
                                <div class="stat-icon <?php echo $config_test['php_mail_available'] ? 'success' : 'error'; ?>">
                                    <i class="fas <?php echo $config_test['php_mail_available'] ? 'fa-check' : 'fa-times'; ?>"></i>
                                </div>
                                <div class="stat-label">Fonction mail() PHP</div>
                                <div class="stat-number" style="font-size: 1rem;">
                                    <?php echo $config_test['php_mail_available'] ? 'Disponible' : 'Indisponible'; ?>
                                </div>
                            </div>
                            
                            <div class="stat-card">
                                <div class="stat-icon primary">
                                    <i class="fas fa-cog"></i>
                                </div>
                                <div class="stat-label">Méthode d'envoi</div>
                                <div class="stat-number" style="font-size: 1rem;">
                                    <?php echo $config_test['current_method']; ?>
                                </div>
                            </div>
                            
                            <div class="stat-card">
                                <div class="stat-icon primary">
                                    <i class="fas fa-envelope"></i>
                                </div>
                                <div class="stat-label">Email admin</div>
                                <div class="stat-number" style="font-size: 0.9rem;">
                                    <?php echo htmlspecialchars(ADMIN_EMAIL); ?>
                                </div>
                            </div>
                        </div>
                        
                        <?php if (!empty($config_test['recommendations'])): ?>
                        <div style="margin-top: 2rem; padding: 1rem; background: rgba(218, 133, 72, 0.1); border: 1px solid var(--warning); border-radius: 8px;">
                            <h4 style="color: var(--warning); margin-bottom: 1rem;">
                                <i class="fas fa-exclamation-triangle"></i> Recommandations
                            </h4>
                            <ul style="margin: 0; padding-left: 1.5rem; color: var(--text-secondary);">
                                <?php foreach ($config_test['recommendations'] as $recommendation): ?>
                                <li style="margin-bottom: 0.5rem;"><?php echo htmlspecialchars($recommendation); ?></li>
                                <?php endforeach; ?>
                            </ul>
                        </div>
                        <?php endif; ?>
                    </div>
                </div>

                <!-- Test d'envoi -->
                <div class="card">
                    <div class="card-header">
                        <h3>Envoyer un Email de Test</h3>
                    </div>
                    <div class="card-body">
                        <form method="POST">
                            <div class="form-group">
                                <label for="test_email">Adresse email de test *</label>
                                <input type="email" id="test_email" name="test_email" required placeholder="votre-email@exemple.com" value="<?php echo htmlspecialchars(ADMIN_EMAIL); ?>">
                                <small style="color: var(--text-secondary); font-size: 0.85rem; margin-top: 0.5rem; display: block;">
                                    Entrez l'adresse email où vous voulez recevoir l'email de test.
                                </small>
                            </div>
                            
                            <div style="margin-top: 2rem;">
                                <button type="submit" name="test_email" class="btn btn-primary">
                                    <i class="fas fa-paper-plane"></i>
                                    Envoyer l'email de test
                                </button>
                                <a href="settings.php" class="btn btn-secondary">
                                    <i class="fas fa-cog"></i>
                                    Modifier la configuration
                                </a>
                            </div>
                        </form>
                    </div>
                </div>

                <!-- Instructions -->
                <div class="card">
                    <div class="card-header">
                        <h3>Instructions de Configuration</h3>
                    </div>
                    <div class="card-body">
                        <div style="color: var(--text-secondary); line-height: 1.6;">
                            <h4 style="color: var(--text-primary); margin-bottom: 1rem;">
                                <i class="fas fa-info-circle" style="color: var(--accent-blue);"></i>
                                Configuration recommandée
                            </h4>
                            
                            <ol style="padding-left: 1.5rem;">
                                <li style="margin-bottom: 1rem;">
                                    <strong>Modifiez le fichier</strong> <code style="background: var(--bg-primary); padding: 2px 6px; border-radius: 4px; font-family: 'JetBrains Mono', monospace;">config/email.php</code>
                                </li>
                                <li style="margin-bottom: 1rem;">
                                    <strong>Changez ADMIN_EMAIL</strong> par votre vraie adresse email
                                </li>
                                <li style="margin-bottom: 1rem;">
                                    <strong>Testez l'envoi</strong> avec le formulaire ci-dessus
                                </li>
                                <li style="margin-bottom: 1rem;">
                                    <strong>Si ça ne marche pas</strong> : configurez SMTP dans le même fichier
                                </li>
                            </ol>
                            
                            <div style="margin-top: 2rem; padding: 1rem; background: var(--bg-primary); border: 1px solid var(--border); border-radius: 8px;">
                                <h5 style="color: var(--text-primary); margin-bottom: 0.5rem;">
                                    <i class="fas fa-lightbulb" style="color: var(--warning);"></i>
                                    Conseil pour MAMP/Local
                                </h5>
                                <p style="margin: 0; font-size: 0.9rem;">
                                    En local, l'envoi d'email peut ne pas fonctionner. Utilisez un service comme 
                                    <strong>Mailtrap</strong> ou <strong>MailHog</strong> pour tester.
                                </p>
                            </div>
                        </div>
                    </div>
                </div>
            </div>
        </main>
    </div>
</body>
</html>
