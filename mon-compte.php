<?php
session_start();
require_once "config/db.php";

if (!isset($_SESSION['user_id'])) {
    header("Location: login.php");
    exit();
}

$stmt = $pdo->prepare("SELECT * FROM utilisateurs WHERE id = ?");
$stmt->execute([$_SESSION['user_id']]);
$user = $stmt->fetch();

$succes = '';

if ($_POST) {
    $pdo->prepare("UPDATE utilisateurs SET nom=?, telephone=?, adresse=? WHERE id=?")
        ->execute([$_POST['nom'], $_POST['telephone'], $_POST['adresse'], $_SESSION['user_id']]);
    $_SESSION['nom'] = $_POST['nom'];
    $succes = "Profil mis a jour avec succes.";
    $user['nom']       = $_POST['nom'];
    $user['telephone'] = $_POST['telephone'];
    $user['adresse']   = $_POST['adresse'];
}
?>
<!DOCTYPE html>
<html lang="fr">
<head>
    <meta charset="UTF-8">
    <title>Mon compte - PharmaConnect</title>
    <link rel="stylesheet" href="css/style.css">
</head>
<body>

<?php $root = ''; require_once "includes/navbar.php"; ?>

<div class="container" style="max-width:500px;">
    <h1>Mon compte</h1>

    <?php if ($succes): ?>
        <p class="message-succes"><?= $succes ?></p>
    <?php endif; ?>

    <form method="POST">
        <label>Nom :</label>
        <input type="text" name="nom" required value="<?= htmlspecialchars($user['nom']) ?>">

        <label>Email (non modifiable) :</label>
        <input type="email" value="<?= htmlspecialchars($user['email']) ?>" disabled>

        <label>Telephone :</label>
        <input type="text" name="telephone" value="<?= htmlspecialchars($user['telephone'] ?? '') ?>">

        <label>Adresse :</label>
        <textarea name="adresse" rows="3"><?= htmlspecialchars($user['adresse'] ?? '') ?></textarea>

        <label>Inscrit le :</label>
        <input type="text" value="<?= date('d/m/Y', strtotime($user['created_at'])) ?>" disabled>

        <button type="submit">Mettre a jour</button>
    </form>
</div>

</body>
</html>
