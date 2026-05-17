<?php
session_start();
require_once "../../config/db.php";

if (!isset($_SESSION['user_id']) || $_SESSION['role'] !== 'admin') {
    header("Location: ../../login.php");
    exit();
}

$msg = $_GET['msg'] ?? '';


if ($_POST) {
    if ($_POST['action'] == 'ajouter') {
        $pdo->prepare("INSERT INTO categories (nom, description) VALUES (?,?)")
            ->execute([$_POST['nom'], $_POST['description']]);
        header("Location: liste.php?msg=added");
        exit();
    }
    if ($_POST['action'] == 'supprimer') {
        $pdo->prepare("DELETE FROM categories WHERE id = ?")->execute([$_POST['id']]);
        header("Location: liste.php?msg=deleted");
        exit();
    }
}

$categories = $pdo->query("
    SELECT c.*, COUNT(m.id) as nb_medicaments
    FROM categories c
    LEFT JOIN medicaments m ON m.categorie_id = c.id
    GROUP BY c.id
    ORDER BY c.nom
")->fetchAll();
?>
<!DOCTYPE html>
<html lang="fr">
<head>
    <meta charset="UTF-8">
    <title>Categories - Admin</title>
    <link rel="stylesheet" href="../../css/style.css">
</head>
<body>

<?php $root = '../../'; require_once "../../includes/navbar.php"; ?>

<div class="container">
    <h1>Gestion des categories</h1>

    <?php if ($msg == 'added'): ?>
        <p class="message-succes">Categorie ajoutee.</p>
    <?php elseif ($msg == 'deleted'): ?>
        <p class="message-succes">Categorie supprimee.</p>
    <?php endif; ?>

    <h2>Ajouter une categorie</h2>
    <form method="POST" style="margin-bottom:25px;">
        <input type="hidden" name="action" value="ajouter">
        <label>Nom :</label>
        <input type="text" name="nom" required style="width:250px;">
        <label>Description :</label>
        <input type="text" name="description" style="width:350px;">
        <button type="submit">Ajouter</button>
    </form>

    <h2>Liste des categories</h2>
    <table>
        <tr>
            <th>ID</th>
            <th>Nom</th>
            <th>Description</th>
            <th>Nb medicaments</th>
            <th>Action</th>
        </tr>
        <?php foreach ($categories as $c): ?>
        <tr>
            <td><?= $c['id'] ?></td>
            <td><?= htmlspecialchars($c['nom']) ?></td>
            <td><?= htmlspecialchars($c['description'] ?? '') ?></td>
            <td><?= $c['nb_medicaments'] ?></td>
            <td>
                <form method="POST" style="display:inline;"
                      onsubmit="return confirm('Supprimer cette categorie ?')">
                    <input type="hidden" name="action" value="supprimer">
                    <input type="hidden" name="id" value="<?= $c['id'] ?>">
                    <button type="submit" class="btn btn-danger">Supprimer</button>
                </form>
            </td>
        </tr>
        <?php endforeach; ?>
    </table>
</div>

</body>
</html>
