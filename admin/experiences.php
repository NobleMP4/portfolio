<?php
session_start();
require_once '../config/database.php';
require_once '../includes/functions.php';

requireLogin();

$action = $_GET['action'] ?? 'list';
$experience_id = $_GET['id'] ?? null;
$success_message = '';
$error_message = '';

// Traitement des actions
if ($_POST) {
    if (isset($_POST['add_experience'])) {
        $title = sanitize_input($_POST['title']);
        $company = sanitize_input($_POST['company']);
        $location = sanitize_input($_POST['location']);
        $start_date = $_POST['start_date'];
        $end_date = $_POST['end_date'] ?: null;
        $current_position = isset($_POST['current_position']) ? 1 : 0;
        $description = sanitize_input($_POST['description']);
        $technologies = sanitize_input($_POST['technologies']);
        $logo = '';
        $sort_order = (int)$_POST['sort_order'];
        
        // Gestion de l'upload du logo
        if (isset($_FILES['logo']) && $_FILES['logo']['error'] === UPLOAD_ERR_OK) {
            try {
                $logo = uploadImage($_FILES['logo'], '../assets/images/logos/');
            } catch (Exception $e) {
                $error_message = $e->getMessage();
            }
        }
        
        try {
            $sql = "INSERT INTO experiences (title, company, location, start_date, end_date, current_position, description, technologies, logo, sort_order) VALUES (?, ?, ?, ?, ?, ?, ?, ?, ?, ?)";
            $stmt = $pdo->prepare($sql);
            $stmt->execute([$title, $company, $location, $start_date, $end_date, $current_position, $description, $technologies, $logo, $sort_order]);
            
            $success_message = 'Expérience ajoutée avec succès !';
            $action = 'list';
        } catch (PDOException $e) {
            $error_message = 'Erreur lors de l\'ajout de l\'expérience.';
        }
    }
    
    if (isset($_POST['edit_experience'])) {
        $title = sanitize_input($_POST['title']);
        $company = sanitize_input($_POST['company']);
        $location = sanitize_input($_POST['location']);
        $start_date = $_POST['start_date'];
        $end_date = $_POST['end_date'] ?: null;
        $current_position = isset($_POST['current_position']) ? 1 : 0;
        $description = sanitize_input($_POST['description']);
        $technologies = sanitize_input($_POST['technologies']);
        $logo = '';
        $sort_order = (int)$_POST['sort_order'];
        
        // Gestion de l'upload du logo
        if (isset($_FILES['logo']) && $_FILES['logo']['error'] === UPLOAD_ERR_OK) {
            try {
                // Supprimer l'ancien logo s'il existe
                if (isset($_POST['current_logo']) && !empty($_POST['current_logo'])) {
                    deleteImage('../' . $_POST['current_logo']);
                }
                $logo = uploadImage($_FILES['logo'], '../assets/images/logos/');
            } catch (Exception $e) {
                $error_message = $e->getMessage();
            }
        } elseif (isset($_POST['current_logo']) && !empty($_POST['current_logo'])) {
            // Garder le logo existant si aucun nouveau n'est uploadé
            $logo = sanitize_input($_POST['current_logo']);
        }
        
        try {
            $sql = "UPDATE experiences SET title = ?, company = ?, location = ?, start_date = ?, end_date = ?, current_position = ?, description = ?, technologies = ?, logo = ?, sort_order = ? WHERE id = ?";
            $stmt = $pdo->prepare($sql);
            $stmt->execute([$title, $company, $location, $start_date, $end_date, $current_position, $description, $technologies, $logo, $sort_order, $experience_id]);
            
            $success_message = 'Expérience modifiée avec succès !';
            $action = 'list';
        } catch (PDOException $e) {
            $error_message = 'Erreur lors de la modification de l\'expérience.';
        }
    }
}

// Supprimer une expérience
if ($action === 'delete' && $experience_id) {
    try {
        $stmt = $pdo->prepare("DELETE FROM experiences WHERE id = ?");
        $stmt->execute([$experience_id]);
        
        $success_message = 'Expérience supprimée avec succès !';
        $action = 'list';
    } catch (PDOException $e) {
        $error_message = 'Erreur lors de la suppression de l\'expérience.';
        $action = 'list';
    }
}

// Récupérer les données selon l'action
if ($action === 'edit' && $experience_id) {
    $stmt = $pdo->prepare("SELECT * FROM experiences WHERE id = ?");
    $stmt->execute([$experience_id]);
    $experience = $stmt->fetch();
    
    if (!$experience) {
        $error_message = 'Expérience non trouvée.';
        $action = 'list';
    }
}

if ($action === 'list') {
    $experiences = $pdo->query("SELECT * FROM experiences ORDER BY start_date DESC")->fetchAll();
}
?>
<!DOCTYPE html>
<html lang="fr">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Administration - Expériences</title>
    
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
                    <li><a href="experiences.php" class="active"><i class="fas fa-briefcase"></i> Expériences</a></li>
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
                            Ajouter une Expérience
                        <?php elseif ($action === 'edit'): ?>
                            Modifier une Expérience
                        <?php else: ?>
                            Gestion des Expériences
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
                <!-- Liste des expériences -->
                <div class="card">
                    <div class="card-header">
                        <h3>Expériences Professionnelles</h3>
                        <a href="?action=add" class="btn btn-primary">
                            <i class="fas fa-plus"></i> Ajouter une expérience
                        </a>
                    </div>
                    <div class="card-body">
                        <?php if (empty($experiences)): ?>
                            <p class="text-center">Aucune expérience trouvée.</p>
                        <?php else: ?>
                        <div class="table-responsive">
                            <table class="table">
                                <thead>
                                    <tr>
                                        <th>Logo</th>
                                        <th>Poste</th>
                                        <th>Entreprise</th>
                                        <th>Période</th>
                                        <th>Lieu</th>
                                        <th>Actuel</th>
                                        <th>Actions</th>
                                    </tr>
                                </thead>
                                <tbody>
                                    <?php foreach ($experiences as $experience): ?>
                                    <tr>
                                        <td>
                                            <?php if (!empty($experience['logo'])): ?>
                                                <img src="../<?php echo htmlspecialchars($experience['logo']); ?>" alt="<?php echo htmlspecialchars($experience['company']); ?>" style="width: 40px; height: 40px; object-fit: contain; border-radius: 4px;">
                                            <?php else: ?>
                                                <div style="width: 40px; height: 40px; background: var(--bg-tertiary); border-radius: 4px; display: flex; align-items: center; justify-content: center; color: var(--text-muted);">
                                                    <i class="fas fa-building"></i>
                                                </div>
                                            <?php endif; ?>
                                        </td>
                                        <td>
                                            <strong><?php echo htmlspecialchars($experience['title']); ?></strong>
                                        </td>
                                        <td><?php echo htmlspecialchars($experience['company']); ?></td>
                                        <td>
                                            <?php echo formatPeriod($experience['start_date'], $experience['end_date'], $experience['current_position']); ?>
                                        </td>
                                        <td><?php echo htmlspecialchars($experience['location']); ?></td>
                                        <td>
                                            <?php if ($experience['current_position']): ?>
                                                <span style="color: var(--success);"><i class="fas fa-clock"></i> Oui</span>
                                            <?php else: ?>
                                                <span style="color: var(--text-secondary);">Non</span>
                                            <?php endif; ?>
                                        </td>
                                        <td>
                                            <div class="table-actions">
                                                <a href="?action=edit&id=<?php echo $experience['id']; ?>" class="btn btn-sm btn-warning">
                                                    <i class="fas fa-edit"></i>
                                                </a>
                                                <a href="?action=delete&id=<?php echo $experience['id']; ?>" class="btn btn-sm btn-danger" onclick="return confirm('Êtes-vous sûr de vouloir supprimer cette expérience ?')">
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
                            <?php echo $action === 'add' ? 'Ajouter' : 'Modifier'; ?> une expérience
                        </h3>
                        <a href="?action=list" class="btn btn-secondary">
                            <i class="fas fa-arrow-left"></i> Retour
                        </a>
                    </div>
                    <div class="card-body">
                        <form method="POST" enctype="multipart/form-data">
                            <?php if (isset($experience) && !empty($experience['logo'])): ?>
                            <input type="hidden" name="current_logo" value="<?php echo htmlspecialchars($experience['logo']); ?>">
                            <?php endif; ?>
                            <div class="form-row">
                                <div class="form-group">
                                    <label for="title">Poste *</label>
                                    <input type="text" id="title" name="title" required value="<?php echo isset($experience) ? htmlspecialchars($experience['title']) : ''; ?>">
                                </div>
                                <div class="form-group">
                                    <label for="company">Entreprise *</label>
                                    <input type="text" id="company" name="company" required value="<?php echo isset($experience) ? htmlspecialchars($experience['company']) : ''; ?>">
                                </div>
                            </div>
                            
                            <div class="form-row">
                                <div class="form-group">
                                    <label for="location">Lieu</label>
                                    <input type="text" id="location" name="location" value="<?php echo isset($experience) ? htmlspecialchars($experience['location']) : ''; ?>">
                                </div>
                                <div class="form-group">
                                    <label for="logo">Logo de l'entreprise</label>
                                    <div class="logo-upload-container">
                                        <input type="file" id="logo" name="logo" accept="image/*" onchange="previewLogo(this)">
                                        <div class="logo-preview" id="logoPreview" style="display: none;">
                                            <img id="previewImg" src="" alt="Aperçu du logo">
                                            <button type="button" onclick="removeLogo()" class="btn-remove-logo">
                                                <i class="fas fa-times"></i>
                                            </button>
                                        </div>
                                        <?php if (isset($experience) && !empty($experience['logo'])): ?>
                                        <div class="current-logo">
                                            <p>Logo actuel :</p>
                                            <img src="../<?php echo htmlspecialchars($experience['logo']); ?>" alt="Logo actuel" style="width: 60px; height: 60px; object-fit: contain; border-radius: 4px; border: 1px solid var(--border);">
                                        </div>
                                        <?php endif; ?>
                                    </div>
                                    <small class="form-help">Formats acceptés : JPG, PNG, GIF, WebP (max 2MB)</small>
                                </div>
                            </div>
                            
                            <div class="form-row">
                                <div class="form-group">
                                    <label for="sort_order">Ordre d'affichage</label>
                                    <input type="number" id="sort_order" name="sort_order" value="<?php echo isset($experience) ? $experience['sort_order'] : '0'; ?>">
                                </div>
                            </div>
                            
                            <div class="form-row">
                                <div class="form-group">
                                    <label for="start_date">Date de début *</label>
                                    <input type="date" id="start_date" name="start_date" required value="<?php echo isset($experience) ? $experience['start_date'] : ''; ?>">
                                </div>
                                <div class="form-group">
                                    <label for="end_date">Date de fin</label>
                                    <input type="date" id="end_date" name="end_date" value="<?php echo isset($experience) ? $experience['end_date'] : ''; ?>">
                                </div>
                            </div>
                            
                            <div class="checkbox-group">
                                <input type="checkbox" id="current_position" name="current_position" <?php echo (isset($experience) && $experience['current_position']) ? 'checked' : ''; ?>>
                                <label for="current_position">Poste actuel</label>
                            </div>
                            
                            <div class="form-group">
                                <label for="description">Description *</label>
                                <textarea id="description" name="description" rows="5" required><?php echo isset($experience) ? htmlspecialchars($experience['description']) : ''; ?></textarea>
                            </div>
                            
                            <div class="form-group">
                                <label for="technologies">Technologies utilisées</label>
                                <input type="text" id="technologies" name="technologies" placeholder="ex: PHP, JavaScript, MySQL..." value="<?php echo isset($experience) ? htmlspecialchars($experience['technologies']) : ''; ?>">
                            </div>
                            
                            <div style="margin-top: 2rem;">
                                <button type="submit" name="<?php echo $action === 'add' ? 'add_experience' : 'edit_experience'; ?>" class="btn btn-primary">
                                    <i class="fas fa-save"></i>
                                    <?php echo $action === 'add' ? 'Ajouter' : 'Modifier'; ?> l'expérience
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

    <!-- PWA Installation Script -->
    <script src="pwa-install.js"></script>
    
    <!-- Admin JavaScript -->
    <script src="../assets/js/admin.js"></script>
</body>
</html>
