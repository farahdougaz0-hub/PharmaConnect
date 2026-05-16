<?php
session_start();
require_once "../../config/db.php";

if (!isset($_SESSION['user_id']) || $_SESSION['role'] !== 'admin') {
    header("Location: ../../login.php");
    exit();
}

$medicaments = $pdo->query("
    SELECT m.*, c.nom as categorie_nom
    FROM medicaments m
    LEFT JOIN categories c ON m.categorie_id = c.id
    ORDER BY m.nom
")->fetchAll();

$msg = $_GET['msg'] ?? '';
?>
<!DOCTYPE html>
<html lang="fr">
<head>
    <meta charset="UTF-8">
    <title>Medicaments - Admin</title>
    <link rel="stylesheet" href="../../css/style.css">
</head>
<body>

<?php $root = '../../'; require_once "../../includes/navbar.php"; ?>

<div class="container">
    <h1>Gestion des medicaments</h1>

    <div class="liens-actions">
        <a href="ajouter.php" class="btn">Ajouter un medicament</a>
    </div>

    <?php if ($msg == 'added'): ?>
        <p class="message-succes">Medicament ajoute avec succes.</p>
    <?php elseif ($msg == 'updated'): ?>
        <p class="message-succes">Medicament modifie avec succes.</p>
    <?php elseif ($msg == 'deleted'): ?>
        <p class="message-succes">Medicament supprime.</p>
    <?php endif; ?>

    <table>
        <tr>
            <th>ID</th>
            <th>Image</th>
            <th>Nom</th>
            <th>Categorie</th>
            <th>Prix</th>
            <th>Stock</th>
            <th>Actions</th>
        </tr>
        <?php foreach ($medicaments as $m): ?>
        <tr>
            <td><?= $m['id'] ?></td>
            <td>
                <?php if (!empty($m['image']) && file_exists("../../uploads/medicaments/" . $m['image'])): ?>
                    <img src="../../uploads/medicaments/<?= htmlspecialchars($m['image']) ?>" class="img-med">
                <?php else: ?>
                    -
                <?php endif; ?>
            </td>
            <td><?= htmlspecialchars($m['nom']) ?></td>
            <td><?= htmlspecialchars($m['categorie_nom'] ?? '') ?></td>
            <td><?= number_format($m['prix'], 2) ?> DT</td>
            <td>
                <?php if ($m['quantite'] == 0): ?>
                    <span style="color:red;">Rupture</span>
                <?php else: ?>
                    <?= $m['quantite'] ?>
                <?php endif; ?>
            </td>
            <td>
                <a href="modifier.php?id=<?= $m['id'] ?>" class="btn btn-warning">Modifier</a>
                <a href="supprimer.php?id=<?= $m['id'] ?>" class="btn btn-danger"
                   onclick="return confirm('Supprimer ce medicament ?')">Supprimer</a>
            </td>
        </tr>
        <?php endforeach; ?>
    </table>
</div>

</body>
</html>
