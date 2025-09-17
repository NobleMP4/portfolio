<?php
session_start();
require_once '../config/database.php';
require_once '../includes/functions.php';

requireLogin();

$action = $_GET['action'] ?? 'list';
$skill_id = $_GET['id'] ?? null;
$success_message = '';
$error_message = '';

// Traitement des actions
if ($_POST) {
    if (isset($_POST['add_skill'])) {
        $name = sanitize_input($_POST['name']);
        $level = (int)$_POST['level'];
        $category = sanitize_input($_POST['category']);
        $icon = sanitize_input($_POST['icon']);
        $featured = isset($_POST['featured']) ? 1 : 0;
        $sort_order = (int)$_POST['sort_order'];
        
        try {
            $sql = "INSERT INTO skills (name, level, category, icon, featured, sort_order) VALUES (?, ?, ?, ?, ?, ?)";
            $stmt = $pdo->prepare($sql);
            $stmt->execute([$name, $level, $category, $icon, $featured, $sort_order]);
            
            $success_message = 'Compétence ajoutée avec succès !';
            $action = 'list';
        } catch (PDOException $e) {
            $error_message = 'Erreur lors de l\'ajout de la compétence.';
        }
    }
    
    if (isset($_POST['edit_skill'])) {
        $name = sanitize_input($_POST['name']);
        $level = (int)$_POST['level'];
        $category = sanitize_input($_POST['category']);
        $icon = sanitize_input($_POST['icon']);
        $featured = isset($_POST['featured']) ? 1 : 0;
        $sort_order = (int)$_POST['sort_order'];
        
        try {
            $sql = "UPDATE skills SET name = ?, level = ?, category = ?, icon = ?, featured = ?, sort_order = ? WHERE id = ?";
            $stmt = $pdo->prepare($sql);
            $stmt->execute([$name, $level, $category, $icon, $featured, $sort_order, $skill_id]);
            
            $success_message = 'Compétence modifiée avec succès !';
            $action = 'list';
        } catch (PDOException $e) {
            $error_message = 'Erreur lors de la modification de la compétence.';
        }
    }
}

// Supprimer une compétence
if ($action === 'delete' && $skill_id) {
    try {
        $stmt = $pdo->prepare("DELETE FROM skills WHERE id = ?");
        $stmt->execute([$skill_id]);
        
        $success_message = 'Compétence supprimée avec succès !';
        $action = 'list';
    } catch (PDOException $e) {
        $error_message = 'Erreur lors de la suppression de la compétence.';
        $action = 'list';
    }
}

// Récupérer les données selon l'action
if ($action === 'edit' && $skill_id) {
    $stmt = $pdo->prepare("SELECT * FROM skills WHERE id = ?");
    $stmt->execute([$skill_id]);
    $skill = $stmt->fetch();
    
    if (!$skill) {
        $error_message = 'Compétence non trouvée.';
        $action = 'list';
    }
}

if ($action === 'list') {
    $skills = $pdo->query("SELECT * FROM skills ORDER BY category ASC, sort_order ASC, name ASC")->fetchAll();
}
?>
<!DOCTYPE html>
<html lang="fr">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Administration - Compétences</title>
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
                    <li><a href="skills.php" class="active"><i class="fas fa-cogs"></i> Compétences</a></li>
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
                            Ajouter une Compétence
                        <?php elseif ($action === 'edit'): ?>
                            Modifier une Compétence
                        <?php else: ?>
                            Gestion des Compétences
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
                <!-- Liste des compétences -->
                <div class="card">
                    <div class="card-header">
                        <h3>Compétences</h3>
                        <a href="?action=add" class="btn btn-primary">
                            <i class="fas fa-plus"></i> Ajouter une compétence
                        </a>
                    </div>
                    <div class="card-body">
                        <?php if (empty($skills)): ?>
                            <p class="text-center">Aucune compétence trouvée.</p>
                        <?php else: ?>
                        <div class="table-responsive">
                            <table class="table">
                                <thead>
                                    <tr>
                                        <th>Nom</th>
                                        <th>Catégorie</th>
                                        <th>Niveau</th>
                                        <th>Icône</th>
                                        <th>Mis en avant</th>
                                        <th>Ordre</th>
                                        <th>Actions</th>
                                    </tr>
                                </thead>
                                <tbody>
                                    <?php foreach ($skills as $skill): ?>
                                    <tr>
                                        <td>
                                            <strong><?php echo htmlspecialchars($skill['name']); ?></strong>
                                        </td>
                                        <td>
                                            <span style="background: var(--bg-tertiary); padding: 0.25rem 0.5rem; border-radius: 4px; font-size: 0.8rem;">
                                                <?php echo htmlspecialchars($skill['category']); ?>
                                            </span>
                                        </td>
                                        <td>
                                            <div style="display: flex; align-items: center; gap: 0.5rem;">
                                                <span><?php echo $skill['level']; ?>%</span>
                                                <div style="width: 50px; height: 4px; background: var(--bg-tertiary); border-radius: 2px; overflow: hidden;">
                                                    <div style="height: 100%; background: var(--accent-blue); width: <?php echo $skill['level']; ?>%; border-radius: 2px;"></div>
                                                </div>
                                            </div>
                                        </td>
                                        <td>
                                            <i class="<?php echo strpos($skill['icon'], 'fa-') === 0 ? 'fas fa-' . $skill['icon'] : 'fab fa-' . $skill['icon']; ?>" style="color: var(--accent-blue);"></i>
                                        </td>
                                        <td>
                                            <?php if ($skill['featured']): ?>
                                                <span style="color: var(--success);"><i class="fas fa-star"></i> Oui</span>
                                            <?php else: ?>
                                                <span style="color: var(--text-secondary);">Non</span>
                                            <?php endif; ?>
                                        </td>
                                        <td><?php echo $skill['sort_order']; ?></td>
                                        <td>
                                            <div class="table-actions">
                                                <a href="?action=edit&id=<?php echo $skill['id']; ?>" class="btn btn-sm btn-warning">
                                                    <i class="fas fa-edit"></i>
                                                </a>
                                                <a href="?action=delete&id=<?php echo $skill['id']; ?>" class="btn btn-sm btn-danger" onclick="return confirm('Êtes-vous sûr de vouloir supprimer cette compétence ?')">
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
                            <?php echo $action === 'add' ? 'Ajouter' : 'Modifier'; ?> une compétence
                        </h3>
                        <a href="?action=list" class="btn btn-secondary">
                            <i class="fas fa-arrow-left"></i> Retour
                        </a>
                    </div>
                    <div class="card-body">
                        <form method="POST">
                            <div class="form-row">
                                <div class="form-group">
                                    <label for="name">Nom *</label>
                                    <input type="text" id="name" name="name" required value="<?php echo isset($skill) ? htmlspecialchars($skill['name']) : ''; ?>">
                                </div>
                                <div class="form-group">
                                    <label for="level">Niveau (0-100) *</label>
                                    <input type="number" id="level" name="level" min="0" max="100" required value="<?php echo isset($skill) ? $skill['level'] : ''; ?>">
                                </div>
                            </div>
                            
                            <div class="form-row">
                                <div class="form-group">
                                    <label for="category">Catégorie *</label>
                                    <select id="category" name="category" required>
                                        <option value="">Sélectionner une catégorie</option>
                                        <option value="Technologies" <?php echo (isset($skill) && $skill['category'] === 'Technologies') ? 'selected' : ''; ?>>Technologies</option>
                                        <option value="Framework et CMS" <?php echo (isset($skill) && $skill['category'] === 'Framework et CMS') ? 'selected' : ''; ?>>Framework et CMS</option>
                                        <option value="Langues" <?php echo (isset($skill) && $skill['category'] === 'Langues') ? 'selected' : ''; ?>>Langues</option>
                                        <option value="Outils" <?php echo (isset($skill) && $skill['category'] === 'Outils') ? 'selected' : ''; ?>>Outils</option>
                                        <option value="Loisirs" <?php echo (isset($skill) && $skill['category'] === 'Loisirs') ? 'selected' : ''; ?>>Loisirs</option>
                                    </select>
                                </div>
                                <div class="form-group">
                                    <label for="icon">Icône Font Awesome</label>
                                    <input type="text" id="icon" name="icon" placeholder="ex: html5, php, github..." value="<?php echo isset($skill) ? htmlspecialchars($skill['icon']) : ''; ?>">
                                </div>
                            </div>
                            
                            <div class="form-group">
                                <label for="sort_order">Ordre d'affichage</label>
                                <input type="number" id="sort_order" name="sort_order" value="<?php echo isset($skill) ? $skill['sort_order'] : '0'; ?>">
                            </div>
                            
                            <div class="checkbox-group">
                                <input type="checkbox" id="featured" name="featured" <?php echo (isset($skill) && $skill['featured']) ? 'checked' : ''; ?>>
                                <label for="featured">Compétence mise en avant</label>
                            </div>
                            
                            <div style="margin-top: 2rem;">
                                <button type="submit" name="<?php echo $action === 'add' ? 'add_skill' : 'edit_skill'; ?>" class="btn btn-primary">
                                    <i class="fas fa-save"></i>
                                    <?php echo $action === 'add' ? 'Ajouter' : 'Modifier'; ?> la compétence
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
