<?php
require_once "../config/db.php";

$med = $conn->query("SELECT * FROM medicaments")->fetchAll();
?>

<!DOCTYPE html>
<html>
<head>
    <title>Médicaments</title>
    <link rel="stylesheet" href="../css/style.css">
</head>
<body>
<div class="container">
<h1>Médicaments</h1>

<div class="actions">
    <a href="rechercher.php">rechercher</a>
    <a href="../dashboard.php">Retour</a>
</div>

<table>
<tr>
    <th>Nom</th>
    <th>Prix</th>
    <th>Stock</th>
    <th>Image</th>
    <th>Action</th>
</tr>

<?php
  foreach ($med as $m): ?>
   <tr>
    <td><?= $m['nom'] ?></td>
    <td><?= $m['prix'] ?> DT</td>
    <td><?= $m['quantite'] ?></td>
    <td>
    <td>
      <?php if (!empty($m['image'])): ?>
        <img src="../uploads/medicaments/<?php echo $m['image']; ?>" width="70">
      <?php endif; ?>

``

  <td>
    <a href="../commander/panier.php?id=<?= $m['id'] ?>">
        🛒 Commander
    </a>
</td>
</tr>
<?php endforeach; ?>
</table>
</div>
</body>
</html>
