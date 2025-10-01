<?php
session_start();
require_once '../config/database.php';
require_once '../includes/functions.php';

requireLogin();

// Statistiques pour le dashboard
try {
    $stats = [
        'projects' => $pdo->query("SELECT COUNT(*) FROM projects")->fetchColumn(),
        'experiences' => $pdo->query("SELECT COUNT(*) FROM experiences")->fetchColumn(),
        'formations' => $pdo->query("SELECT COUNT(*) FROM formations")->fetchColumn(),
        'skills' => $pdo->query("SELECT COUNT(*) FROM skills")->fetchColumn(),
        'messages' => $pdo->query("SELECT COUNT(*) FROM contact_messages WHERE read_status = 0")->fetchColumn()
    ];
    
    // Messages récents
    $recent_messages = $pdo->query("
        SELECT id, name, email, subject, created_at 
        FROM contact_messages 
        ORDER BY created_at DESC 
        LIMIT 5
    ")->fetchAll();
    
    // Projets récents
    $recent_projects = $pdo->query("
        SELECT id, title, created_at 
        FROM projects 
        ORDER BY created_at DESC 
        LIMIT 5
    ")->fetchAll();
    
} catch (PDOException $e) {
    $stats = ['projects' => 0, 'experiences' => 0, 'formations' => 0, 'skills' => 0, 'messages' => 0];
    $recent_messages = [];
    $recent_projects = [];
}
?>
<!DOCTYPE html>
<html lang="fr">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Esteban DERENNE - Administration</title>
    
    <!-- Favicon dynamique -->
    <link rel="icon" type="image/png" href="../assets/logo/logo-sombre.png?v=5">
    
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
                    <li><a href="index.php" class="active"><i class="fas fa-tachometer-alt"></i> Dashboard</a></li>
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
                    <h1>Dashboard</h1>
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
                <!-- Stats Cards -->
                <div class="stats-grid">
                    <div class="stat-card">
                        <div class="stat-icon primary">
                            <i class="fas fa-folder"></i>
                        </div>
                        <div class="stat-number"><?php echo $stats['projects']; ?></div>
                        <div class="stat-label">Projets</div>
                    </div>
                    
                    <div class="stat-card">
                        <div class="stat-icon success">
                            <i class="fas fa-briefcase"></i>
                        </div>
                        <div class="stat-number"><?php echo $stats['experiences']; ?></div>
                        <div class="stat-label">Expériences</div>
                    </div>
                    
                    <div class="stat-card">
                        <div class="stat-icon warning">
                            <i class="fas fa-graduation-cap"></i>
                        </div>
                        <div class="stat-number"><?php echo $stats['formations']; ?></div>
                        <div class="stat-label">Formations</div>
                    </div>
                    
                    <div class="stat-card">
                        <div class="stat-icon primary">
                            <i class="fas fa-cogs"></i>
                        </div>
                        <div class="stat-number"><?php echo $stats['skills']; ?></div>
                        <div class="stat-label">Compétences</div>
                    </div>
                    
                    <div class="stat-card">
                        <div class="stat-icon error">
                            <i class="fas fa-envelope"></i>
                        </div>
                        <div class="stat-number"><?php echo $stats['messages']; ?></div>
                        <div class="stat-label">Messages non lus</div>
                    </div>
                </div>

                <!-- Recent Activity -->
                <div style="display: grid; grid-template-columns: 1fr 1fr; gap: 2rem;">
                    <!-- Recent Messages -->
                    <div class="card">
                        <div class="card-header">
                            <h3><i class="fas fa-envelope"></i> Messages Récents</h3>
                            <a href="messages.php" class="btn btn-sm btn-primary">Voir tous</a>
                        </div>
                        <div class="card-body">
                            <?php if (empty($recent_messages)): ?>
                                <p class="text-center" style="color: #64748b; padding: 2rem;">Aucun message récent</p>
                            <?php else: ?>
                                <div class="message-list">
                                    <?php foreach ($recent_messages as $message): ?>
                                    <div class="message-item" style="padding: 1rem; border-bottom: 1px solid #e2e8f0; display: flex; justify-content: space-between; align-items: center;">
                                        <div>
                                            <strong><?php echo htmlspecialchars($message['name']); ?></strong>
                                            <br>
                                            <small style="color: #64748b;"><?php echo htmlspecialchars($message['subject']); ?></small>
                                        </div>
                                        <div style="text-align: right; font-size: 0.875rem; color: #64748b;">
                                            <?php echo formatDate($message['created_at'], 'd/m/Y H:i'); ?>
                                        </div>
                                    </div>
                                    <?php endforeach; ?>
                                </div>
                            <?php endif; ?>
                        </div>
                    </div>

                    <!-- Recent Projects -->
                    <div class="card">
                        <div class="card-header">
                            <h3><i class="fas fa-folder"></i> Projets Récents</h3>
                            <a href="projects.php" class="btn btn-sm btn-primary">Voir tous</a>
                        </div>
                        <div class="card-body">
                            <?php if (empty($recent_projects)): ?>
                                <p class="text-center" style="color: #64748b; padding: 2rem;">Aucun projet récent</p>
                            <?php else: ?>
                                <div class="project-list">
                                    <?php foreach ($recent_projects as $project): ?>
                                    <div class="project-item" style="padding: 1rem; border-bottom: 1px solid #e2e8f0; display: flex; justify-content: space-between; align-items: center;">
                                        <div>
                                            <strong><?php echo htmlspecialchars($project['title']); ?></strong>
                                        </div>
                                        <div style="text-align: right; font-size: 0.875rem; color: #64748b;">
                                            <?php echo formatDate($project['created_at'], 'd/m/Y'); ?>
                                        </div>
                                    </div>
                                    <?php endforeach; ?>
                                </div>
                            <?php endif; ?>
                        </div>
                    </div>
                </div>

            </div>
        </main>
    </div>

    <script>
    // Auto-refresh des statistiques toutes les 5 minutes
    setInterval(function() {
        location.reload();
    }, 300000);
    
    // Animation des cartes de statistiques
    document.addEventListener('DOMContentLoaded', function() {
        const statNumbers = document.querySelectorAll('.stat-number');
        
        statNumbers.forEach(stat => {
            const finalNumber = parseInt(stat.textContent);
            let currentNumber = 0;
            const increment = Math.ceil(finalNumber / 20);
            
            const counter = setInterval(() => {
                currentNumber += increment;
                if (currentNumber >= finalNumber) {
                    stat.textContent = finalNumber;
                    clearInterval(counter);
                } else {
                    stat.textContent = currentNumber;
                }
            }, 50);
        });
    });
    </script>
    
    <!-- PWA Installation Script -->
    <script src="pwa-install.js"></script>
    <script src="favicon-theme.js"></script>
</body>
</html>
