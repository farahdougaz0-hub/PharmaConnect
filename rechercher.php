<?php
session_start();
require_once "config/db.php";

$search = trim($_GET['search'] ?? '');
$medicaments = [];

if ($search) {
    $stmt = $pdo->prepare("
        SELECT m.*, c.nom as categorie_nom
        FROM medicaments m
        LEFT JOIN categories c ON m.categorie_id = c.id
        WHERE m.nom LIKE ? OR m.description LIKE ? OR c.nom LIKE ?
        ORDER BY m.nom
    ");
    $like = "%" . $search . "%";
    $stmt->execute([$like, $like, $like]);
    $medicaments = $stmt->fetchAll();
}
?>
<!DOCTYPE html>
<html lang="fr">
<head>
    <meta charset="UTF-8">
    <title>Recherche - PharmaConnect</title>
    <link rel="stylesheet" href="css/style.css">
</head>
<body>

<?php $root = ''; require_once "includes/navbar.php"; ?>

<div class="container">
    <h1>Recherche de medicaments</h1>

    <form method="GET" style="margin-bottom:20px;">
        <input type="text" name="search" placeholder="Nom, categorie..." value="<?= htmlspecialchars($search) ?>" style="width:300px;">
        <button type="submit">Chercher</button>
        <?php if ($search): ?>
            <a href="rechercher.php" class="btn">Effacer</a>
        <?php endif; ?>
    </form>

    <?php if ($search): ?>
        <p><?= count($medicaments) ?> resultat(s) pour "<?= htmlspecialchars($search) ?>"</p>

        <?php if (empty($medicaments)): ?>
            <p class="message-info">Aucun medicament trouve.</p>
        <?php else: ?>
        <table>
            <tr>
                <th>Nom</th>
                <th>Categorie</th>
                <th>Prix</th>
                <th>Stock</th>
                <th>Actions</th>
            </tr>
            <?php foreach ($medicaments as $m): ?>
            <tr>
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
                    <?php if ($m['quantite'] > 0): ?>
                        <a href="commander/panier.php?id=<?= $m['id'] ?>" class="btn">Commander</a>
                        <a href="reservations/reserver.php?id=<?= $m['id'] ?>" class="btn">Reserver</a>
                    <?php else: ?>
                        <span style="color:red;">Non disponible</span>
                    <?php endif; ?>
                </td>
            </tr>
            <?php endforeach; ?>
        </table>
        <?php endif; ?>
    <?php endif; ?>
</div>

</body>
</html>
