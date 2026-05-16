<?php
session_start();
require_once "../../config/db.php";

if (!isset($_SESSION['user_id']) || $_SESSION['role'] !== 'admin') { header("Location: ../../login.php"); exit(); }

$id = (int)($_GET['id'] ?? 0);
$stmt = $pdo->prepare("SELECT * FROM medicaments WHERE id = ?");
$stmt->execute([$id]);
$med = $stmt->fetch();

if ($med) {
    // Supprimer l'image physique
    if (!empty($med['image'])) {
        $path = "../../uploads/medicaments/" . $med['image'];
        if (file_exists($path)) unlink($path);
    }
    $pdo->prepare("DELETE FROM medicaments WHERE id = ?")->execute([$id]);
}

header("Location: liste.php?msg=deleted");
exit();
