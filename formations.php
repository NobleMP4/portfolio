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
    <title>Esteban DERENNE</title>
    
    <!-- SEO Meta Tags -->
    <meta name="description" content="Formations d'Esteban DERENNE - Développeur Full Stack. Découvrez mon parcours éducatif, mes diplômes et ma formation en développement web et technologies.">
    <meta name="keywords" content="Esteban DERENNE, formations, diplômes, parcours éducatif, développeur, full stack, études, formation web, technologies">
    <meta name="author" content="Esteban DERENNE">
    <meta name="robots" content="index, follow">
    
    <!-- Open Graph Meta Tags -->
    <meta property="og:title" content="Formations d'Esteban DERENNE - Parcours Éducatif">
    <meta property="og:description" content="Découvrez le parcours éducatif d'Esteban DERENNE, développeur Full Stack avec une formation solide en développement web.">
    <meta property="og:type" content="website">
    <meta property="og:url" content="https://esteban-derenne.fr/formations">
    <meta property="og:image" content="https://esteban-derenne.fr/assets/img/photo-portfolio.png">
    <meta property="og:site_name" content="Esteban DERENNE Portfolio">
    <meta property="og:locale" content="fr_FR">
    
    <!-- Twitter Card Meta Tags -->
    <meta name="twitter:card" content="summary_large_image">
    <meta name="twitter:title" content="Formations d'Esteban DERENNE - Parcours Éducatif">
    <meta name="twitter:description" content="Découvrez le parcours éducatif d'Esteban DERENNE, développeur Full Stack.">
    <meta name="twitter:image" content="https://esteban-derenne.fr/assets/img/photo-portfolio.png">
    
    <!-- Favicon dynamique -->
    <link rel="icon" type="image/png" href="assets/logo/logo-sombre.png?v=5">
    
    <link rel="stylesheet" href="assets/css/style.css">
    <link href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/6.0.0/css/all.min.css" rel="stylesheet">
</head>
<body>
    <!-- Code rain background -->
    <div class="code-rain" id="codeRain"></div>
    
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
            <a href="/" class="nav-command" data-tooltip="Retour à la page d'accueil">Accueil</a>
            <a href="/a-propos" class="nav-command" data-tooltip="Découvrir mon parcours">À propos</a>
            <a href="/projets" class="nav-command" data-tooltip="Voir mes réalisations">Mes projets</a>
            <a href="/experiences" class="nav-command" data-tooltip="Mon expérience professionnelle">Expériences</a>
            <a href="/formations" class="nav-command active" data-tooltip="Page actuelle">Formations</a>
            <a href="/contact" class="nav-command" data-tooltip="Me contacter">Contact</a>
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
                    <div class="formation-header">
                        <?php if (!empty($formation['logo'])): ?>
                        <div class="formation-logo-full">
                            <img src="<?php echo htmlspecialchars($formation['logo']); ?>" alt="<?php echo htmlspecialchars($formation['school']); ?>" loading="lazy">
                        </div>
                        <?php endif; ?>
                        <div class="formation-title-section">
                            <h3><?php echo htmlspecialchars($formation['title']); ?></h3>
                            <h4><?php echo htmlspecialchars($formation['school']); ?></h4>
                        </div>
                        <div class="formation-period">
                            <?php echo formatPeriod($formation['start_date'], $formation['end_date'], $formation['current_formation']); ?>
                        </div>
                    </div>
                    
                    <div class="formation-details">
                        <?php if (!empty($formation['location'])): ?>
                        <div class="detail-item">
                            <i class="fas fa-map-marker-alt"></i>
                            <span><?php echo htmlspecialchars($formation['location']); ?></span>
                        </div>
                        <?php endif; ?>
                        
                        <?php if (!empty($formation['diploma'])): ?>
                        <div class="detail-item">
                            <i class="fas fa-certificate"></i>
                            <span><?php echo htmlspecialchars($formation['diploma']); ?></span>
                        </div>
                        <?php endif; ?>
                        
                        <?php if ($formation['current_formation']): ?>
                        <div class="current-badge">
                            <i class="fas fa-clock"></i> Formation en cours
                        </div>
                        <?php endif; ?>
                    </div>
                    
                    <?php if (!empty($formation['description'])): ?>
                    <div class="formation-description">
                        <p><?php echo nl2br(htmlspecialchars($formation['description'])); ?></p>
                    </div>
                    <?php endif; ?>
                </div>
                <?php endforeach; ?>
            </div>
        </div>
    </section>

    </div>

    <script src="assets/js/main.js"></script>
    <script src="assets/js/favicon-theme.js"></script>
</body>
</html>
