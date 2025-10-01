<?php
require_once 'config/database.php';
require_once 'includes/functions.php';

$projects = getProjects($pdo);
?>
<!DOCTYPE html>
<html lang="fr">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Portfolio - Mes Projets</title>
    
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
            <a href="portfolio.php" class="nav-command active" data-tooltip="Page actuelle">Mes projets</a>
            <a href="experiences.php" class="nav-command" data-tooltip="Mon expérience professionnelle">Expériences</a>
            <a href="formations.php" class="nav-command" data-tooltip="Mon parcours de formation">Formations</a>
            <a href="contact.php" class="nav-command" data-tooltip="Me contacter">Contact</a>
        </div>
    </div>

    <!-- Main Container -->
    <div class="main-container">
        <!-- Portfolio Header -->
        <section class="portfolio-header" style="text-align: center; padding: 4rem 0 2rem;">
            <h1 style="font-family: 'JetBrains Mono', monospace; color: var(--text-primary); margin-bottom: 1rem;">
                <span style="color: var(--text-muted);">// </span>Mes Projets
            </h1>
            <p style="color: var(--text-secondary); font-size: 1.1rem; max-width: 600px; margin: 0 auto;">
                Découvrez une sélection de mes projets web les plus récents et aboutis.
            </p>
        </section>

        <!-- Portfolio Grid -->
        <section class="portfolio-grid" style="padding: 2rem 0;">
            <div class="container">
            <div class="projects-grid">
                <?php foreach ($projects as $project): ?>
                <div class="project-card" onclick="openProjectModal(<?php echo $project['id']; ?>)">
                    <div class="project-header">
                        <h3 class="project-title"><?php echo htmlspecialchars($project['title']); ?></h3>
                        <?php if (!empty($project['technologies'])): ?>
                        <div class="project-tech">
                            <?php 
                            $technologies = explode(', ', $project['technologies']);
                            foreach ($technologies as $tech): 
                            ?>
                            <span class="tech-tag"><?php echo htmlspecialchars($tech); ?></span>
                            <?php endforeach; ?>
                        </div>
                        <?php endif; ?>
                    </div>
                    <div class="project-body">
                        <p class="project-desc"><?php echo htmlspecialchars($project['description']); ?></p>
                        <div class="project-links">
                            <?php if (!empty($project['link'])): ?>
                            <a href="<?php echo htmlspecialchars($project['link']); ?>" class="project-link" target="_blank" onclick="event.stopPropagation()">
                                <i class="fas fa-external-link-alt"></i>
                                Voir le projet
                            </a>
                            <?php endif; ?>
                            <a href="#" class="project-link" onclick="openProjectModal(<?php echo $project['id']; ?>); event.stopPropagation()">
                                <i class="fas fa-info-circle"></i>
                                Détails
                            </a>
                        </div>
                        <?php if ($project['featured']): ?>
                        <div class="featured-badge" style="background: var(--accent-blue); color: var(--bg-primary); padding: 0.25rem 0.75rem; border-radius: 1rem; font-size: 0.75rem; font-weight: 600; margin-top: 1rem; display: inline-block;">
                            <i class="fas fa-star"></i> Projet mis en avant
                        </div>
                        <?php endif; ?>
                    </div>
                </div>
                <?php endforeach; ?>
            </div>
        </div>
    </section>

    <!-- Project Modal -->
    <div id="projectModal" class="modal">
        <div class="modal-content">
            <span class="close">&times;</span>
            <div id="modalContent">
                <!-- Contenu chargé dynamiquement -->
            </div>
        </div>
    </div>

    </div>

    <script src="assets/js/main.js"></script>
    <script>
    // Données des projets pour les modales
    const projectsData = <?php echo json_encode($projects); ?>;
    
    function openProjectModal(projectId) {
        const project = projectsData.find(p => p.id == projectId);
        if (!project) return;
        
        const modal = document.getElementById('projectModal');
        const modalContent = document.getElementById('modalContent');
        
        const technologies = project.technologies ? project.technologies.split(', ') : [];
        const techTags = technologies.map(tech => `<span class="tech-tag">${tech}</span>`).join('');
        
        modalContent.innerHTML = `
            <div class="modal-project">
                <img src="${project.image}" alt="${project.title}" class="modal-image">
                <div class="modal-info">
                    <h2>${project.title}</h2>
                    <p class="modal-description">${project.description}</p>
                    ${techTags ? `<div class="modal-technologies">${techTags}</div>` : ''}
                    ${project.link ? `<a href="${project.link}" target="_blank" class="btn btn-primary modal-link">
                        <i class="fas fa-external-link-alt"></i> Voir le projet
                    </a>` : ''}
                </div>
            </div>
        `;
        
        modal.style.display = 'block';
    }
    
    // Fermer la modale
    document.querySelector('.close').onclick = function() {
        document.getElementById('projectModal').style.display = 'none';
    }
    
    window.onclick = function(event) {
        const modal = document.getElementById('projectModal');
        if (event.target == modal) {
            modal.style.display = 'none';
        }
    }
    </script>
</body>
</html>
