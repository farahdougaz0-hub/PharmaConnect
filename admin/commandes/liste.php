<?php
session_start();
require_once "../../config/db.php";

if (!isset($_SESSION['user_id']) || $_SESSION['role'] !== 'admin') {
    header("Location: ../../login.php");
    exit();
}

// Mettre a jour le statut
if ($_POST && isset($_POST['statut'], $_POST['id'])) {
    $pdo->prepare("UPDATE commandes SET statut = ? WHERE id = ?")
        ->execute([$_POST['statut'], $_POST['id']]);
    header("Location: liste.php?msg=updated");
    exit();
}

$commandes = $pdo->query("
    SELECT c.*, u.nom as client_nom, u.email as client_email,
           COUNT(dc.id) as nb_articles
    FROM commandes c
    JOIN utilisateurs u ON c.utilisateur_id = u.id
    LEFT JOIN details_commande dc ON dc.commande_id = c.id
    GROUP BY c.id
    ORDER BY c.date_commande DESC
")->fetchAll();

$msg = $_GET['msg'] ?? '';
?>
<!DOCTYPE html>
<html lang="fr">
<head>
    <meta charset="UTF-8">
    <title>Commandes - Admin</title>
    <link rel="stylesheet" href="../../css/style.css">
</head>
<body>

<?php $root = '../../'; require_once "../../includes/navbar.php"; ?>

<div class="container">
    <h1>Gestion des commandes</h1>

    <?php if ($msg == 'updated'): ?>
        <p class="message-succes">Statut mis a jour.</p>
    <?php endif; ?>

    <table>
        <tr>
            <th>N</th>
            <th>Client</th>
            <th>Articles</th>
            <th>Total</th>
            <th>Date</th>
            <th>Statut</th>
            <th>Changer statut</th>
        </tr>
        <?php foreach ($commandes as $c): ?>
        <tr>
            <td><?= $c['id'] ?></td>
            <td>
                <?= htmlspecialchars($c['client_nom']) ?><br>
                <small><?= htmlspecialchars($c['client_email']) ?></small>
            </td>
            <td><?= $c['nb_articles'] ?></td>
            <td><?= number_format($c['total'], 2) ?> DT</td>
            <td><?= date('d/m/Y', strtotime($c['date_commande'])) ?></td>
            <td><?= $c['statut'] ?></td>
            <td>
                <form method="POST" style="display:flex; gap:5px;">
                    <input type="hidden" name="id" value="<?= $c['id'] ?>">
                    <select name="statut">
                        <option value="en_attente"  <?= $c['statut']=='en_attente'  ? 'selected':'' ?>>En attente</option>
                        <option value="confirmee"   <?= $c['statut']=='confirmee'   ? 'selected':'' ?>>Confirmee</option>
                        <option value="livree"      <?= $c['statut']=='livree'      ? 'selected':'' ?>>Livree</option>
                        <option value="annulee"     <?= $c['statut']=='annulee'     ? 'selected':'' ?>>Annulee</option>
                    </select>
                    <button type="submit">OK</button>
                </form>
            </td>
        </tr>
        <?php endforeach; ?>
    </table>
</div>

</body>
</html>
