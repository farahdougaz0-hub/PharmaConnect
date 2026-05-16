<?php
session_start();
require_once "../../config/db.php";

if (!isset($_SESSION['user_id']) || $_SESSION['role'] !== 'admin') {
    header("Location: ../../login.php");
    exit();
}

$categories = $pdo->query("SELECT * FROM categories ORDER BY nom")->fetchAll();
$error = '';

if ($_POST) {
    $image_name = '';

    if (!empty($_FILES['image']['name'])) {
        $ext = strtolower(pathinfo($_FILES['image']['name'], PATHINFO_EXTENSION));
        $formats = ['jpg', 'jpeg', 'png', 'gif'];

        if (!in_array($ext, $formats)) {
            $error = "Format non accepte. Utilisez jpg, jpeg, png ou gif.";
        } else {
            $dossier = "../../uploads/medicaments/";
            if (!is_dir($dossier)) {
                mkdir($dossier, 0777, true);
            }
            $image_name = time() . '_' . $_FILES['image']['name'];
            move_uploaded_file($_FILES['image']['tmp_name'], $dossier . $image_name);
        }
    }

    if (!$error) {
        $stmt = $pdo->prepare("INSERT INTO medicaments (nom, description, prix, quantite, categorie_id, image) VALUES (?,?,?,?,?,?)");
        $stmt->execute([
            $_POST['nom'],
            $_POST['description'],
            $_POST['prix'],
            $_POST['quantite'],
            $_POST['categorie_id'] ?: null,
            $image_name
        ]);
        header("Location: liste.php?msg=added");
        exit();
    }
}
?>
<!DOCTYPE html>
<html lang="fr">
<head>
    <meta charset="UTF-8">
    <title>Ajouter medicament - Admin</title>
    <link rel="stylesheet" href="../../css/style.css">
</head>
<body>

<?php $root = '../../'; require_once "../../includes/navbar.php"; ?>

<div class="container" style="max-width:550px;">
    <h1>Ajouter un medicament</h1>

    <div class="liens-actions">
        <a href="liste.php" class="btn">Retour a la liste</a>
    </div>

    <?php if ($error): ?>
        <p class="message-erreur"><?= htmlspecialchars($error) ?></p>
    <?php endif; ?>

    <form method="POST" enctype="multipart/form-data">
        <label>Nom :</label>
        <input type="text" name="nom" required>

        <label>Description :</label>
        <textarea name="description" rows="3"></textarea>

        <label>Categorie :</label>
        <select name="categorie_id">
            <option value="">-- Choisir --</option>
            <?php foreach ($categories as $c): ?>
                <option value="<?= $c['id'] ?>"><?= htmlspecialchars($c['nom']) ?></option>
            <?php endforeach; ?>
        </select>

        <label>Prix (DT) :</label>
        <input type="number" name="prix" step="0.01" min="0" required>

        <label>Stock :</label>
        <input type="number" name="quantite" min="0" required>

        <label>Image :</label>
        <input type="file" name="image" accept="image/*">

        <br>
        <button type="submit">Enregistrer</button>
    </form>
</div>

</body>
</html>
