<?php
/**
 * Fonctions utilitaires pour le portfolio
 */

/**
 * Sécuriser les données d'entrée pour l'affichage
 */
function sanitize_input($data) {
    $data = trim($data);
    $data = stripslashes($data);
    $data = htmlspecialchars($data, ENT_QUOTES | ENT_HTML5, 'UTF-8', false);
    return $data;
}

/**
 * Nettoyer les données pour la base de données (sans échappement HTML)
 */
function clean_for_database($data) {
    $data = trim($data);
    $data = stripslashes($data);
    // Ne pas échapper les apostrophes pour la base de données
    return $data;
}

/**
 * Valider une adresse email
 */
function validate_email($email) {
    return filter_var($email, FILTER_VALIDATE_EMAIL);
}

/**
 * Générer un token CSRF
 */
function generate_csrf_token() {
    if (!isset($_SESSION['csrf_token'])) {
        $_SESSION['csrf_token'] = bin2hex(random_bytes(32));
    }
    return $_SESSION['csrf_token'];
}

/**
 * Vérifier un token CSRF
 */
function verify_csrf_token($token) {
    return isset($_SESSION['csrf_token']) && hash_equals($_SESSION['csrf_token'], $token);
}

/**
 * Récupérer toutes les compétences
 */
function getSkills($pdo, $limit = null, $featured_only = false) {
    $sql = "SELECT * FROM skills";
    $params = [];
    
    if ($featured_only) {
        $sql .= " WHERE featured = 1";
    }
    
    $sql .= " ORDER BY sort_order ASC, name ASC";
    
    if ($limit) {
        $sql .= " LIMIT :limit";
        $params['limit'] = $limit;
    }
    
    $stmt = $pdo->prepare($sql);
    
    if ($limit) {
        $stmt->bindValue(':limit', $limit, PDO::PARAM_INT);
    }
    
    $stmt->execute();
    return $stmt->fetchAll();
}

/**
 * Récupérer tous les projets
 */
function getProjects($pdo, $limit = null, $featured_only = false) {
    $sql = "SELECT * FROM projects";
    $params = [];
    
    if ($featured_only) {
        $sql .= " WHERE featured = 1";
    }
    
    $sql .= " ORDER BY sort_order ASC, created_at DESC";
    
    if ($limit) {
        $sql .= " LIMIT :limit";
        $params['limit'] = $limit;
    }
    
    $stmt = $pdo->prepare($sql);
    
    if ($limit) {
        $stmt->bindValue(':limit', $limit, PDO::PARAM_INT);
    }
    
    $stmt->execute();
    return $stmt->fetchAll();
}

/**
 * Récupérer les projets récents
 */
function getRecentProjects($pdo, $limit = 3) {
    return getProjects($pdo, $limit, false);
}

/**
 * Récupérer toutes les expériences
 */
function getExperiences($pdo) {
    $sql = "SELECT * FROM experiences ORDER BY start_date DESC, sort_order ASC";
    $stmt = $pdo->prepare($sql);
    $stmt->execute();
    return $stmt->fetchAll();
}

/**
 * Récupérer toutes les formations
 */
function getFormations($pdo) {
    $sql = "SELECT * FROM formations ORDER BY start_date DESC, sort_order ASC";
    $stmt = $pdo->prepare($sql);
    $stmt->execute();
    return $stmt->fetchAll();
}

/**
 * Formater une date
 */
function formatDate($date, $format = 'd/m/Y') {
    if (empty($date)) return '';
    return date($format, strtotime($date));
}

/**
 * Formater une période (date début - date fin)
 */
function formatPeriod($start_date, $end_date = null, $current = false) {
    $start = formatDate($start_date, 'm/Y');
    
    if ($current || empty($end_date)) {
        return $start . ' - Aujourd\'hui';
    }
    
    $end = formatDate($end_date, 'm/Y');
    return $start . ' - ' . $end;
}

/**
 * Upload d'image
 */
function uploadImage($file, $upload_dir = 'assets/images/uploads/') {
    // Vérifier si le dossier existe
    if (!file_exists($upload_dir)) {
        mkdir($upload_dir, 0777, true);
    }
    
    $allowed_types = ['jpg', 'jpeg', 'png', 'gif', 'webp'];
    $max_size = 5 * 1024 * 1024; // 5MB
    
    $file_extension = strtolower(pathinfo($file['name'], PATHINFO_EXTENSION));
    
    // Vérifications
    if ($file['error'] !== UPLOAD_ERR_OK) {
        throw new Exception('Erreur lors de l\'upload du fichier.');
    }
    
    if ($file['size'] > $max_size) {
        throw new Exception('Le fichier est trop volumineux (max 5MB).');
    }
    
    if (!in_array($file_extension, $allowed_types)) {
        throw new Exception('Type de fichier non autorisé.');
    }
    
    // Générer un nom unique
    $filename = uniqid() . '.' . $file_extension;
    $filepath = $upload_dir . $filename;
    
    if (move_uploaded_file($file['tmp_name'], $filepath)) {
        return $filepath;
    } else {
        throw new Exception('Impossible de déplacer le fichier uploadé.');
    }
}

/**
 * Supprimer une image
 */
function deleteImage($filepath) {
    if (file_exists($filepath) && !empty($filepath)) {
        return unlink($filepath);
    }
    return false;
}

/**
 * Vérifier si l'utilisateur est connecté
 */
function isLoggedIn() {
    return isset($_SESSION['admin_logged_in']) && $_SESSION['admin_logged_in'] === true;
}

/**
 * Rediriger si non connecté
 */
function requireLogin() {
    if (!isLoggedIn()) {
        header('Location: login.php');
        exit();
    }
}

/**
 * Envoyer un email (configuration basique)
 */
function sendEmail($to, $subject, $message, $from_name = 'Portfolio', $from_email = 'noreply@portfolio.com') {
    $headers = [
        'From' => "$from_name <$from_email>",
        'Reply-To' => $from_email,
        'Content-Type' => 'text/html; charset=UTF-8',
        'X-Mailer' => 'PHP/' . phpversion()
    ];
    
    $headers_string = '';
    foreach ($headers as $key => $value) {
        $headers_string .= "$key: $value\r\n";
    }
    
    return mail($to, $subject, $message, $headers_string);
}

/**
 * Pagination
 */
function paginate($total_items, $items_per_page, $current_page) {
    $total_pages = ceil($total_items / $items_per_page);
    $offset = ($current_page - 1) * $items_per_page;
    
    return [
        'total_pages' => $total_pages,
        'current_page' => $current_page,
        'offset' => $offset,
        'limit' => $items_per_page,
        'has_previous' => $current_page > 1,
        'has_next' => $current_page < $total_pages
    ];
}

/**
 * Upload d'image pour les formations
 */
function uploadFormationImage($file, $formation_id = null) {
    if (!isset($file) || $file['error'] !== UPLOAD_ERR_OK) {
        return false;
    }
    
    $allowed_types = ['image/jpeg', 'image/png', 'image/gif', 'image/webp'];
    if (!in_array($file['type'], $allowed_types)) {
        return false;
    }
    
    $max_size = 2 * 1024 * 1024; // 2MB
    if ($file['size'] > $max_size) {
        return false;
    }
    
    $upload_dir = '../assets/images/logos/';
    if (!is_dir($upload_dir)) {
        mkdir($upload_dir, 0755, true);
    }
    
    $extension = pathinfo($file['name'], PATHINFO_EXTENSION);
    $filename = 'formation_' . ($formation_id ? $formation_id . '_' : '') . uniqid() . '.' . $extension;
    $filepath = $upload_dir . $filename;
    
    if (move_uploaded_file($file['tmp_name'], $filepath)) {
        return 'assets/images/logos/' . $filename;
    }
    
    return false;
}

/**
 * Supprimer une image de formation
 */
function deleteFormationImage($image_path) {
    if ($image_path && file_exists('../' . $image_path)) {
        return unlink('../' . $image_path);
    }
    return true;
}
?>
