<?php
session_start();
require_once 'config/db.php';

$search = $_GET['search'] ?? '';
$cat_id = $_GET['categorie'] ?? '';

$sql = "SELECT m.*, c.nom as categorie_nom FROM medicaments m LEFT JOIN categories c ON m.categorie_id = c.id WHERE 1=1";
$params = [];

if ($search) {
    $sql .= " AND (m.nom LIKE ? OR m.description LIKE ?)";
    $params[] = "%" . $search . "%";
    $params[] = "%" . $search . "%";
}

if ($cat_id) {
    $sql .= " AND m.categorie_id = ?";
    $params[] = $cat_id;
}

$sql .= " ORDER BY m.nom";
$stmt = $pdo->prepare($sql);
$stmt->execute($params);
$medicaments = $stmt->fetchAll();

$categories = $pdo->query("SELECT * FROM categories ORDER BY nom")->fetchAll();
?>
<!DOCTYPE html>
<html lang="fr">
<head>
    <meta charset="UTF-8">
    <title>Liste des medicaments - PharmaConnect</title>
    <link rel="stylesheet" href="../css/style.css">
</head>
<body>

<?php $root = ''; require_once "includes/navbar.php"; ?>

<div class="container">
    <h1>Liste des medicaments</h1>

    <form method="GET" style="margin-bottom:15px;">
        <input type="text" name="search" placeholder="Rechercher..." value="<?= htmlspecialchars($search) ?>" style="width:250px;">
        <select name="categorie">
            <option value="">Toutes les categories</option>
            <?php foreach ($categories as $c): ?>
                <option value="<?= $c['id'] ?>" <?= $cat_id == $c['id'] ? 'selected' : '' ?>>
                    <?= htmlspecialchars($c['nom']) ?>
                </option>
            <?php endforeach; ?>
        </select>
        <button type="submit">Filtrer</button>
        <?php if ($search || $cat_id): ?>
            <a href="liste.php" class="btn">Reinitialiser</a>
        <?php endif; ?>
    </form>

    <?php if (empty($medicaments)): ?>
        <p class="message-info">Aucun medicament trouve.</p>
    <?php else: ?>
    <table>
        <tr>
            <th>Image</th>
            <th>Nom</th>
            <th>Categorie</th>
            <th>Prix</th>
            <th>Stock</th>
            <th>Actions</th>
        </tr>
        <?php foreach ($medicaments as $m): ?>
        <tr>
            <td>
             <?php if (!empty($m['image']) && file_exists("uploads/medicaments/" . $m['image'])): ?>
               <img src="uploads/medicaments/<?= htmlspecialchars($m['image']) ?>" class="img-med" width="60">
             <?php else: ?>
                <span>-</span>
             <?php endif; ?>
            </td>
``
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
                    <a href="/pharmaconnect-v-final/PharmaConnect/reservations/reserver.php?id=<?= $m['id'] ?>" class="btn">Reserver</a>
                <?php else: ?>
                    <span style="color:red;">Non disponible</span>
                <?php endif; ?>
            </td>
        </tr>
        <?php endforeach; ?>
    </table>
    <?php endif; ?>
</div>

</body>
</html>
