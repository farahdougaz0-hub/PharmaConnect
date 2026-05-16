<?php
session_start();
require_once "../config/db.php";

if (!isset($_SESSION['user_id'])) {
    header("Location: ../login.php");
    exit();
}

if (!isset($_SESSION['panier'])) {
    $_SESSION['panier'] = [];
}

// Ajouter un article au panier
if (isset($_GET['id'])) {
    $id = (int)$_GET['id'];
    $stmt = $pdo->prepare("SELECT * FROM medicaments WHERE id = ? AND quantite > 0");
    $stmt->execute([$id]);
    $med = $stmt->fetch();

    if ($med) {
        $trouve = false;
        foreach ($_SESSION['panier'] as &$item) {
            if ($item['id'] == $id) {
                $item['qte']++;
                $trouve = true;
                break;
            }
        }
        if (!$trouve) {
            $med['qte'] = 1;
            $_SESSION['panier'][] = $med;
        }
    }
    header("Location: panier.php");
    exit();
}

// Supprimer un article du panier
if (isset($_GET['remove'])) {
    $remove = (int)$_GET['remove'];
    $nouveau_panier = [];
    foreach ($_SESSION['panier'] as $item) {
        if ($item['id'] != $remove) {
            $nouveau_panier[] = $item;
        }
    }
    $_SESSION['panier'] = $nouveau_panier;
    header("Location: panier.php");
    exit();
}

$total = 0;
foreach ($_SESSION['panier'] as $item) {
    $total += $item['prix'] * $item['qte'];
}
?>
<!DOCTYPE html>
<html lang="fr">
<head>
    <meta charset="UTF-8">
    <title>Panier - PharmaConnect</title>
    <link rel="stylesheet" href="../css/style.css">
</head>
<body>

<?php $root = '../'; require_once "../includes/navbar.php"; ?>

<div class="container">
    <h1>Mon panier</h1>

    <?php if (empty($_SESSION['panier'])): ?>
        <p class="message-info">Votre panier est vide.</p>
        <a href="../liste.php" class="btn">Voir les medicaments</a>
    <?php else: ?>
        <table>
            <tr>
                <th>Medicament</th>
                <th>Prix unitaire</th>
                <th>Quantite</th>
                <th>Sous-total</th>
                <th>Action</th>
            </tr>
            <?php foreach ($_SESSION['panier'] as $item): ?>
            <tr>
                <td><?= htmlspecialchars($item['nom']) ?></td>
                <td><?= number_format($item['prix'], 2) ?> DT</td>
                <td><?= $item['qte'] ?></td>
                <td><?= number_format($item['prix'] * $item['qte'], 2) ?> DT</td>
                <td>
                    <a href="panier.php?remove=<?= $item['id'] ?>" class="btn btn-danger"
                       onclick="return confirm('Supprimer cet article ?')">Supprimer</a>
                </td>
            </tr>
            <?php endforeach; ?>
        </table>

        <p class="total-commande">Total : <?= number_format($total, 2) ?> DT</p>

        <div class="liens-actions" style="margin-top:15px;">
            <a href="../liste.php" class="btn">Continuer</a>
            <a href="valider.php" class="btn">Valider la commande</a>
        </div>
    <?php endif; ?>
</div>

</body>
</html>
