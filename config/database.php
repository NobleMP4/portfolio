<?php
/**
 * Configuration de la base de données
 * Compatible MAMP et serveur de production
 */

// Configuration pour MAMP (développement local)
if ($_SERVER['SERVER_NAME'] == 'localhost' || $_SERVER['SERVER_NAME'] == '127.0.0.1') {
    $host = 'localhost';
    $port = '8889'; // Port par défaut MAMP
    $dbname = 'portfolio';
    $username = 'root';
    $password = 'root'; // Mot de passe par défaut MAMP
} else {
    // Configuration pour serveur de production
    $host = 'localhost';
    $port = '3306';
    $dbname = 'portfolio';
    $username = 'your_username';
    $password = 'your_password';
}

try {
    $dsn = "mysql:host=$host;port=$port;dbname=$dbname;charset=utf8mb4";
    $options = [
        PDO::ATTR_ERRMODE => PDO::ERRMODE_EXCEPTION,
        PDO::ATTR_DEFAULT_FETCH_MODE => PDO::FETCH_ASSOC,
        PDO::ATTR_EMULATE_PREPARES => false,
    ];
    
    $pdo = new PDO($dsn, $username, $password, $options);
} catch (PDOException $e) {
    die('Erreur de connexion à la base de données : ' . $e->getMessage());
}

/**
 * Fonction pour tester la connexion à la base de données
 */
function testDatabaseConnection() {
    global $pdo;
    try {
        $stmt = $pdo->query('SELECT 1');
        return true;
    } catch (PDOException $e) {
        return false;
    }
}
?>
