<?php
require_once 'config/database.php';
require_once 'includes/functions.php';

$experiences = getExperiences($pdo);
?>
<!DOCTYPE html>
<html lang="fr">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Expériences - Portfolio</title>
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
            <a href="experiences.php" class="nav-command active" data-tooltip="Page actuelle">Expériences</a>
            <a href="formations.php" class="nav-command" data-tooltip="Mon parcours de formation">Formations</a>
            <a href="contact.php" class="nav-command" data-tooltip="Me contacter">Contact</a>
        </div>
    </div>

    <!-- Main Container -->
    <div class="main-container">
        <!-- Experiences Header -->
        <section class="experiences-header" style="text-align: center; padding: 4rem 0 2rem;">
            <h1 style="font-family: 'JetBrains Mono', monospace; color: var(--text-primary); margin-bottom: 1rem;">
                <span style="color: var(--text-muted);">// </span>Mon Parcours Professionnel
            </h1>
            <p style="color: var(--text-secondary); font-size: 1.1rem; max-width: 600px; margin: 0 auto;">
                Découvrez mon évolution professionnelle et les expériences qui ont façonné mes compétences.
            </p>
        </section>

        <!-- Timeline -->
        <section class="timeline-section" style="padding: 2rem 0;">
            <div class="container">
            <div class="timeline">
                <?php foreach ($experiences as $index => $experience): ?>
                <div class="timeline-item <?php echo $index % 2 == 0 ? 'left' : 'right'; ?>">
                    <div class="timeline-content">
                        <div class="timeline-date">
                            <?php echo formatPeriod($experience['start_date'], $experience['end_date'], $experience['current_position']); ?>
                        </div>
                        <div class="timeline-card">
                            <h3><?php echo htmlspecialchars($experience['title']); ?></h3>
                            <h4>
                                <i class="fas fa-building"></i>
                                <?php echo htmlspecialchars($experience['company']); ?>
                                <?php if (!empty($experience['location'])): ?>
                                <span class="location">
                                    <i class="fas fa-map-marker-alt"></i>
                                    <?php echo htmlspecialchars($experience['location']); ?>
                                </span>
                                <?php endif; ?>
                            </h4>
                            <?php if ($experience['current_position']): ?>
                            <div class="current-badge">
                                <i class="fas fa-clock"></i> Poste actuel
                            </div>
                            <?php endif; ?>
                            <p><?php echo nl2br(htmlspecialchars($experience['description'])); ?></p>
                            <?php if (!empty($experience['technologies'])): ?>
                            <div class="timeline-technologies">
                                <strong>Technologies utilisées :</strong>
                                <div class="tech-tags">
                                    <?php 
                                    $technologies = explode(', ', $experience['technologies']);
                                    foreach ($technologies as $tech): 
                                    ?>
                                    <span class="tech-tag"><?php echo htmlspecialchars($tech); ?></span>
                                    <?php endforeach; ?>
                                </div>
                            </div>
                            <?php endif; ?>
                        </div>
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
