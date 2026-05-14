<?php
require_once "../../includes/auth_admin.php";
require_once "../../config/db.php";

$clients = $pdo->query("
    SELECT u.*, COUNT(c.id) AS nb_commandes
    FROM utilisateurs u
    LEFT JOIN commandes c ON c.utilisateur_id = u.id
    GROUP BY u.id
    ORDER BY u.nom
")->fetchAll();
?>
<!DOCTYPE html>
<html>
<head>
    <title> Clients</title>
    <link rel="stylesheet" href="../../css/style.css">
</head>
<body>
<div class="container">
    <h1> Gestion des Clients</h1>
    <div class="actions"><a href="../../dashboard.php">⬅ Retour</a></div>
    <table>
        <tr>
            <th>ID</th>
            <th>Nom</th>
            <th>Email</th>
            <th>Rôle</th>
            <th>Commandes</th>
        </tr>
        <?php foreach ($clients as $u): ?>
        <tr>
            <td><?= $u['id'] ?></td>
            <td><?= htmlspecialchars($u['nom']) ?></td>
            <td><?= htmlspecialchars($u['email']) ?></td>
            <td><?= $u['role'] ?></td>
            <td><?= $u['nb_commandes'] ?></td>
        </tr>
        <?php endforeach; ?>
    </table>
</div>
</body>
</html>
