<?php
session_start();
require_once '../config/database.php';
require_once '../includes/functions.php';

requireLogin();

$action = $_GET['action'] ?? 'list';
$message_id = $_GET['id'] ?? null;
$success_message = '';
$error_message = '';

// Marquer un message comme lu
if ($action === 'mark_read' && $message_id) {
    try {
        $stmt = $pdo->prepare("UPDATE contact_messages SET read_status = 1 WHERE id = ?");
        $stmt->execute([$message_id]);
        $success_message = 'Message marqué comme lu.';
        $action = 'list';
    } catch (PDOException $e) {
        $error_message = 'Erreur lors de la mise à jour.';
        $action = 'list';
    }
}

// Supprimer un message
if ($action === 'delete' && $message_id) {
    try {
        $stmt = $pdo->prepare("DELETE FROM contact_messages WHERE id = ?");
        $stmt->execute([$message_id]);
        $success_message = 'Message supprimé avec succès !';
        $action = 'list';
    } catch (PDOException $e) {
        $error_message = 'Erreur lors de la suppression du message.';
        $action = 'list';
    }
}

if ($action === 'list') {
    $messages = $pdo->query("SELECT * FROM contact_messages ORDER BY created_at DESC")->fetchAll();
}

if ($action === 'view' && $message_id) {
    $stmt = $pdo->prepare("SELECT * FROM contact_messages WHERE id = ?");
    $stmt->execute([$message_id]);
    $message = $stmt->fetch();
    
    if ($message && !$message['read_status']) {
        // Marquer comme lu automatiquement
        $stmt = $pdo->prepare("UPDATE contact_messages SET read_status = 1 WHERE id = ?");
        $stmt->execute([$message_id]);
    }
}
?>
<!DOCTYPE html>
<html lang="fr">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Administration - Messages</title>
    
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
                    <li><a href="messages.php" class="active"><i class="fas fa-envelope"></i> Messages</a></li>
                    <li><a href="settings.php"><i class="fas fa-sliders-h"></i> Paramètres</a></li>
                </ul>
            </nav>
        </aside>

        <!-- Main Content -->
        <main class="admin-main">
            <!-- Header -->
            <header class="admin-header">
                <div>
                    <h1>
                        <?php if ($action === 'view'): ?>
                            Lecture du Message
                        <?php else: ?>
                            Messages de Contact
                        <?php endif; ?>
                    </h1>
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

                <?php if ($action === 'list'): ?>
                <!-- Liste des messages -->
                <div class="card">
                    <div class="card-header">
                        <h3>Messages de Contact</h3>
                    </div>
                    <div class="card-body">
                        <?php if (empty($messages)): ?>
                            <p class="text-center">Aucun message trouvé.</p>
                        <?php else: ?>
                        <div class="table-responsive">
                            <table class="table">
                                <thead>
                                    <tr>
                                        <th>Statut</th>
                                        <th>Nom</th>
                                        <th>Email</th>
                                        <th>Sujet</th>
                                        <th>Date</th>
                                        <th>Actions</th>
                                    </tr>
                                </thead>
                                <tbody>
                                    <?php foreach ($messages as $msg): ?>
                                    <tr style="<?php echo !$msg['read_status'] ? 'background: rgba(88, 166, 255, 0.05);' : ''; ?>">
                                        <td>
                                            <?php if (!$msg['read_status']): ?>
                                                <span style="color: var(--accent-blue);"><i class="fas fa-circle"></i> Nouveau</span>
                                            <?php else: ?>
                                                <span style="color: var(--text-secondary);"><i class="far fa-circle"></i> Lu</span>
                                            <?php endif; ?>
                                        </td>
                                        <td><strong><?php echo htmlspecialchars($msg['name']); ?></strong></td>
                                        <td><?php echo htmlspecialchars($msg['email']); ?></td>
                                        <td><?php echo htmlspecialchars($msg['subject']); ?></td>
                                        <td><?php echo formatDate($msg['created_at'], 'd/m/Y H:i'); ?></td>
                                        <td>
                                            <div class="table-actions">
                                                <a href="?action=view&id=<?php echo $msg['id']; ?>" class="btn btn-sm btn-primary">
                                                    <i class="fas fa-eye"></i>
                                                </a>
                                                <?php if (!$msg['read_status']): ?>
                                                <a href="?action=mark_read&id=<?php echo $msg['id']; ?>" class="btn btn-sm btn-success">
                                                    <i class="fas fa-check"></i>
                                                </a>
                                                <?php endif; ?>
                                                <a href="?action=delete&id=<?php echo $msg['id']; ?>" class="btn btn-sm btn-danger" onclick="return confirm('Êtes-vous sûr de vouloir supprimer ce message ?')">
                                                    <i class="fas fa-trash"></i>
                                                </a>
                                            </div>
                                        </td>
                                    </tr>
                                    <?php endforeach; ?>
                                </tbody>
                            </table>
                        </div>
                        <?php endif; ?>
                    </div>
                </div>

                <?php elseif ($action === 'view' && isset($message)): ?>
                <!-- Affichage du message -->
                <div class="card">
                    <div class="card-header">
                        <h3>Message de <?php echo htmlspecialchars($message['name']); ?></h3>
                        <a href="?action=list" class="btn btn-secondary">
                            <i class="fas fa-arrow-left"></i> Retour
                        </a>
                    </div>
                    <div class="card-body">
                        <div style="background: var(--bg-primary); border: 1px solid var(--border); border-radius: 8px; padding: 1.5rem; margin-bottom: 1.5rem;">
                            <div style="display: grid; grid-template-columns: 1fr 1fr; gap: 1rem; margin-bottom: 1rem; font-size: 0.9rem;">
                                <div><strong>Nom :</strong> <?php echo htmlspecialchars($message['name']); ?></div>
                                <div><strong>Email :</strong> <?php echo htmlspecialchars($message['email']); ?></div>
                                <div><strong>Sujet :</strong> <?php echo htmlspecialchars($message['subject']); ?></div>
                                <div><strong>Date :</strong> <?php echo formatDate($message['created_at'], 'd/m/Y H:i'); ?></div>
                            </div>
                        </div>
                        
                        <div style="background: var(--bg-primary); border: 1px solid var(--border); border-radius: 8px; padding: 1.5rem;">
                            <h4 style="margin-bottom: 1rem; color: var(--text-primary);">Message :</h4>
                            <p style="line-height: 1.6; color: var(--text-secondary);">
                                <?php echo nl2br(htmlspecialchars($message['message'])); ?>
                            </p>
                        </div>
                    </div>
                </div>
                <?php endif; ?>
            </div>
        </main>
    </div>

    <!-- PWA Installation Script -->
    <script src="pwa-install.js"></script>
</body>
</html>
