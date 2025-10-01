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
    <title>Dev Portfolio - Code & Créativité</title>
    
    <!-- Favicon adaptatif au thème -->
    <link rel="icon" href="assets/logo/logo-clair.png" media="(prefers-color-scheme: light)">
    <link rel="icon" href="assets/logo/logo-sombre.png" media="(prefers-color-scheme: dark)">
    <link rel="icon" href="assets/logo/logo-clair.png"> <!-- Fallback -->
    
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
</body>
</html>