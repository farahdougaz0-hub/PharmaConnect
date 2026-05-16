<?php
session_start();
require_once "../../config/db.php";

if (!isset($_SESSION['user_id']) || $_SESSION['role'] !== 'admin') {
    header("Location: ../../login.php");
    exit();
}

if ($_POST && isset($_POST['statut'], $_POST['id'])) {
    $pdo->prepare("UPDATE reservations SET statut = ? WHERE id = ?")
        ->execute([$_POST['statut'], $_POST['id']]);
    header("Location: liste.php?msg=updated");
    exit();
}

$reservations = $pdo->query("
    SELECT r.*, u.nom as client_nom, u.email as client_email, m.nom as med_nom
    FROM reservations r
    JOIN utilisateurs u ON r.utilisateur_id = u.id
    JOIN medicaments m ON r.medicament_id = m.id
    ORDER BY r.date_reservation DESC
")->fetchAll();

$msg = $_GET['msg'] ?? '';
?>
<!DOCTYPE html>
<html lang="fr">
<head>
    <meta charset="UTF-8">
    <title>Reservations - Admin</title>
    <link rel="stylesheet" href="../../css/style.css">
</head>
<body>

<?php $root = '../../'; require_once "../../includes/navbar.php"; ?>

<div class="container">
    <h1>Gestion des reservations</h1>

    <?php if ($msg == 'updated'): ?>
        <p class="message-succes">Statut mis a jour.</p>
    <?php endif; ?>

    <table>
        <tr>
            <th>N</th>
            <th>Client</th>
            <th>Medicament</th>
            <th>Quantite</th>
            <th>Date souhaitee</th>
            <th>Date reservation</th>
            <th>Statut</th>
            <th>Changer statut</th>
        </tr>
        <?php foreach ($reservations as $r): ?>
        <tr>
            <td><?= $r['id'] ?></td>
            <td>
                <?= htmlspecialchars($r['client_nom']) ?><br>
                <small><?= htmlspecialchars($r['client_email']) ?></small>
            </td>
            <td><?= htmlspecialchars($r['med_nom']) ?></td>
            <td><?= $r['quantite'] ?></td>
            <td><?= $r['date_souhaitee'] ? date('d/m/Y', strtotime($r['date_souhaitee'])) : '-' ?></td>
            <td><?= date('d/m/Y', strtotime($r['date_reservation'])) ?></td>
            <td><?= $r['statut'] ?></td>
            <td>
                <form method="POST" style="display:flex; gap:5px;">
                    <input type="hidden" name="id" value="<?= $r['id'] ?>">
                    <select name="statut">
                        <option value="en_attente" <?= $r['statut']=='en_attente' ? 'selected':'' ?>>En attente</option>
                        <option value="confirmee"  <?= $r['statut']=='confirmee'  ? 'selected':'' ?>>Confirmee</option>
                        <option value="annulee"    <?= $r['statut']=='annulee'    ? 'selected':'' ?>>Annulee</option>
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
