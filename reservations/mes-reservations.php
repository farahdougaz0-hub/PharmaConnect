<?php
session_start();
require_once "../../config/db.php";

if (!isset($_SESSION['user_id'])) {
    header("Location: ../../login.php");
    exit();
}

$stmt = $pdo->prepare("
    SELECT r.*, m.nom as med_nom
    FROM reservations r
    JOIN medicaments m ON r.medicament_id = m.id
    WHERE r.utilisateur_id = ?
    ORDER BY r.date_reservation DESC
");
$stmt->execute([$_SESSION['user_id']]);
$reservations = $stmt->fetchAll();
?>
<!DOCTYPE html>
<html lang="fr">
<head>
    <meta charset="UTF-8">
    <title>Mes reservations - PharmaConnect</title>
    <link rel="stylesheet" href="../../css/style.css">
</head>
<body>

<?php $root = '../'; require_once "../includes/navbar.php"; ?>

<div class="container">
    <h1>Mes reservations</h1>

    <div class="liens-actions">
        <a href="../../liste.php" class="btn">Nouvelle reservation</a>
    </div>

    <?php if (empty($reservations)): ?>
        <p class="message-info">Vous n'avez pas encore de reservations.</p>
    <?php else: ?>
    <table>
        <tr>
            <th>N</th>
            <th>Medicament</th>
            <th>Quantite</th>
            <th>Date souhaitee</th>
            <th>Date reservation</th>
            <th>Statut</th>
        </tr>
        <?php foreach ($reservations as $r): ?>
        <tr>
            <td><?= $r['id'] ?></td>
            <td><?= htmlspecialchars($r['med_nom']) ?></td>
            <td><?= $r['quantite'] ?></td>
            <td><?= $r['date_souhaitee'] ? date('d/m/Y', strtotime($r['date_souhaitee'])) : '-' ?></td>
            <td><?= date('d/m/Y', strtotime($r['date_reservation'])) ?></td>
            <td><?= $r['statut'] ?></td>
        </tr>
        <?php endforeach; ?>
    </table>
    <?php endif; ?>
</div>

</body>
</html>
