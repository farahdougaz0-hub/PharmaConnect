<?php
session_start();
require_once __DIR__ . "/../config/db.php";

if (!isset($_SESSION['user_id'])) {
    header("Location: ../login.php");
    exit();
}

if (empty($_SESSION['panier'])) {
    header("Location: panier.php");
    exit();
}

$total = 0;
foreach ($_SESSION['panier'] as $i) {
    $total += $i['prix'] * ($i['qte'] ?? 1);
}

$stmt = $pdo->prepare("INSERT INTO commandes (utilisateur_id, total) VALUES (?, ?)");
$stmt->execute([$_SESSION['user_id'], $total]);
$commande_id = $pdo->lastInsertId();

foreach ($_SESSION['panier'] as $p) {
    $pdo->prepare("INSERT INTO details_commande (commande_id, medicament_id, quantite, prix) VALUES (?,?,?,?)")
        ->execute([$commande_id, $p['id'], $p['qte'] ?? 1, $p['prix']]);

    $pdo->prepare("UPDATE medicaments SET quantite = quantite - ? WHERE id = ?")
        ->execute([$p['qte'] ?? 1, $p['id']]);
}

$_SESSION['panier'] = [];
?>
<!DOCTYPE html>
<html lang="fr">
<head>
    <meta charset="UTF-8">
    <title>Commande validee - PharmaConnect</title>
    <link rel="stylesheet" href="../css/style.css">
</head>
<body>

<?php $root = '../'; require_once "../includes/navbar.php"; ?>

<div class="container">
    <h1>Commande validee</h1>
    <p class="message-succes">
        Votre commande numero <?= $commande_id ?> a ete enregistree avec succes.<br>
        Total : <?= number_format($total, 2) ?> DT
    </p>
    <div class="liens-actions">
        <a href="../dashboard.php" class="btn">Retour au dashboard</a>
        <a href="../liste.php" class="btn">Continuer les achats</a>
    </div>
</div>

</body>
</html>
