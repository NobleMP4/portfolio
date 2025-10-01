<?php
require_once 'config/database.php';
require_once 'includes/functions.php';

$formations = getFormations($pdo);
?>
<!DOCTYPE html>
<html lang="fr">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Formations - Portfolio</title>
    
    <!-- Favicon adaptatif au thème -->
    <link rel="icon" href="assets/logo/logo-clair.png" media="(prefers-color-scheme: light)">
    <link rel="icon" href="assets/logo/logo-sombre.png" media="(prefers-color-scheme: dark)">
    <link rel="icon" href="assets/logo/logo-clair.png"> <!-- Fallback -->
    
    <link rel="stylesheet" href="assets/css/style.css">
    <link href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/6.0.0/css/all.min.css" rel="stylesheet">
</head>
<body>
    
    <!-- Terminal Navigation -->
    <div class="terminal-nav">
        <div class="terminal-header">
            <div class="terminal-dots">
                <div class="dot red"></div>
                <div class="dot yellow"></div>
                <div class="dot green"></div>
            </div>
            <div class="terminal-title">Navigation</div>
        </div>
        <div class="terminal-body">
            <a href="index.php" class="nav-command" data-tooltip="Retour à la page d'accueil">Accueil</a>
            <a href="about.php" class="nav-command" data-tooltip="Découvrir mon parcours">À propos</a>
            <a href="portfolio.php" class="nav-command" data-tooltip="Voir mes réalisations">Mes projets</a>
            <a href="experiences.php" class="nav-command" data-tooltip="Mon expérience professionnelle">Expériences</a>
            <a href="formations.php" class="nav-command active" data-tooltip="Page actuelle">Formations</a>
            <a href="contact.php" class="nav-command" data-tooltip="Me contacter">Contact</a>
        </div>
    </div>

    <!-- Main Container -->
    <div class="main-container">
        <!-- Formations Header -->
        <section class="formations-header" style="text-align: center; padding: 4rem 0 2rem;">
            <h1 style="font-family: 'JetBrains Mono', monospace; color: var(--text-primary); margin-bottom: 1rem;">
                <span style="color: var(--text-muted);">// </span>Mon Parcours de Formation
            </h1>
            <p style="color: var(--text-secondary); font-size: 1.1rem; max-width: 600px; margin: 0 auto;">
                Les formations qui ont construit mes bases académiques et techniques.
            </p>
        </section>

    <!-- Education Timeline -->
    <section class="education-section">
        <div class="container">
            <div class="education-grid">
                <?php foreach ($formations as $formation): ?>
                <div class="education-card">
                    <div class="education-header">
                        <div class="education-icon">
                            <i class="fas fa-graduation-cap"></i>
                        </div>
                        <div class="education-period">
                            <?php echo formatPeriod($formation['start_date'], $formation['end_date'], $formation['current_formation']); ?>
                        </div>
                    </div>
                    <div class="education-content">
                        <h3><?php echo htmlspecialchars($formation['title']); ?></h3>
                        <h4>
                            <i class="fas fa-university"></i>
                            <?php echo htmlspecialchars($formation['school']); ?>
                            <?php if (!empty($formation['location'])): ?>
                            <span class="location">
                                <i class="fas fa-map-marker-alt"></i>
                                <?php echo htmlspecialchars($formation['location']); ?>
                            </span>
                            <?php endif; ?>
                        </h4>
                        <?php if (!empty($formation['diploma'])): ?>
                        <div class="diploma">
                            <i class="fas fa-certificate"></i>
                            <strong>Diplôme :</strong> <?php echo htmlspecialchars($formation['diploma']); ?>
                        </div>
                        <?php endif; ?>
                        <?php if ($formation['current_formation']): ?>
                        <div class="current-badge">
                            <i class="fas fa-clock"></i> Formation en cours
                        </div>
                        <?php endif; ?>
                        <?php if (!empty($formation['description'])): ?>
                        <p><?php echo nl2br(htmlspecialchars($formation['description'])); ?></p>
                        <?php endif; ?>
                    </div>
                </div>
                <?php endforeach; ?>
            </div>
        </div>
    </section>

    </div>

    <script src="assets/js/main.js"></script>
</body>
</html>
