<?php
require_once 'config/database.php';
require_once 'includes/functions.php';

$skills = getSkills($pdo);
?>
<!DOCTYPE html>
<html lang="fr">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Esteban DERENNE</title>
    
    <!-- SEO Meta Tags -->
    <meta name="description" content="À propos d'Esteban DERENNE - Développeur Full Stack passionné. Découvrez mon parcours, mes compétences techniques et ma passion pour le développement web.">
    <meta name="keywords" content="Esteban DERENNE, à propos, développeur, full stack, parcours, compétences, développement web, PHP, JavaScript">
    <meta name="author" content="Esteban DERENNE">
    <meta name="robots" content="index, follow">
    
    <!-- Open Graph Meta Tags -->
    <meta property="og:title" content="À propos d'Esteban DERENNE - Développeur Full Stack">
    <meta property="og:description" content="Découvrez le parcours et les compétences d'Esteban DERENNE, développeur Full Stack passionné par le développement web.">
    <meta property="og:type" content="website">
    <meta property="og:url" content="https://esteban-derenne.fr/a-propos">
    <meta property="og:image" content="https://esteban-derenne.fr/assets/img/photo-portfolio.png">
    <meta property="og:site_name" content="Esteban DERENNE Portfolio">
    <meta property="og:locale" content="fr_FR">
    
    <!-- Twitter Card Meta Tags -->
    <meta name="twitter:card" content="summary_large_image">
    <meta name="twitter:title" content="À propos d'Esteban DERENNE - Développeur Full Stack">
    <meta name="twitter:description" content="Découvrez le parcours et les compétences d'Esteban DERENNE, développeur Full Stack.">
    <meta name="twitter:image" content="https://esteban-derenne.fr/assets/img/photo-portfolio.png">
    
    <!-- Favicon dynamique -->
    <link rel="icon" type="image/png" href="assets/logo/logo-sombre.png?v=5">
    
    <link rel="stylesheet" href="assets/css/style.css">
    <link href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/6.0.0/css/all.min.css" rel="stylesheet">
</head>
<body>
    <!-- Code rain background -->
    <div class="code-rain" id="codeRain"></div>

    <!-- Bouton retour accueil -->
    
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
            <a href="/a-propos" class="nav-command active" data-tooltip="Page actuelle">À propos</a>
            <a href="/projets" class="nav-command" data-tooltip="Voir mes réalisations">Mes projets</a>
            <a href="/experiences" class="nav-command" data-tooltip="Mon expérience professionnelle">Expériences</a>
            <a href="/formations" class="nav-command" data-tooltip="Mon parcours de formation">Formations</a>
            <a href="/contact" class="nav-command" data-tooltip="Me contacter">Contact</a>
        </div>
    </div>

    <!-- Main Container -->
    <div class="main-container">
        <!-- About Header -->
        <section style="text-align: center; padding: 4rem 0 2rem;">
            <h1 style="font-family: 'JetBrains Mono', monospace; color: var(--text-primary); margin-bottom: 1rem;">
                <span style="color: var(--text-muted);">// </span>À propos de moi
            </h1>
            <p style="color: var(--text-secondary); font-size: 1.1rem; max-width: 600px; margin: 0 auto;">
                Découvrez mon parcours, et ma passion pour le développement web.
            </p>
        </section>

        <!-- About Content -->
        <section style="padding: 2rem 0;">
            <div class="about-layout">
                <div class="about-photo" style="text-align: center;">
                    <img src="assets/img/photo-portfolio.png" alt="Photo de profil" style="width: 100%; border-radius: 12px; border: 2px solid var(--border); box-shadow: 0 8px 25px rgba(88, 166, 255, 0.15);">
                    <div style="margin-top: 1.5rem;">
                        <a href="assets/cv/cv.pdf" class="btn btn-primary" target="_blank">
                            <i class="fas fa-download"></i> Télécharger mon CV
                        </a>
                    </div>
                </div>
                <div style="background: var(--bg-secondary); border: 1px solid var(--border); border-radius: 12px; padding: 2rem;">
                    <div style="margin-bottom: 2rem;">
                        <h2 style="font-family: 'JetBrains Mono', monospace; color: var(--text-primary); margin-bottom: 1rem;">
                            <span style="color: var(--text-muted);">// </span>Mon Histoire
                        </h2>
                        <p style="color: var(--text-secondary); line-height: 1.7; margin-bottom: 1.5rem;">
                        Passionné par le développement web, je conçois des solutions digitales qui allient performance technique et qualité d’utilisation.
                        </p>
                        <p style="color: var(--text-secondary); line-height: 1.7; margin-bottom: 1.5rem;">
                            Diplômé d’un Bac+2 (DISII) en développement web, je continue de renforcer mes compétences et d’explorer de nouvelles technologies afin de progresser constamment dans ce domaine.
                        </p>
                        <p style="color: var(--text-secondary); line-height: 1.7;">
                        J’aime relever des défis techniques qui me poussent à évoluer et à élargir mes connaissances. Le travail en équipe et le partage de savoirs occupent également une place importante dans ma manière d’avancer.
                        </p>
                    </div>
                </div>
            </div>
        </section>

        <!-- Skills Section -->
        <section style="padding: 3rem 0;">
            <div style="max-width: 900px; margin: 0 auto;">
                <h2 style="font-family: 'JetBrains Mono', monospace; color: var(--text-primary); text-align: center; margin-bottom: 3rem;">
                    <span style="color: var(--text-muted);">// </span>Mes Compétences
                </h2>
                
                <div class="skills-grid" style="display: grid; grid-template-columns: 1fr 1fr; gap: 2rem; margin-bottom: 3rem;">
                    <!-- Technologies -->
                    <div style="background: var(--bg-secondary); border: 1px solid var(--border); border-radius: 8px; padding: 1.5rem;">
                        <h3 style="font-family: 'JetBrains Mono', monospace; color: var(--accent-blue); margin-bottom: 1.5rem; font-size: 1.1rem;">
                            <i class="fas fa-code" style="margin-right: 0.5rem;"></i>Technologies
                        </h3>
                        <div style="display: grid; grid-template-columns: repeat(auto-fit, minmax(100px, 1fr)); gap: 0.75rem;">
                            <?php foreach ($skills as $skill): ?>
                                <?php if ($skill['category'] === 'Technologies'): ?>
                                <div style="background: var(--bg-primary); border: 1px solid var(--border); border-radius: 6px; padding: 0.75rem; text-align: center; transition: all 0.3s ease; cursor: pointer;" onmouseover="this.style.borderColor='var(--accent-blue)'" onmouseout="this.style.borderColor='var(--border)'">
                                    <?php
                                    $iconClass = '';
                                    switch(strtolower($skill['name'])) {
                                        case 'html5': $iconClass = 'fab fa-html5'; break;
                                        case 'css3': $iconClass = 'fab fa-css3-alt'; break;
                                        case 'javascript': $iconClass = 'fab fa-js-square'; break;
                                        case 'php': $iconClass = 'fab fa-php'; break;
                                        case 'mysql': $iconClass = 'fas fa-database'; break;
                                        case 'react': $iconClass = 'fab fa-react'; break;
                                        case 'node.js': $iconClass = 'fab fa-node-js'; break;
                                        case 'github': $iconClass = 'fab fa-github'; break;
                                        case 'python': $iconClass = 'fab fa-python'; break;
                                        case 'sass': $iconClass = 'fab fa-sass'; break;
                                        default: $iconClass = 'fas fa-code'; break;
                                    }
                                    ?>
                                    <i class="<?php echo $iconClass; ?>" style="color: var(--accent-blue); font-size: 1.2rem; margin-bottom: 0.5rem; display: block;"></i>
                                    <div style="font-size: 0.75rem; color: var(--text-primary); font-weight: 500; margin-bottom: 0.25rem;">
                                        <?php echo htmlspecialchars($skill['name']); ?>
                                    </div>
                                    <div class="skill-stars" style="justify-content: center;">
                                        <?php 
                                        $stars = round($skill['level'] / 20);
                                        for ($i = 1; $i <= 5; $i++): 
                                        ?>
                                            <i class="fas fa-star" style="color: <?php echo $i <= $stars ? 'var(--accent-blue)' : 'var(--text-muted)'; ?>; font-size: 0.6rem;"></i>
                                        <?php endfor; ?>
                                    </div>
                                </div>
                                <?php endif; ?>
                            <?php endforeach; ?>
                        </div>
                    </div>

                    <!-- Framework et CMS -->
                    <div style="background: var(--bg-secondary); border: 1px solid var(--border); border-radius: 8px; padding: 1.5rem;">
                        <h3 style="font-family: 'JetBrains Mono', monospace; color: var(--accent-green); margin-bottom: 1.5rem; font-size: 1.1rem;">
                            <i class="fas fa-layer-group" style="margin-right: 0.5rem;"></i>Framework et CMS
                        </h3>
                        <div style="display: grid; grid-template-columns: repeat(auto-fit, minmax(100px, 1fr)); gap: 0.75rem;">
                            <?php foreach ($skills as $skill): ?>
                                <?php if ($skill['category'] === 'Framework et CMS'): ?>
                                <div style="background: var(--bg-primary); border: 1px solid var(--border); border-radius: 6px; padding: 0.75rem; text-align: center; transition: all 0.3s ease; cursor: pointer;" onmouseover="this.style.borderColor='var(--accent-green)'" onmouseout="this.style.borderColor='var(--border)'">
                                    <?php
                                    $iconClass = '';
                                    switch(strtolower($skill['name'])) {
                                        case 'wordpress': $iconClass = 'fab fa-wordpress'; break;
                                        case 'bootstrap': $iconClass = 'fab fa-bootstrap'; break;
                                        case 'laravel': $iconClass = 'fab fa-laravel'; break;
                                        case 'vue.js': $iconClass = 'fab fa-vuejs'; break;
                                        case 'angular': $iconClass = 'fab fa-angular'; break;
                                        case 'symfony': $iconClass = 'fab fa-symfony'; break;
                                        default: $iconClass = 'fas fa-layer-group'; break;
                                    }
                                    ?>
                                    <i class="<?php echo $iconClass; ?>" style="color: var(--accent-green); font-size: 1.2rem; margin-bottom: 0.5rem; display: block;"></i>
                                    <div style="font-size: 0.75rem; color: var(--text-primary); font-weight: 500; margin-bottom: 0.25rem;">
                                        <?php echo htmlspecialchars($skill['name']); ?>
                                    </div>
                                    <div class="skill-stars" style="justify-content: center;">
                                        <?php 
                                        $stars = round($skill['level'] / 20);
                                        for ($i = 1; $i <= 5; $i++): 
                                        ?>
                                            <i class="fas fa-star" style="color: <?php echo $i <= $stars ? 'var(--accent-green)' : 'var(--text-muted)'; ?>; font-size: 0.6rem;"></i>
                                        <?php endfor; ?>
                                    </div>
                                </div>
                                <?php endif; ?>
                            <?php endforeach; ?>
                        </div>
                    </div>
                </div>

                <!-- Section "En savoir plus" -->
                <div style="text-align: center; margin: 3rem 0 2rem;">
                    <h3 style="font-family: 'JetBrains Mono', monospace; color: var(--text-primary); font-size: 1.1rem;">
                        <span style="color: var(--text-muted);">// </span>En savoir plus
                    </h3>
                </div>

                <div class="skills-categories" style="display: grid; grid-template-columns: 1fr 1fr 1fr; gap: 2rem;">
                    <!-- Langues -->
                    <div style="background: var(--bg-secondary); border: 1px solid var(--border); border-radius: 8px; padding: 1.5rem;">
                        <h3 style="font-family: 'JetBrains Mono', monospace; color: var(--accent-blue); margin-bottom: 1.5rem; font-size: 1rem;">
                            <i class="fas fa-globe" style="margin-right: 0.5rem;"></i>Langues
                        </h3>
                        <div style="display: grid; gap: 0.75rem;">
                            <?php foreach ($skills as $skill): ?>
                                <?php if ($skill['category'] === 'Langues'): ?>
                                <?php
                                // Convertir le niveau en texte simple
                                $levelText = '';
                                $levelColor = '';
                                if ($skill['level'] >= 95) {
                                    $levelText = 'Maternelle';
                                    $levelColor = 'var(--success)';
                                } elseif ($skill['level'] >= 70) {
                                    $levelText = 'Expert';
                                    $levelColor = 'var(--accent-blue)';
                                } elseif ($skill['level'] >= 50) {
                                    $levelText = 'Intermédiaire';
                                    $levelColor = 'var(--warning)';
                                } else {
                                    $levelText = 'Débutant';
                                    $levelColor = 'var(--accent-purple)';
                                }
                                ?>
                                <div style="background: var(--bg-primary); border: 1px solid var(--border); border-radius: 6px; padding: 0.75rem;">
                                    <div style="display: flex; align-items: center; gap: 0.75rem; margin-bottom: 0.5rem;">
                                        <span style="color: var(--text-primary); font-weight: 500; font-size: 0.9rem; flex: 1;"><?php echo htmlspecialchars($skill['name']); ?></span>
                                    </div>
                                    <div style="text-align: center;">
                                        <span style="background: <?php echo $levelColor; ?>; color: var(--bg-primary); padding: 0.3rem 0.75rem; border-radius: 12px; font-size: 0.75rem; font-weight: 500; display: inline-block; min-width: 80px;">
                                            <?php echo $levelText; ?>
                                        </span>
                                    </div>
                                </div>
                                <?php endif; ?>
                            <?php endforeach; ?>
                        </div>
                    </div>

                    <!-- Outils -->
                    <div style="background: var(--bg-secondary); border: 1px solid var(--border); border-radius: 8px; padding: 1.5rem;">
                        <h3 style="font-family: 'JetBrains Mono', monospace; color: var(--warning); margin-bottom: 1.5rem; font-size: 1rem;">
                            <i class="fas fa-tools" style="margin-right: 0.5rem;"></i>Outils
                        </h3>
                        <div style="display: grid; gap: 0.5rem;">
                            <?php foreach ($skills as $skill): ?>
                                <?php if ($skill['category'] === 'Outils'): ?>
                                <div style="background: var(--bg-primary); border: 1px solid var(--border); border-radius: 6px; padding: 0.5rem 0.75rem; display: flex; align-items: center; gap: 0.5rem; transition: all 0.3s ease;" onmouseover="this.style.borderColor='var(--warning)'" onmouseout="this.style.borderColor='var(--border)'">
                                    <?php
                                    $iconClass = '';
                                    switch(strtolower($skill['name'])) {
                                        case 'git': $iconClass = 'fab fa-git-alt'; break;
                                        case 'github': $iconClass = 'fab fa-github'; break;
                                        case 'chatgpt': $iconClass = 'fas fa-robot'; break;
                                        case 'proxmox': $iconClass = 'fas fa-server'; break;
                                        case 'docker': $iconClass = 'fab fa-docker'; break;
                                        case 'photoshop': $iconClass = 'fab fa-adobe'; break;
                                        default: $iconClass = 'fas fa-tools'; break;
                                    }
                                    ?>
                                    <i class="<?php echo $iconClass; ?>" style="color: var(--warning); font-size: 0.9rem;"></i>
                                    <span style="font-size: 0.85rem; color: var(--text-primary); font-weight: 500;"><?php echo htmlspecialchars($skill['name']); ?></span>
                                </div>
                                <?php endif; ?>
                            <?php endforeach; ?>
                        </div>
                    </div>

                    <!-- Loisirs -->
                    <div style="background: var(--bg-secondary); border: 1px solid var(--border); border-radius: 8px; padding: 1.5rem;">
                        <h3 style="font-family: 'JetBrains Mono', monospace; color: var(--accent-purple); margin-bottom: 1.5rem; font-size: 1rem;">
                            <i class="fas fa-heart" style="margin-right: 0.5rem;"></i>Loisirs
                        </h3>
                        <div style="display: grid; gap: 0.5rem;">
                            <?php foreach ($skills as $skill): ?>
                                <?php if ($skill['category'] === 'Loisirs'): ?>
                                <div style="background: var(--bg-primary); border: 1px solid var(--border); border-radius: 6px; padding: 0.5rem 0.75rem; display: flex; align-items: center; gap: 0.5rem; transition: all 0.3s ease;" onmouseover="this.style.borderColor='var(--accent-purple)'" onmouseout="this.style.borderColor='var(--border)'">
                                    <?php
                                    $iconClass = '';
                                    switch(strtolower($skill['name'])) {
                                        case 'sport auto': $iconClass = 'fas fa-car'; break;
                                        case 'jeux vidéo': $iconClass = 'fas fa-gamepad'; break;
                                        case 'simracing': $iconClass = 'fas fa-flag-checkered'; break;
                                        case 'technologie': $iconClass = 'fas fa-microchip'; break;
                                        default: $iconClass = 'fas fa-heart'; break;
                                    }
                                    ?>
                                    <i class="<?php echo $iconClass; ?>" style="color: var(--accent-purple); font-size: 0.9rem;"></i>
                                    <span style="font-size: 0.85rem; color: var(--text-primary); font-weight: 500;"><?php echo htmlspecialchars($skill['name']); ?></span>
                                </div>
                                <?php endif; ?>
                            <?php endforeach; ?>
                        </div>
                    </div>
                </div>
            </div>
        </section>


    </div>

    <script src="assets/js/main.js"></script>
    <script src="assets/js/favicon-theme.js"></script>
</body>
</html>
