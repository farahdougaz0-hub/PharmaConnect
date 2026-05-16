<?php
session_start();
require_once "config/db.php";

$error = '';

if ($_POST) {
    $check = $pdo->prepare("SELECT id FROM utilisateurs WHERE email = ?");
    $check->execute([$_POST['email']]);

    if ($check->fetch()) {
        $error = "Cet email est deja utilise.";
    } else {
        $stmt = $pdo->prepare("INSERT INTO utilisateurs (nom, email, password, telephone, role) VALUES (?,?,?,?,'client')");
        $stmt->execute([
            $_POST['nom'],
            $_POST['email'],
            password_hash($_POST['password'], PASSWORD_DEFAULT),
            $_POST['telephone']
        ]);
        header("Location: login.php");
        exit();
    }
}
?>
<!DOCTYPE html>
<html lang="fr">
<head>
    <meta charset="UTF-8">
    <title>Inscription - PharmaConnect</title>
    <link rel="stylesheet" href="css/style.css">
</head>
<body>

<div class="form-login">
    <h1>Inscription</h1>

    <?php if ($error): ?>
        <p class="message-erreur"><?= htmlspecialchars($error) ?></p>
    <?php endif; ?>

    <form method="POST">
        <label>Nom :</label>
        <input type="text" name="nom" required>

        <label>Email :</label>
        <input type="email" name="email" required>

        <label>Telephone :</label>
        <input type="text" name="telephone">

        <label>Mot de passe :</label>
        <input type="password" name="password" required>

        <button type="submit">S'inscrire</button>
    </form>

    <p>Deja un compte ? <a href="login.php">Se connecter</a></p>
</div>

</body>
</html>
