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
    <title>Esteban DERENNE</title>
    
    <!-- SEO Meta Tags -->
    <meta name="description" content="Expériences professionnelles d'Esteban DERENNE - Développeur Full Stack. Découvrez mon parcours professionnel et mes réalisations dans le développement web.">
    <meta name="keywords" content="Esteban DERENNE, expériences, parcours professionnel, développeur, full stack, carrière, développement web, emploi">
    <meta name="author" content="Esteban DERENNE">
    <meta name="robots" content="index, follow">
    
    <!-- Open Graph Meta Tags -->
    <meta property="og:title" content="Expériences d'Esteban DERENNE - Parcours Professionnel">
    <meta property="og:description" content="Découvrez le parcours professionnel d'Esteban DERENNE, développeur Full Stack avec une expérience solide en développement web.">
    <meta property="og:type" content="website">
    <meta property="og:url" content="https://esteban-derenne.fr/experiences">
    <meta property="og:image" content="https://esteban-derenne.fr/assets/img/photo-portfolio.png">
    <meta property="og:site_name" content="Esteban DERENNE Portfolio">
    <meta property="og:locale" content="fr_FR">
    
    <!-- Twitter Card Meta Tags -->
    <meta name="twitter:card" content="summary_large_image">
    <meta name="twitter:title" content="Expériences d'Esteban DERENNE - Parcours Professionnel">
    <meta name="twitter:description" content="Découvrez le parcours professionnel d'Esteban DERENNE, développeur Full Stack.">
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
            <a href="/experiences" class="nav-command active" data-tooltip="Page actuelle">Expériences</a>
            <a href="/formations" class="nav-command" data-tooltip="Mon parcours de formation">Formations</a>
            <a href="/contact" class="nav-command" data-tooltip="Me contacter">Contact</a>
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
        <section class="timeline-section">
            <div class="container">
            <div class="timeline">
                <?php foreach ($experiences as $index => $experience): ?>
                <div class="timeline-item <?php echo $index % 2 == 0 ? 'left' : 'right'; ?>">
                    <div class="timeline-content">
                        <div class="timeline-date">
                            <?php echo formatPeriod($experience['start_date'], $experience['end_date'], $experience['current_position']); ?>
                        </div>
                        <div class="timeline-card">
                            <div class="experience-header">
                                <?php if (!empty($experience['logo'])): ?>
                                <div class="company-logo">
                                    <img src="<?php echo htmlspecialchars($experience['logo']); ?>" alt="<?php echo htmlspecialchars($experience['company']); ?>" loading="lazy">
                                </div>
                                <?php endif; ?>
                                <div class="experience-info">
                                    <h3><?php echo htmlspecialchars($experience['title']); ?></h3>
                                </div>
                            </div>
                            
                            <div class="experience-details">
                                <div class="detail-item">
                                    <i class="fas fa-building"></i>
                                    <span><?php echo htmlspecialchars($experience['company']); ?></span>
                                </div>
                                
                                <?php if (!empty($experience['location'])): ?>
                                <div class="detail-item">
                                    <i class="fas fa-map-marker-alt"></i>
                                    <span><?php echo htmlspecialchars($experience['location']); ?></span>
                                </div>
                                <?php endif; ?>
                            </div>
                            
                            <?php if ($experience['current_position']): ?>
                            <div class="current-badge">
                                <i class="fas fa-clock"></i> Poste actuel
                            </div>
                            <?php endif; ?>
                            
                            <div class="experience-description">
                                <?php 
                                $description = $experience['description'];
                                $maxLength = 200; // Limite de caractères
                                
                                if (strlen($description) > $maxLength): 
                                    $shortDescription = substr($description, 0, $maxLength) . '...';
                                ?>
                                <p class="description-text">
                                    <span class="description-short"><?php echo nl2br(htmlspecialchars($shortDescription)); ?></span>
                                    <span class="description-full" style="display: none;"><?php echo nl2br(htmlspecialchars($description)); ?></span>
                                </p>
                                <button class="read-more-btn" onclick="toggleDescription(this)">
                                    <i class="fas fa-chevron-down"></i> Lire plus
                                </button>
                                <?php else: ?>
                                <p><?php echo nl2br(htmlspecialchars($description)); ?></p>
                                <?php endif; ?>
                            </div>
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
    <script src="assets/js/favicon-theme.js"></script>
    <script>
    function toggleDescription(button) {
        const descriptionText = button.previousElementSibling;
        const shortSpan = descriptionText.querySelector('.description-short');
        const fullSpan = descriptionText.querySelector('.description-full');
        const icon = button.querySelector('i');
        
        if (fullSpan.style.display === 'none') {
            // Afficher la description complète
            shortSpan.style.display = 'none';
            fullSpan.style.display = 'inline';
            button.innerHTML = '<i class="fas fa-chevron-up"></i> Lire moins';
        } else {
            // Afficher la description courte
            shortSpan.style.display = 'inline';
            fullSpan.style.display = 'none';
            button.innerHTML = '<i class="fas fa-chevron-down"></i> Lire plus';
        }
    }
    </script>
</body>
</html>
