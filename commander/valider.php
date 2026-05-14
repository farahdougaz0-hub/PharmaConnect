<?php
session_start();
require_once __DIR__ . "/../config/db.php";


if (empty($_SESSION['panier'])) {
    header("Location: panier.php");
    exit();
}

if (!isset($_SESSION['user_id'])) {
    header("Location: ../login.php");
    exit();
}

$stmt = $pdo->prepare("INSERT INTO commandes(utilisateur_id) VALUES(?)");
$stmt->execute([$_SESSION['user_id']]);
$commande_id = $pdo->lastInsertId();

foreach ($_SESSION['panier'] as $p) {
    $pdo->prepare("
        INSERT INTO details_commande(commande_id, medicament_id, quantite, prix)
        VALUES(?,?,?,?)
    ")->execute([$commande_id, $p['id'], $p['qte'] ?? 1, $p['prix']]);
}

$_SESSION['panier'] = [];
?>
<!DOCTYPE html>
<html>
<head>
    <title>Commande validée</title>
    <link rel="stylesheet" href="../css/style.css">
</head>
<body>
<div class="container">
    <h1 style="color:#27ae60;">Commande validée avec succès !</h1>
    <p style="text-align:center;">Votre commande #<?= $commande_id ?> a été enregistrée.</p>
    <div class="actions">
        <a href="../dashboard.php"> Retour au Dashboard</a>
    </div>
</div>
</body>
</html>
