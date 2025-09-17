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
        $sort_order = (int)$_POST['sort_order'];
        
        try {
            $sql = "INSERT INTO experiences (title, company, location, start_date, end_date, current_position, description, technologies, sort_order) VALUES (?, ?, ?, ?, ?, ?, ?, ?, ?)";
            $stmt = $pdo->prepare($sql);
            $stmt->execute([$title, $company, $location, $start_date, $end_date, $current_position, $description, $technologies, $sort_order]);
            
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
        $sort_order = (int)$_POST['sort_order'];
        
        try {
            $sql = "UPDATE experiences SET title = ?, company = ?, location = ?, start_date = ?, end_date = ?, current_position = ?, description = ?, technologies = ?, sort_order = ? WHERE id = ?";
            $stmt = $pdo->prepare($sql);
            $stmt->execute([$title, $company, $location, $start_date, $end_date, $current_position, $description, $technologies, $sort_order, $experience_id]);
            
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
                        <form method="POST">
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
</body>
</html>
