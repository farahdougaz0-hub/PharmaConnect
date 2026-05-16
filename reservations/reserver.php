<?php
session_start();
require_once "../config/db.php";

if (!isset($_SESSION['user_id'])) {
    header("Location: ../login.php");
    exit();
}

$id = isset($_GET['id']) ? (int)$_GET['id'] : 0;
$stmt = $pdo->prepare("SELECT * FROM medicaments WHERE id = ?");
$stmt->execute([$id]);
$med = $stmt->fetch();

if (!$med) {
    header("Location: ../liste.php");
    exit();
}

$succes = '';

if ($_POST) {
    $stmt = $pdo->prepare("INSERT INTO reservations (utilisateur_id, medicament_id, quantite, date_souhaitee, notes) VALUES (?,?,?,?,?)");
    $stmt->execute([
        $_SESSION['user_id'],
        $id,
        (int)$_POST['quantite'],
        $_POST['date_souhaitee'] ?: null,
        $_POST['notes']
    ]);
    $succes = "Reservation enregistree avec succes.";
}
?>
<!DOCTYPE html>
<html lang="fr">
<head>
    <meta charset="UTF-8">
    <title>Reserver - PharmaConnect</title>
    <link rel="stylesheet" href="../css/style.css">
</head>
<body>

<?php $root = '../'; require_once "../includes/navbar.php"; ?>

<div class="container" style="max-width:500px;">
    <h1>Reserver un medicament</h1>

    <p><strong>Medicament :</strong> <?= htmlspecialchars($med['nom']) ?></p>
    <p><strong>Prix :</strong> <?= number_format($med['prix'], 2) ?> DT</p>
    <p><strong>Stock disponible :</strong> <?= $med['quantite'] ?></p>

    <?php if ($succes): ?>
        <p class="message-succes"><?= $succes ?></p>
        <a href="../dashboard.php" class="btn">Retour au dashboard</a>
    <?php else: ?>
    <form method="POST">
        <label>Quantite :</label>
        <input type="number" name="quantite" min="1" max="<?= $med['quantite'] ?>" value="1" required>

        <label>Date souhaitee :</label>
        <input type="date" name="date_souhaitee" min="<?= date('Y-m-d', strtotime('+1 day')) ?>">

        <label>Notes / Remarques :</label>
        <textarea name="notes" rows="3" placeholder="Informations complementaires..."></textarea>

        <button type="submit">Confirmer la reservation</button>
        <a href="../liste.php" class="btn" style="margin-left:10px;">Annuler</a>
    </form>
    <?php endif; ?>
</div>

</body>
</html>
