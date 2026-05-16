<?php
session_start();
require_once "config/db.php";

if (!isset($_SESSION['user_id'])) {
    header("Location: login.php");
    exit();
}

$isAdmin = (isset($_SESSION['role']) && $_SESSION['role'] === 'admin');
$nom = htmlspecialchars($_SESSION['nom']);

if ($isAdmin) {
    $nb_meds    = $pdo->query("SELECT COUNT(*) FROM medicaments")->fetchColumn();
    $nb_cats    = $pdo->query("SELECT COUNT(*) FROM categories")->fetchColumn();
    $nb_cmd     = $pdo->query("SELECT COUNT(*) FROM commandes WHERE statut='en_attente'")->fetchColumn();
    $nb_rsv     = $pdo->query("SELECT COUNT(*) FROM reservations WHERE statut='en_attente'")->fetchColumn();
    $nb_clients = $pdo->query("SELECT COUNT(*) FROM utilisateurs WHERE role='client'")->fetchColumn();
}

$stmt = $pdo->prepare("SELECT COUNT(*) FROM commandes WHERE utilisateur_id = ?");
$stmt->execute([$_SESSION['user_id']]);
$mes_cmd = $stmt->fetchColumn();

$stmt2 = $pdo->prepare("SELECT COUNT(*) FROM reservations WHERE utilisateur_id = ?");
$stmt2->execute([$_SESSION['user_id']]);
$mes_rsv = $stmt2->fetchColumn();
?>
<!DOCTYPE html>
<html lang="fr">
<head>
    <meta charset="UTF-8">
    <title>Dashboard - PharmaConnect</title>
    <link rel="stylesheet" href="css/style.css">
</head>
<body>

<?php $root = ''; require_once "includes/navbar.php"; ?>

<div class="container">
    <h1>Bienvenue, <?= $nom ?></h1>

    <?php if ($isAdmin): ?>
    <h2>Espace Administration</h2>

    <div class="stats-box">
        <div class="stat-item">
            <p>Medicaments</p>
            <h3><?= $nb_meds ?></h3>
        </div>
        <div class="stat-item">
            <p>Categories</p>
            <h3><?= $nb_cats ?></h3>
        </div>
        <div class="stat-item">
            <p>Commandes en attente</p>
            <h3><?= $nb_cmd ?></h3>
        </div>
        <div class="stat-item">
            <p>Reservations en attente</p>
            <h3><?= $nb_rsv ?></h3>
        </div>
        <div class="stat-item">
            <p>Clients</p>
            <h3><?= $nb_clients ?></h3>
        </div>
    </div>

    <div class="liens-actions">
        <a href="admin/medicaments/liste.php" class="btn">Gerer Medicaments</a>
        <a href="admin/categories/liste.php" class="btn">Gerer Categories</a>
        <a href="admin/commandes/liste.php" class="btn">Gerer Commandes</a>
        <a href="admin/reservations/liste.php" class="btn">Gerer Reservations</a>
        <a href="admin/clients/liste.php" class="btn">Gerer Clients</a>
        <a href="admin/statistiques.php" class="btn">Statistiques</a>
    </div>

    <hr>
    <?php endif; ?>

    <h2>Espace Client</h2>
    <p>Commandes passees : <strong><?= $mes_cmd ?></strong> &nbsp;|&nbsp; Reservations : <strong><?= $mes_rsv ?></strong></p>

    <div class="liens-actions" style="margin-top:15px;">
        <a href="liste.php" class="btn">Voir les medicaments</a>
        <a href="rechercher.php" class="btn">Rechercher</a>
        <a href="commander/panier.php" class="btn">Mon panier</a>
        <a href="reservations/mes-reservations.php" class="btn">Mes reservations</a>
        <a href="mon-compte.php" class="btn">Mon compte</a>
    </div>
</div>

</body>
</html>
