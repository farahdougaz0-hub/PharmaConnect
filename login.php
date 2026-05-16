<?php
session_start();
require_once "config/db.php";

$error = '';

if ($_POST) {
    $stmt = $pdo->prepare("SELECT * FROM utilisateurs WHERE email = ?");
    $stmt->execute([$_POST['email']]);
    $user = $stmt->fetch();

    if ($user && password_verify($_POST['password'], $user['password'])) {
        $_SESSION['user_id'] = $user['id'];
        $_SESSION['nom']     = $user['nom'];
        $_SESSION['role']    = $user['role'];
        header("Location: dashboard.php");
        exit();
    }

    $error = "Email ou mot de passe incorrect.";
}
?>
<!DOCTYPE html>
<html lang="fr">
<head>
    <meta charset="UTF-8">
    <title>Connexion - PharmaConnect</title>
    <link rel="stylesheet" href="css/style.css">
</head>
<body>

<div class="form-login">
    <h1>Connexion</h1>

    <?php if ($error): ?>
        <p class="message-erreur"><?= htmlspecialchars($error) ?></p>
    <?php endif; ?>

    <form method="POST">
        <label>Email :</label>
        <input type="email" name="email" required>

        <label>Mot de passe :</label>
        <input type="password" name="password" required>

        <button type="submit">Se connecter</button>
    </form>

    <p>Pas de compte ? <a href="register.php">S'inscrire</a></p>
</div>

</body>
</html>
