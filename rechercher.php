<!DOCTYPE html>
<html>
<head>
    <title>Recherche Médicaments</title>
</head>
<body>

<h2> Recherche Médicaments</h2>

<form method="GET">
    <input type="text" name="search" placeholder="Nom / Prix / Catégorie">
    <button type="submit">Chercher</button>
</form>

<br>

<?php

$conn = new mysqli("localhost", "root", "", "pharmaconnect");

// recherche
if (isset($_GET['search'])) {

    $search = $_GET['search'];

    $sql = "SELECT * FROM medicaments WHERE nom LIKE '%$search%' OR categorie LIKE '%$search%'";
    $result = mysqli_query($conn, $sql);

    $resultats = mysqli_fetch_all($result, MYSQLI_ASSOC);

    if (!empty($resultats)) {
?>

<table border="1">
<tr>
    <th>Nom</th>
    <th>Prix</th>
    <th>Catégorie</th>
    <th style="min-width: 180px;">Action</th>
</tr>

<?php foreach ($resultats as $m): ?>
<tr>
    <td><?= $m['nom'] ?></td>
    <td><?= $m['prix'] ?> DT</td>
    <td><?= $m['categorie'] ?></td>
    

   <td style="min-width: 180px; text-align: center;">
    <a href="../commander/panier.php?id=<?= $m['id'] ?>">
        <button style="padding: 6px 12px;"> Commander</button>
    </a>
   </td>


    
</tr>
<?php endforeach; ?>

</table>

<?php
    } else {
        echo "<p>Aucun résultat</p>";
    }
}
?>

<br>

<a href="../dashboard.php">⬅ Retour</a>



</body>
</html>
