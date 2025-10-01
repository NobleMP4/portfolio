<?php
session_start();
require_once '../config/database.php';
require_once '../includes/functions.php';

requireLogin();

$action = $_GET['action'] ?? 'list';
$project_id = $_GET['id'] ?? null;
$success_message = '';
$error_message = '';

// Traitement des actions
if ($_POST) {
    if (isset($_POST['add_project'])) {
        // Ajouter un projet
        $title = sanitize_input($_POST['title']);
        $description = sanitize_input($_POST['description']);
        $link = sanitize_input($_POST['link']);
        $technologies = sanitize_input($_POST['technologies']);
        $featured = isset($_POST['featured']) ? 1 : 0;
        $sort_order = (int)$_POST['sort_order'];
        
        $image = '';
        if (isset($_FILES['image']) && $_FILES['image']['error'] === UPLOAD_ERR_OK) {
            try {
                $image = uploadImage($_FILES['image'], '../assets/images/projects/');
            } catch (Exception $e) {
                $error_message = $e->getMessage();
            }
        }
        
        if (empty($error_message)) {
            try {
                $sql = "INSERT INTO projects (title, description, image, link, technologies, featured, sort_order) VALUES (?, ?, ?, ?, ?, ?, ?)";
                $stmt = $pdo->prepare($sql);
                $stmt->execute([$title, $description, $image, $link, $technologies, $featured, $sort_order]);
                
                $success_message = 'Projet ajouté avec succès !';
                $action = 'list';
            } catch (PDOException $e) {
                $error_message = 'Erreur lors de l\'ajout du projet.';
            }
        }
    }
    
    if (isset($_POST['edit_project'])) {
        // Modifier un projet
        $title = sanitize_input($_POST['title']);
        $description = sanitize_input($_POST['description']);
        $link = sanitize_input($_POST['link']);
        $technologies = sanitize_input($_POST['technologies']);
        $featured = isset($_POST['featured']) ? 1 : 0;
        $sort_order = (int)$_POST['sort_order'];
        
        // Récupérer l'image actuelle
        $current_project = $pdo->prepare("SELECT image FROM projects WHERE id = ?");
        $current_project->execute([$project_id]);
        $current_image = $current_project->fetchColumn();
        
        $image = $current_image;
        if (isset($_FILES['image']) && $_FILES['image']['error'] === UPLOAD_ERR_OK) {
            try {
                $image = uploadImage($_FILES['image'], '../assets/images/projects/');
                // Supprimer l'ancienne image
                if ($current_image) {
                    deleteImage('../' . $current_image);
                }
            } catch (Exception $e) {
                $error_message = $e->getMessage();
            }
        }
        
        if (empty($error_message)) {
            try {
                $sql = "UPDATE projects SET title = ?, description = ?, image = ?, link = ?, technologies = ?, featured = ?, sort_order = ? WHERE id = ?";
                $stmt = $pdo->prepare($sql);
                $stmt->execute([$title, $description, $image, $link, $technologies, $featured, $sort_order, $project_id]);
                
                $success_message = 'Projet modifié avec succès !';
                $action = 'list';
            } catch (PDOException $e) {
                $error_message = 'Erreur lors de la modification du projet.';
            }
        }
    }
}

// Supprimer un projet
if ($action === 'delete' && $project_id) {
    try {
        // Récupérer l'image pour la supprimer
        $stmt = $pdo->prepare("SELECT image FROM projects WHERE id = ?");
        $stmt->execute([$project_id]);
        $image = $stmt->fetchColumn();
        
        // Supprimer le projet
        $stmt = $pdo->prepare("DELETE FROM projects WHERE id = ?");
        $stmt->execute([$project_id]);
        
        // Supprimer l'image
        if ($image) {
            deleteImage('../' . $image);
        }
        
        $success_message = 'Projet supprimé avec succès !';
        $action = 'list';
    } catch (PDOException $e) {
        $error_message = 'Erreur lors de la suppression du projet.';
        $action = 'list';
    }
}

// Récupérer les données selon l'action
if ($action === 'edit' && $project_id) {
    $stmt = $pdo->prepare("SELECT * FROM projects WHERE id = ?");
    $stmt->execute([$project_id]);
    $project = $stmt->fetch();
    
    if (!$project) {
        $error_message = 'Projet non trouvé.';
        $action = 'list';
    }
}

if ($action === 'list') {
    $projects = $pdo->query("SELECT * FROM projects ORDER BY sort_order ASC, created_at DESC")->fetchAll();
}
?>
<!DOCTYPE html>
<html lang="fr">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Administration - Projets</title>
    
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
                    <li><a href="projects.php" class="active"><i class="fas fa-folder"></i> Projets</a></li>
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
                    <h1>
                        <?php if ($action === 'add'): ?>
                            Ajouter un Projet
                        <?php elseif ($action === 'edit'): ?>
                            Modifier un Projet
                        <?php else: ?>
                            Gestion des Projets
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
                <!-- Liste des projets -->
                <div class="card">
                    <div class="card-header">
                        <h3><i class="fas fa-folder"></i> Projets</h3>
                        <a href="?action=add" class="btn btn-primary">
                            <i class="fas fa-plus"></i> Ajouter un projet
                        </a>
                    </div>
                    <div class="card-body">
                        <?php if (empty($projects)): ?>
                            <p class="text-center">Aucun projet trouvé.</p>
                        <?php else: ?>
                        <div class="table-responsive">
                            <table class="table">
                                <thead>
                                    <tr>
                                        <th>Image</th>
                                        <th>Titre</th>
                                        <th>Technologies</th>
                                        <th>Mis en avant</th>
                                        <th>Ordre</th>
                                        <th>Date</th>
                                        <th>Actions</th>
                                    </tr>
                                </thead>
                                <tbody>
                                    <?php foreach ($projects as $project): ?>
                                    <tr>
                                        <td>
                                            <?php if ($project['image']): ?>
                                                <img src="../<?php echo htmlspecialchars($project['image']); ?>" alt="<?php echo htmlspecialchars($project['title']); ?>" style="width: 60px; height: 40px; object-fit: cover; border-radius: 4px;">
                                            <?php else: ?>
                                                <div style="width: 60px; height: 40px; background: #f1f5f9; border-radius: 4px; display: flex; align-items: center; justify-content: center;">
                                                    <i class="fas fa-image" style="color: #94a3b8;"></i>
                                                </div>
                                            <?php endif; ?>
                                        </td>
                                        <td>
                                            <strong><?php echo htmlspecialchars($project['title']); ?></strong>
                                            <br>
                                            <small style="color: #64748b;">
                                                <?php echo htmlspecialchars(substr($project['description'], 0, 50)) . '...'; ?>
                                            </small>
                                        </td>
                                        <td><?php echo htmlspecialchars($project['technologies']); ?></td>
                                        <td>
                                            <?php if ($project['featured']): ?>
                                                <span style="color: #059669;"><i class="fas fa-star"></i> Oui</span>
                                            <?php else: ?>
                                                <span style="color: #64748b;">Non</span>
                                            <?php endif; ?>
                                        </td>
                                        <td><?php echo $project['sort_order']; ?></td>
                                        <td><?php echo formatDate($project['created_at']); ?></td>
                                        <td>
                                            <div class="table-actions">
                                                <a href="?action=edit&id=<?php echo $project['id']; ?>" class="btn btn-sm btn-warning">
                                                    <i class="fas fa-edit"></i>
                                                </a>
                                                <a href="?action=delete&id=<?php echo $project['id']; ?>" class="btn btn-sm btn-danger" onclick="return confirm('Êtes-vous sûr de vouloir supprimer ce projet ?')">
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

                <?php elseif ($action === 'add' || $action === 'edit'): ?>
                <!-- Formulaire d'ajout/modification -->
                <div class="card">
                    <div class="card-header">
                        <h3>
                            <i class="fas fa-<?php echo $action === 'add' ? 'plus' : 'edit'; ?>"></i>
                            <?php echo $action === 'add' ? 'Ajouter' : 'Modifier'; ?> un projet
                        </h3>
                        <a href="?action=list" class="btn btn-secondary">
                            <i class="fas fa-arrow-left"></i> Retour
                        </a>
                    </div>
                    <div class="card-body">
                        <form method="POST" enctype="multipart/form-data">
                            <div class="form-row">
                                <div class="form-group">
                                    <label for="title">Titre *</label>
                                    <input type="text" id="title" name="title" required value="<?php echo isset($project) ? htmlspecialchars($project['title']) : ''; ?>">
                                </div>
                                <div class="form-group">
                                    <label for="sort_order">Ordre d'affichage</label>
                                    <input type="number" id="sort_order" name="sort_order" value="<?php echo isset($project) ? $project['sort_order'] : '0'; ?>">
                                </div>
                            </div>
                            
                            <div class="form-group">
                                <label for="description">Description *</label>
                                <textarea id="description" name="description" required><?php echo isset($project) ? htmlspecialchars($project['description']) : ''; ?></textarea>
                            </div>
                            
                            <div class="form-row">
                                <div class="form-group">
                                    <label for="link">Lien du projet</label>
                                    <input type="url" id="link" name="link" value="<?php echo isset($project) ? htmlspecialchars($project['link']) : ''; ?>">
                                </div>
                                <div class="form-group">
                                    <label for="technologies">Technologies utilisées</label>
                                    <input type="text" id="technologies" name="technologies" placeholder="PHP, MySQL, JavaScript..." value="<?php echo isset($project) ? htmlspecialchars($project['technologies']) : ''; ?>">
                                </div>
                            </div>
                            
                            <div class="form-group">
                                <label for="image">Image du projet</label>
                                <div class="file-upload">
                                    <input type="file" id="image" name="image" accept="image/*">
                                    <div class="file-upload-label">
                                        <i class="fas fa-cloud-upload-alt"></i>
                                        Choisir une image
                                    </div>
                                </div>
                                <?php if (isset($project) && $project['image']): ?>
                                <div class="file-preview">
                                    <p>Image actuelle :</p>
                                    <img src="../<?php echo htmlspecialchars($project['image']); ?>" alt="<?php echo htmlspecialchars($project['title']); ?>">
                                </div>
                                <?php endif; ?>
                            </div>
                            
                            <div class="checkbox-group">
                                <input type="checkbox" id="featured" name="featured" <?php echo (isset($project) && $project['featured']) ? 'checked' : ''; ?>>
                                <label for="featured">Projet mis en avant</label>
                            </div>
                            
                            <div style="margin-top: 2rem;">
                                <button type="submit" name="<?php echo $action === 'add' ? 'add_project' : 'edit_project'; ?>" class="btn btn-primary">
                                    <i class="fas fa-save"></i>
                                    <?php echo $action === 'add' ? 'Ajouter' : 'Modifier'; ?> le projet
                                </button>
                                <a href="?action=list" class="btn btn-secondary">Annuler</a>
                            </div>
                        </form>
                    </div>
                </div>
                <?php endif; ?>
            </div>
        </main>
    </div>

    <script>
    // Preview de l'image uploadée
    document.getElementById('image').addEventListener('change', function(e) {
        const file = e.target.files[0];
        if (file) {
            const reader = new FileReader();
            reader.onload = function(e) {
                let preview = document.querySelector('.file-preview');
                if (!preview) {
                    preview = document.createElement('div');
                    preview.className = 'file-preview';
                    document.querySelector('.file-upload').parentNode.appendChild(preview);
                }
                preview.innerHTML = '<p>Aperçu :</p><img src="' + e.target.result + '" alt="Preview">';
            };
            reader.readAsDataURL(file);
        }
    });
    </script>
    
    <!-- PWA Installation Script -->
    <script src="pwa-install.js"></script>
</body>
</html>
