<?php
require_once 'config/database.php';
require_once 'includes/functions.php';

// Récupérer les données pour la page d'accueil
$skills = getSkills($pdo, 8);
$recentProjects = getRecentProjects($pdo, 4);
?>
<!DOCTYPE html>
<html lang="fr">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Esteban DERENNE</title>
    
    <!-- SEO Meta Tags -->
    <meta name="description" content="Esteban DERENNE - Développeur Full Stack passionné. Portfolio professionnel présentant mes projets, compétences techniques et expériences en développement web.">
    <meta name="keywords" content="Esteban DERENNE, développeur, full stack, portfolio, web development, PHP, JavaScript, React, Node.js, développeur web">
    <meta name="author" content="Esteban DERENNE">
    <meta name="robots" content="index, follow">
    
    <!-- Open Graph Meta Tags -->
    <meta property="og:title" content="Esteban DERENNE - Développeur Full Stack">
    <meta property="og:description" content="Portfolio professionnel d'Esteban DERENNE, développeur Full Stack passionné par le développement web et les nouvelles technologies.">
    <meta property="og:type" content="website">
    <meta property="og:url" content="https://esteban-derenne.fr">
    <meta property="og:image" content="https://esteban-derenne.fr/assets/img/photo-portfolio.png">
    <meta property="og:site_name" content="Esteban DERENNE Portfolio">
    <meta property="og:locale" content="fr_FR">
    
    <!-- Twitter Card Meta Tags -->
    <meta name="twitter:card" content="summary_large_image">
    <meta name="twitter:title" content="Esteban DERENNE - Développeur Full Stack">
    <meta name="twitter:description" content="Portfolio professionnel d'Esteban DERENNE, développeur Full Stack passionné par le développement web.">
    <meta name="twitter:image" content="https://esteban-derenne.fr/assets/img/photo-portfolio.png">
    
    <!-- Favicon dynamique -->
    <link rel="icon" type="image/png" href="assets/logo/logo-sombre.png?v=5">
    
    <link href="https://fonts.googleapis.com/css2?family=JetBrains+Mono:wght@300;400;500;700&family=Inter:wght@300;400;500;600&display=swap" rel="stylesheet">
    <link href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/6.0.0/css/all.min.css" rel="stylesheet">
    <link rel="stylesheet" href="assets/css/style.css">
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
            <a href="/formations" class="nav-command" data-tooltip="Mon parcours de formation">Formations</a>
            <a href="/contact" class="nav-command" data-tooltip="Me contacter">Contact</a>
        </div>
    </div>

    <!-- Main Container -->
    <div class="main-container">
        <!-- Hero Section -->
        <section class="hero-section">
            <div class="hero-content">
                <div class="hero-text">
                    <h1 class="hero-title">Développeur Web Full-Stack</h1>
                    <p class="hero-subtitle">"Passionné par la création d'expériences digitales"</p>
                    <div style="margin: 1.5rem 0; color: var(--text-secondary); font-size: 1.1rem;">
                        <p>Je transforme vos idées en applications web modernes et performantes. 
                        Spécialisé dans le développement complet, du design à la mise en ligne.</p>
                    </div>
                    
                    <div class="hero-buttons">
                        <a href="/projets" class="btn btn-primary">
                            <i class="fas fa-eye"></i>
                            Découvrir mes réalisations
                        </a>
                        <a href="/contact" class="btn">
                            <i class="fas fa-envelope"></i>
                            Me contacter
                        </a>
                        <a href="assets/cv/cv.pdf" class="btn" target="_blank">
                            <i class="fas fa-download"></i>
                            Télécharger mon CV
                        </a>
                    </div>

                    
                </div>

                <!-- Profile Section -->
                <div class="profile-section">
                    <!-- Photo de profil -->
                    <img src="assets/img/photo-portfolio.png" alt="Photo de profil" class="profile-photo">
                    
                    
                </div>
            </div>
        </section>
    </div>

    <script src="assets/js/main.js"></script>
    <script src="assets/js/favicon-theme.js"></script>
</body>
</html>