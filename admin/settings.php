<?php
session_start();
require_once '../config/database.php';
require_once '../includes/functions.php';

requireLogin();

$success_message = '';
$error_message = '';

// Traitement des actions
if ($_POST) {
    if (isset($_POST['update_profile'])) {
        $name = sanitize_input($_POST['name']);
        $title = sanitize_input($_POST['title']);
        $email = sanitize_input($_POST['email']);
        $phone = sanitize_input($_POST['phone']);
        $location = sanitize_input($_POST['location']);
        $bio = sanitize_input($_POST['bio']);
        $linkedin = sanitize_input($_POST['linkedin']);
        $github = sanitize_input($_POST['github']);
        $website = sanitize_input($_POST['website']);
        
        try {
            // Vérifier si un profil existe
            $stmt = $pdo->query("SELECT COUNT(*) FROM profile");
            $profileExists = $stmt->fetchColumn() > 0;
            
            if ($profileExists) {
                $sql = "UPDATE profile SET name = ?, title = ?, email = ?, phone = ?, location = ?, bio = ?, linkedin = ?, github = ?, website = ? WHERE id = 1";
                $stmt = $pdo->prepare($sql);
                $stmt->execute([$name, $title, $email, $phone, $location, $bio, $linkedin, $github, $website]);
            } else {
                $sql = "INSERT INTO profile (name, title, email, phone, location, bio, linkedin, github, website) VALUES (?, ?, ?, ?, ?, ?, ?, ?, ?)";
                $stmt = $pdo->prepare($sql);
                $stmt->execute([$name, $title, $email, $phone, $location, $bio, $linkedin, $github, $website]);
            }
            
            $success_message = 'Profil mis à jour avec succès !';
        } catch (PDOException $e) {
            $error_message = 'Erreur lors de la mise à jour du profil.';
        }
    }
    
    if (isset($_POST['change_password'])) {
        $current_password = $_POST['current_password'];
        $new_password = $_POST['new_password'];
        $confirm_password = $_POST['confirm_password'];
        
        if ($new_password !== $confirm_password) {
            $error_message = 'Les nouveaux mots de passe ne correspondent pas.';
        } elseif (strlen($new_password) < 6) {
            $error_message = 'Le mot de passe doit contenir au moins 6 caractères.';
        } else {
            try {
                // Vérifier le mot de passe actuel
                $stmt = $pdo->prepare("SELECT password FROM users WHERE username = ?");
                $stmt->execute([$_SESSION['admin_username']]);
                $user = $stmt->fetch();
                
                if ($user && password_verify($current_password, $user['password'])) {
                    $hashed_password = password_hash($new_password, PASSWORD_DEFAULT);
                    $stmt = $pdo->prepare("UPDATE users SET password = ? WHERE username = ?");
                    $stmt->execute([$hashed_password, $_SESSION['admin_username']]);
                    
                    $success_message = 'Mot de passe modifié avec succès !';
                } else {
                    $error_message = 'Mot de passe actuel incorrect.';
                }
            } catch (PDOException $e) {
                $error_message = 'Erreur lors de la modification du mot de passe.';
            }
        }
    }
    
    if (isset($_POST['update_seo'])) {
        $site_title = sanitize_input($_POST['site_title']);
        $site_description = sanitize_input($_POST['site_description']);
        $site_keywords = sanitize_input($_POST['site_keywords']);
        $google_analytics = sanitize_input($_POST['google_analytics']);
        
        try {
            // Vérifier si des paramètres SEO existent
            $stmt = $pdo->query("SELECT COUNT(*) FROM seo_settings");
            $seoExists = $stmt->fetchColumn() > 0;
            
            if ($seoExists) {
                $sql = "UPDATE seo_settings SET site_title = ?, site_description = ?, site_keywords = ?, google_analytics = ? WHERE id = 1";
                $stmt = $pdo->prepare($sql);
                $stmt->execute([$site_title, $site_description, $site_keywords, $google_analytics]);
            } else {
                $sql = "INSERT INTO seo_settings (site_title, site_description, site_keywords, google_analytics) VALUES (?, ?, ?, ?)";
                $stmt = $pdo->prepare($sql);
                $stmt->execute([$site_title, $site_description, $site_keywords, $google_analytics]);
            }
            
            $success_message = 'Paramètres SEO mis à jour avec succès !';
        } catch (PDOException $e) {
            $error_message = 'Erreur lors de la mise à jour des paramètres SEO.';
        }
    }
}

// Récupérer les données
try {
    $profile = $pdo->query("SELECT * FROM profile LIMIT 1")->fetch();
} catch (PDOException $e) {
    $profile = null;
}

try {
    $seo = $pdo->query("SELECT * FROM seo_settings LIMIT 1")->fetch();
} catch (PDOException $e) {
    $seo = null;
}
?>
<!DOCTYPE html>
<html lang="fr">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Administration - Paramètres</title>
    
    <!-- Favicon adaptatif au thème -->
    <link rel="icon" href="../assets/logo/logo-clair.png" media="(prefers-color-scheme: light)">
    <link rel="icon" href="../assets/logo/logo-sombre.png" media="(prefers-color-scheme: dark)">
    <link rel="icon" href="../assets/logo/logo-clair.png"> <!-- Fallback -->
    
    
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
                    <li><a href="settings.php" class="active"><i class="fas fa-sliders-h"></i> Paramètres</a></li>
                </ul>
            </nav>
        </aside>

        <!-- Main Content -->
        <main class="admin-main">
            <!-- Header -->
            <header class="admin-header">
                <div>
                    <h1>Paramètres du Site</h1>
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
                <?php if ($success_message): ?>
                <div class="alert alert-success">
                    <i class="fas fa-check-circle"></i>
                    <?php echo $success_message; ?>
                </div>
                <?php endif; ?>
                
                <?php if ($error_message): ?>
                <div class="alert alert-error">
                    <i class="fas fa-exclamation-circle"></i>
                    <?php echo $error_message; ?>
                </div>
                <?php endif; ?>

                <!-- Profil Personnel -->
                <div class="card">
                    <div class="card-header">
                        <h3>Informations Personnelles</h3>
                    </div>
                    <div class="card-body">
                        <form method="POST">
                            <div class="form-row">
                                <div class="form-group">
                                    <label for="name">Nom complet *</label>
                                    <input type="text" id="name" name="name" required value="<?php echo $profile ? htmlspecialchars($profile['name']) : ''; ?>">
                                </div>
                                <div class="form-group">
                                    <label for="title">Titre professionnel *</label>
                                    <input type="text" id="title" name="title" required placeholder="ex: Développeur Web Full-Stack" value="<?php echo $profile ? htmlspecialchars($profile['title']) : ''; ?>">
                                </div>
                            </div>
                            
                            <div class="form-row">
                                <div class="form-group">
                                    <label for="email">Email *</label>
                                    <input type="email" id="email" name="email" required value="<?php echo $profile ? htmlspecialchars($profile['email']) : ''; ?>">
                                </div>
                                <div class="form-group">
                                    <label for="phone">Téléphone</label>
                                    <input type="tel" id="phone" name="phone" value="<?php echo $profile ? htmlspecialchars($profile['phone']) : ''; ?>">
                                </div>
                            </div>
                            
                            <div class="form-group">
                                <label for="location">Localisation</label>
                                <input type="text" id="location" name="location" placeholder="ex: Paris, France" value="<?php echo $profile ? htmlspecialchars($profile['location']) : ''; ?>">
                            </div>
                            
                            <div class="form-group">
                                <label for="bio">Biographie</label>
                                <textarea id="bio" name="bio" rows="4" placeholder="Présentez-vous en quelques lignes..."><?php echo $profile ? htmlspecialchars($profile['bio']) : ''; ?></textarea>
                            </div>
                            
                            <h4 style="margin: 2rem 0 1rem; color: var(--text-primary); font-family: 'JetBrains Mono', monospace;">
                                <span style="color: var(--text-muted);">// </span>Réseaux Sociaux
                            </h4>
                            
                            <div class="form-row">
                                <div class="form-group">
                                    <label for="linkedin">LinkedIn</label>
                                    <input type="url" id="linkedin" name="linkedin" placeholder="https://linkedin.com/in/..." value="<?php echo $profile ? htmlspecialchars($profile['linkedin']) : ''; ?>">
                                </div>
                                <div class="form-group">
                                    <label for="github">GitHub</label>
                                    <input type="url" id="github" name="github" placeholder="https://github.com/..." value="<?php echo $profile ? htmlspecialchars($profile['github']) : ''; ?>">
                                </div>
                            </div>
                            
                            <div class="form-group">
                                <label for="website">Site Web</label>
                                <input type="url" id="website" name="website" placeholder="https://..." value="<?php echo $profile ? htmlspecialchars($profile['website']) : ''; ?>">
                            </div>
                            
                            <div style="margin-top: 2rem;">
                                <button type="submit" name="update_profile" class="btn btn-primary">
                                    <i class="fas fa-save"></i>
                                    Mettre à jour le profil
                                </button>
                            </div>
                        </form>
                    </div>
                </div>

                <!-- Changement de mot de passe -->
                <div class="card">
                    <div class="card-header">
                        <h3>Sécurité</h3>
                    </div>
                    <div class="card-body">
                        <form method="POST">
                            <div class="form-group">
                                <label for="current_password">Mot de passe actuel *</label>
                                <input type="password" id="current_password" name="current_password" required>
                            </div>
                            
                            <div class="form-row">
                                <div class="form-group">
                                    <label for="new_password">Nouveau mot de passe *</label>
                                    <input type="password" id="new_password" name="new_password" required minlength="6">
                                </div>
                                <div class="form-group">
                                    <label for="confirm_password">Confirmer le mot de passe *</label>
                                    <input type="password" id="confirm_password" name="confirm_password" required minlength="6">
                                </div>
                            </div>
                            
                            <div style="margin-top: 2rem;">
                                <button type="submit" name="change_password" class="btn btn-warning">
                                    <i class="fas fa-key"></i>
                                    Changer le mot de passe
                                </button>
                            </div>
                        </form>
                    </div>
                </div>

                <!-- Paramètres SEO -->
                <div class="card">
                    <div class="card-header">
                        <h3>Référencement (SEO)</h3>
                    </div>
                    <div class="card-body">
                        <form method="POST">
                            <div class="form-group">
                                <label for="site_title">Titre du site</label>
                                <input type="text" id="site_title" name="site_title" placeholder="Portfolio - Développeur Web" value="<?php echo $seo ? htmlspecialchars($seo['site_title']) : ''; ?>">
                            </div>
                            
                            <div class="form-group">
                                <label for="site_description">Description du site</label>
                                <textarea id="site_description" name="site_description" rows="3" placeholder="Description pour les moteurs de recherche..."><?php echo $seo ? htmlspecialchars($seo['site_description']) : ''; ?></textarea>
                            </div>
                            
                            <div class="form-group">
                                <label for="site_keywords">Mots-clés</label>
                                <input type="text" id="site_keywords" name="site_keywords" placeholder="développeur web, php, javascript, portfolio" value="<?php echo $seo ? htmlspecialchars($seo['site_keywords']) : ''; ?>">
                            </div>
                            
                            <div class="form-group">
                                <label for="google_analytics">Google Analytics ID</label>
                                <input type="text" id="google_analytics" name="google_analytics" placeholder="G-XXXXXXXXXX" value="<?php echo $seo ? htmlspecialchars($seo['google_analytics']) : ''; ?>">
                            </div>
                            
                            <div style="margin-top: 2rem;">
                                <button type="submit" name="update_seo" class="btn btn-success">
                                    <i class="fas fa-search"></i>
                                    Mettre à jour le SEO
                                </button>
                            </div>
                        </form>
                    </div>
                </div>
            </div>
        </main>
    </div>

    <script>
        // Validation du mot de passe
        document.getElementById('confirm_password').addEventListener('input', function() {
            const newPassword = document.getElementById('new_password').value;
            const confirmPassword = this.value;
            
            if (newPassword !== confirmPassword) {
                this.style.borderColor = 'var(--error)';
            } else {
                this.style.borderColor = 'var(--success)';
            }
        });
    </script>

    <!-- PWA Installation Script -->
    <script src="pwa-install.js"></script>
</body>
</html>
