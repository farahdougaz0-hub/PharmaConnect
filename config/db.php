<?php
// config/db.php — Connexion PDO unique pour tout le projet

define('DB_HOST', 'localhost');
define('DB_NAME', 'pharmaconnect');
define('DB_USER', 'root');
define('DB_PASS', '');
define('DB_CHARSET', 'utf8mb4');

try {
    $pdo = new PDO(
        "mysql:host=" . DB_HOST . ";dbname=" . DB_NAME . ";charset=" . DB_CHARSET,
        DB_USER,
        DB_PASS,
        [
            PDO::ATTR_ERRMODE            => PDO::ERRMODE_EXCEPTION,
            PDO::ATTR_DEFAULT_FETCH_MODE => PDO::FETCH_ASSOC,
            PDO::ATTR_EMULATE_PREPARES   => false,
        ]
    );
    // Alias $conn pour compatibilité avec l'ancien code
    $conn = $pdo;
} catch (PDOException $e) {
    die("<div style='background:#c0392b;color:white;padding:20px;font-family:sans-serif;'>
        <h2>❌ Erreur de connexion à la base de données</h2>
        <p>" . htmlspecialchars($e->getMessage()) . "</p>
        <p>Vérifiez que MySQL est démarré et que la base <strong>pharmaconnect</strong> existe.</p>
        <p><a href='../pharmaconnect.sql' style='color:#fff'>📄 Télécharger le script SQL</a></p>
    </div>");
}
?>
