<?php
session_start();
require_once "../config/db.php";

if (!isset($_SESSION['user_id']) || $_SESSION['role'] !== 'admin') {
    header("Location: ../login.php");
    exit();
}

$nb_meds     = $pdo->query("SELECT COUNT(*) FROM medicaments")->fetchColumn();
$nb_cats     = $pdo->query("SELECT COUNT(*) FROM categories")->fetchColumn();
$nb_cmd      = $pdo->query("SELECT COUNT(*) FROM commandes")->fetchColumn();
$nb_rsv      = $pdo->query("SELECT COUNT(*) FROM reservations")->fetchColumn();
$nb_clients  = $pdo->query("SELECT COUNT(*) FROM utilisateurs WHERE role='client'")->fetchColumn();
$ca_total    = $pdo->query("SELECT SUM(total) FROM commandes WHERE statut='livree'")->fetchColumn();
$cmd_attente = $pdo->query("SELECT COUNT(*) FROM commandes WHERE statut='en_attente'")->fetchColumn();
$rsv_attente = $pdo->query("SELECT COUNT(*) FROM reservations WHERE statut='en_attente'")->fetchColumn();
$rupture     = $pdo->query("SELECT COUNT(*) FROM medicaments WHERE quantite = 0")->fetchColumn();

$top_meds = $pdo->query("
    SELECT m.nom, SUM(dc.quantite) as total_vendu
    FROM details_commande dc
    JOIN medicaments m ON dc.medicament_id = m.id
    GROUP BY dc.medicament_id
    ORDER BY total_vendu DESC
    LIMIT 5
")->fetchAll();
?>
<!DOCTYPE html>
<html lang="fr">
<head>
    <meta charset="UTF-8">
    <title>Statistiques - Admin</title>
    <link rel="stylesheet" href="../css/style.css">
</head>
<body>

<?php $root = '../'; require_once "../includes/navbar.php"; ?>

<div class="container">
    <h1>Statistiques</h1>

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
            <p>Total commandes</p>
            <h3><?= $nb_cmd ?></h3>
        </div>
        <div class="stat-item">
            <p>Total reservations</p>
            <h3><?= $nb_rsv ?></h3>
        </div>
        <div class="stat-item">
            <p>Clients</p>
            <h3><?= $nb_clients ?></h3>
        </div>
        <div class="stat-item">
            <p>Chiffre d'affaires</p>
            <h3><?= number_format($ca_total ?? 0, 0) ?> DT</h3>
        </div>
    </div>

    <h2>Alertes</h2>
    <table style="max-width:500px;">
        <tr>
            <th>Indicateur</th>
            <th>Valeur</th>
        </tr>
        <tr>
            <td>Commandes en attente</td>
            <td style="color:<?= $cmd_attente > 0 ? 'orange' : 'green' ?>;"><?= $cmd_attente ?></td>
        </tr>
        <tr>
            <td>Reservations en attente</td>
            <td style="color:<?= $rsv_attente > 0 ? 'orange' : 'green' ?>;"><?= $rsv_attente ?></td>
        </tr>
        <tr>
            <td>Medicaments en rupture</td>
            <td style="color:<?= $rupture > 0 ? 'red' : 'green' ?>;"><?= $rupture ?></td>
        </tr>
    </table>

    <h2 style="margin-top:25px;">Top 5 medicaments commandes</h2>
    <?php if (empty($top_meds)): ?>
        <p>Aucune commande pour le moment.</p>
    <?php else: ?>
    <table style="max-width:500px;">
        <tr>
            <th>Position</th>
            <th>Medicament</th>
            <th>Quantite vendue</th>
        </tr>
        <?php foreach ($top_meds as $i => $m): ?>
        <tr>
            <td><?= $i + 1 ?></td>
            <td><?= htmlspecialchars($m['nom']) ?></td>
            <td><?= $m['total_vendu'] ?></td>
        </tr>
        <?php endforeach; ?>
    </table>
    <?php endif; ?>
</div>

</body>
</html>
