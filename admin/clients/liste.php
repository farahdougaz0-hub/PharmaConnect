<?php
session_start();
require_once "../../config/db.php";

if (!isset($_SESSION['user_id']) || $_SESSION['role'] !== 'admin') {
    header("Location: ../../login.php");
    exit();
}

$clients = $pdo->query("
    SELECT u.*,
        (SELECT COUNT(*) FROM commandes WHERE utilisateur_id = u.id) as nb_commandes,
        (SELECT COUNT(*) FROM reservations WHERE utilisateur_id = u.id) as nb_reservations
    FROM utilisateurs u
    WHERE u.role = 'client'
    ORDER BY u.created_at DESC
")->fetchAll();
?>
<!DOCTYPE html>
<html lang="fr">
<head>
    <meta charset="UTF-8">
    <title>Clients - Admin</title>
    <link rel="stylesheet" href="../../css/style.css">
</head>
<body>

<?php $root = '../../'; require_once "../../includes/navbar.php"; ?>

<div class="container">
    <h1>Liste des clients</h1>
    <p><?= count($clients) ?> client(s) inscrit(s)</p>

    <table>
        <tr>
            <th>ID</th>
            <th>Nom</th>
            <th>Email</th>
            <th>Telephone</th>
            <th>Commandes</th>
            <th>Reservations</th>
            <th>Inscription</th>
        </tr>
        <?php if (empty($clients)): ?>
        <tr>
            <td colspan="7" style="text-align:center;">Aucun client inscrit.</td>
        </tr>
        <?php else: ?>
        <?php foreach ($clients as $c): ?>
        <tr>
            <td><?= $c['id'] ?></td>
            <td><?= htmlspecialchars($c['nom']) ?></td>
            <td><?= htmlspecialchars($c['email']) ?></td>
            <td><?= htmlspecialchars($c['telephone'] ?? '-') ?></td>
            <td><?= $c['nb_commandes'] ?></td>
            <td><?= $c['nb_reservations'] ?></td>
            <td><?= date('d/m/Y', strtotime($c['created_at'])) ?></td>
        </tr>
        <?php endforeach; ?>
        <?php endif; ?>
    </table>
</div>

</body>
</html>
