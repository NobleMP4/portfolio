<?php
session_start();
require_once '../config/database.php';
require_once '../includes/functions.php';

requireLogin();

$action = $_GET['action'] ?? 'list';
$formation_id = $_GET['id'] ?? null;
$success_message = '';
$error_message = '';

// Traitement des actions
if ($_POST) {
    if (isset($_POST['add_formation'])) {
        $title = clean_for_database($_POST['title']);
        $school = clean_for_database($_POST['school']);
        $location = clean_for_database($_POST['location']);
        $diploma = clean_for_database($_POST['diploma']);
        $start_date = $_POST['start_date'];
        $end_date = $_POST['end_date'] ?: null;
        $current_formation = isset($_POST['current_formation']) ? 1 : 0;
        $description = clean_for_database($_POST['description']);
        $sort_order = (int)$_POST['sort_order'];
        
        // Gestion du logo
        $logo = null;
        if (isset($_FILES['logo']) && $_FILES['logo']['error'] === UPLOAD_ERR_OK) {
            $logo = uploadFormationImage($_FILES['logo']);
            if (!$logo) {
                $error_message = 'Erreur lors de l\'upload du logo.';
            }
        }
        
        if (!$error_message) {
            try {
                $sql = "INSERT INTO formations (title, school, logo, location, diploma, start_date, end_date, current_formation, description, sort_order) VALUES (?, ?, ?, ?, ?, ?, ?, ?, ?, ?)";
                $stmt = $pdo->prepare($sql);
                $stmt->execute([$title, $school, $logo, $location, $diploma, $start_date, $end_date, $current_formation, $description, $sort_order]);
                
                $success_message = 'Formation ajoutée avec succès !';
                $action = 'list';
            } catch (PDOException $e) {
                $error_message = 'Erreur lors de l\'ajout de la formation.';
            }
        }
    }
    
    if (isset($_POST['edit_formation'])) {
        $title = clean_for_database($_POST['title']);
        $school = clean_for_database($_POST['school']);
        $location = clean_for_database($_POST['location']);
        $diploma = clean_for_database($_POST['diploma']);
        $start_date = $_POST['start_date'];
        $end_date = $_POST['end_date'] ?: null;
        $current_formation = isset($_POST['current_formation']) ? 1 : 0;
        $description = clean_for_database($_POST['description']);
        $sort_order = (int)$_POST['sort_order'];
        $current_logo = $_POST['current_logo'] ?? null;
        
        // Gestion du logo
        $logo = $current_logo;
        if (isset($_FILES['logo']) && $_FILES['logo']['error'] === UPLOAD_ERR_OK) {
            // Supprimer l'ancien logo si il existe
            if ($current_logo) {
                deleteFormationImage($current_logo);
            }
            
            $logo = uploadFormationImage($_FILES['logo'], $formation_id);
            if (!$logo) {
                $error_message = 'Erreur lors de l\'upload du logo.';
            }
        }
        
        if (!$error_message) {
            try {
                $sql = "UPDATE formations SET title = ?, school = ?, logo = ?, location = ?, diploma = ?, start_date = ?, end_date = ?, current_formation = ?, description = ?, sort_order = ? WHERE id = ?";
                $stmt = $pdo->prepare($sql);
                $stmt->execute([$title, $school, $logo, $location, $diploma, $start_date, $end_date, $current_formation, $description, $sort_order, $formation_id]);
                
                $success_message = 'Formation modifiée avec succès !';
                $action = 'list';
            } catch (PDOException $e) {
                $error_message = 'Erreur lors de la modification de la formation.';
            }
        }
    }
}

// Supprimer une formation
if ($action === 'delete' && $formation_id) {
    try {
        // Récupérer le logo avant suppression
        $stmt = $pdo->prepare("SELECT logo FROM formations WHERE id = ?");
        $stmt->execute([$formation_id]);
        $formation = $stmt->fetch();
        
        // Supprimer la formation
        $stmt = $pdo->prepare("DELETE FROM formations WHERE id = ?");
        $stmt->execute([$formation_id]);
        
        // Supprimer le logo si il existe
        if ($formation && $formation['logo']) {
            deleteFormationImage($formation['logo']);
        }
        
        $success_message = 'Formation supprimée avec succès !';
        $action = 'list';
    } catch (PDOException $e) {
        $error_message = 'Erreur lors de la suppression de la formation.';
        $action = 'list';
    }
}

// Récupérer les données selon l'action
if ($action === 'edit' && $formation_id) {
    $stmt = $pdo->prepare("SELECT * FROM formations WHERE id = ?");
    $stmt->execute([$formation_id]);
    $formation = $stmt->fetch();
    
    if (!$formation) {
        $error_message = 'Formation non trouvée.';
        $action = 'list';
    }
}

if ($action === 'list') {
    $formations = $pdo->query("SELECT * FROM formations ORDER BY start_date DESC")->fetchAll();
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
                    <li><a href="index.php"><i class="fas fa-tachometer-alt"></i> Dashboard</a></li>
                    <li><a href="projects.php"><i class="fas fa-folder"></i> Projets</a></li>
                    <li><a href="experiences.php"><i class="fas fa-briefcase"></i> Expériences</a></li>
                    <li><a href="formations.php" class="active"><i class="fas fa-graduation-cap"></i> Formations</a></li>
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
                            Ajouter une Formation
                        <?php elseif ($action === 'edit'): ?>
                            Modifier une Formation
                        <?php else: ?>
                            Gestion des Formations
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
                <!-- Liste des formations -->
                <div class="card">
                    <div class="card-header">
                        <h3>Formations</h3>
                        <a href="?action=add" class="btn btn-primary">
                            <i class="fas fa-plus"></i> Ajouter une formation
                        </a>
                    </div>
                    <div class="card-body">
                        <?php if (empty($formations)): ?>
                            <p class="text-center">Aucune formation trouvée.</p>
                        <?php else: ?>
                        <div class="table-responsive">
                            <table class="table">
                                <thead>
                                    <tr>
                                        <th>Formation</th>
                                        <th>École</th>
                                        <th>Logo</th>
                                        <th>Période</th>
                                        <th>Diplôme</th>
                                        <th>Actions</th>
                                    </tr>
                                </thead>
                                <tbody>
                                    <?php foreach ($formations as $formation): ?>
                                    <tr>
                                        <td>
                                            <strong><?php echo htmlspecialchars($formation['title']); ?></strong>
                                        </td>
                                        <td>
                                            <?php echo htmlspecialchars($formation['school']); ?>
                                            <?php if (!empty($formation['location'])): ?>
                                                <br><small style="color: var(--text-secondary);"><?php echo htmlspecialchars($formation['location']); ?></small>
                                            <?php endif; ?>
                                        </td>
                                        <td>
                                            <?php if (!empty($formation['logo'])): ?>
                                                <img src="../<?php echo htmlspecialchars($formation['logo']); ?>" alt="Logo" style="width: 40px; height: 40px; object-fit: contain; border: 1px solid var(--border); border-radius: 4px;">
                                            <?php else: ?>
                                                <span style="color: var(--text-secondary); font-size: 0.8rem;">Aucun logo</span>
                                            <?php endif; ?>
                                        </td>
                                        <td>
                                            <?php echo formatPeriod($formation['start_date'], $formation['end_date'], $formation['current_formation']); ?>
                                        </td>
                                        <td><?php echo htmlspecialchars($formation['diploma']); ?></td>
                                        <td>
                                            <div class="table-actions">
                                                <a href="?action=edit&id=<?php echo $formation['id']; ?>" class="btn btn-sm btn-warning">
                                                    <i class="fas fa-edit"></i>
                                                </a>
                                                <a href="?action=delete&id=<?php echo $formation['id']; ?>" class="btn btn-sm btn-danger" onclick="return confirm('Êtes-vous sûr de vouloir supprimer cette formation ?')">
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
                            <?php echo $action === 'add' ? 'Ajouter' : 'Modifier'; ?> une formation
                        </h3>
                        <a href="?action=list" class="btn btn-secondary">
                            <i class="fas fa-arrow-left"></i> Retour
                        </a>
                    </div>
                    <div class="card-body">
                        <form method="POST" enctype="multipart/form-data">
                            <div class="form-row">
                                <div class="form-group">
                                    <label for="title">Formation *</label>
                                    <input type="text" id="title" name="title" required value="<?php echo isset($formation) ? htmlspecialchars($formation['title']) : ''; ?>">
                                </div>
                                <div class="form-group">
                                    <label for="school">École/Organisme *</label>
                                    <input type="text" id="school" name="school" required value="<?php echo isset($formation) ? htmlspecialchars($formation['school']) : ''; ?>">
                                </div>
                            </div>
                            
                            <div class="form-group">
                                <label for="logo">Logo de l'école/formation</label>
                                <input type="file" id="logo" name="logo" accept="image/*" onchange="previewFormationLogo(this)">
                                <?php if (isset($formation) && !empty($formation['logo'])): ?>
                                <input type="hidden" name="current_logo" value="<?php echo htmlspecialchars($formation['logo']); ?>">
                                <div class="current-logo">
                                    <p>Logo actuel :</p>
                                    <img src="../<?php echo htmlspecialchars($formation['logo']); ?>" alt="Logo actuel" style="width: 80px; height: 80px; object-fit: contain; border: 2px solid var(--border); border-radius: 8px;">
                                </div>
                                <?php endif; ?>
                                <div id="logoPreview" class="logo-preview" style="display: none;">
                                    <img id="previewImg" src="" alt="Aperçu" style="width: 80px; height: 80px; object-fit: contain; border: 2px solid var(--border); border-radius: 8px;">
                                    <button type="button" class="btn-remove-logo" onclick="removeFormationLogo()">×</button>
                                </div>
                            </div>
                            
                            <div class="form-row">
                                <div class="form-group">
                                    <label for="location">Lieu</label>
                                    <input type="text" id="location" name="location" value="<?php echo isset($formation) ? htmlspecialchars($formation['location']) : ''; ?>">
                                </div>
                                <div class="form-group">
                                    <label for="diploma">Diplôme obtenu</label>
                                    <input type="text" id="diploma" name="diploma" value="<?php echo isset($formation) ? htmlspecialchars($formation['diploma']) : ''; ?>">
                                </div>
                            </div>
                            
                            <div class="form-row">
                                <div class="form-group">
                                    <label for="start_date">Date de début *</label>
                                    <input type="date" id="start_date" name="start_date" required value="<?php echo isset($formation) ? $formation['start_date'] : ''; ?>">
                                </div>
                                <div class="form-group">
                                    <label for="end_date">Date de fin</label>
                                    <input type="date" id="end_date" name="end_date" value="<?php echo isset($formation) ? $formation['end_date'] : ''; ?>">
                                </div>
                            </div>
                            
                            <div class="form-row">
                                <div class="form-group">
                                    <label for="sort_order">Ordre d'affichage</label>
                                    <input type="number" id="sort_order" name="sort_order" value="<?php echo isset($formation) ? $formation['sort_order'] : '0'; ?>">
                                </div>
                                <div class="checkbox-group" style="display: flex; align-items: center; margin-top: 2rem;">
                                    <input type="checkbox" id="current_formation" name="current_formation" <?php echo (isset($formation) && $formation['current_formation']) ? 'checked' : ''; ?>>
                                    <label for="current_formation">Formation en cours</label>
                                </div>
                            </div>
                            
                            <div class="form-group">
                                <label for="description">Description</label>
                                <textarea id="description" name="description" rows="4"><?php echo isset($formation) ? htmlspecialchars($formation['description']) : ''; ?></textarea>
                            </div>
                            
                            <div style="margin-top: 2rem;">
                                <button type="submit" name="<?php echo $action === 'add' ? 'add_formation' : 'edit_formation'; ?>" class="btn btn-primary">
                                    <i class="fas fa-save"></i>
                                    <?php echo $action === 'add' ? 'Ajouter' : 'Modifier'; ?> la formation
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
    <script>
        function previewFormationLogo(input) {
            const preview = document.getElementById('logoPreview');
            const previewImg = document.getElementById('previewImg');

            if (input.files && input.files[0]) {
                const reader = new FileReader();

                reader.onload = function(e) {
                    previewImg.src = e.target.result;
                    preview.style.display = 'inline-block';
                }

                reader.readAsDataURL(input.files[0]);
            }
        }

        function removeFormationLogo() {
            const input = document.getElementById('logo');
            const preview = document.getElementById('logoPreview');

            input.value = '';
            preview.style.display = 'none';
        }
    </script>
    <script src="favicon-theme.js"></script>
</body>
</html>
