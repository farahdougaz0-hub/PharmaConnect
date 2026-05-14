<?php
session_start();
require_once __DIR__ . "/../config/db.php";

// إنشاء panier في session
if (!isset($_SESSION['panier'])) {
    $_SESSION['panier'] = [];
}

// إذا جا id من commander
if (isset($_GET['id'])) {

    $id = $_GET['id'];

    // جلب médicament من DB
    $stmt = $pdo->prepare("SELECT * FROM medicaments WHERE id=?");
    $stmt->execute([$id]);
    $med = $stmt->fetch();

    // نزيده في panier
    if ($med) {
        $_SESSION['panier'][] = $med;
    }
}

// حساب total
$total = 0;
?>

<!DOCTYPE html>
<html>
<body>

<h2>🛒 Panier</h2>

<table border="1">
<tr>
    <th>Nom</th>
    <th>Prix</th>
    <th>Qté</th>
    <th>Total</th>
</tr>

<?php foreach($_SESSION['panier'] as $item): ?>
<tr>
    <td><?= $item['nom'] ?></td>
    <td><?= $item['prix'] ?> DT</td>
    <td>1</td>
    <td><?= $item['prix'] ?> DT</td>
</tr>

<?php 
    $total += $item['prix']; // ✅ حساب total
endforeach; 
?>

</table>

<h3>✅ Total: <?= $total ?> DT</h3>

<br>

<a href="valider.php">✔ Valider commande</a> |
<a href="../dashboard.php">⬅ Retour</a>

</body>
</html>