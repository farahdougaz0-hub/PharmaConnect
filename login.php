<?php
session_start();
require_once "config/db.php";

if ($_POST) {

    $stmt = $conn->prepare("SELECT * FROM utilisateurs WHERE email=?");
    $stmt->execute([$_POST['email']]);
    $user = $stmt->fetch();

    if ($user && $_POST['password'] == $user['password']) {
        $_SESSION['user_id'] = $user['id'];
        $_SESSION['nom'] = $user['nom'];

        header("Location: dashboard.php");
        exit();
    }

    $error = "Email ou mot de passe incorrect";
}
?>

<!DOCTYPE html>
<html>
<head>
    <title>Connexion</title>
    <link rel="stylesheet" href="css/style.css">
</head>
<body>
<div class="container">
    <h1>Connexion</h1>

    <form method="POST">
        <input name="email" type="email" placeholder="Email" required>
        <input name="password" type="password" placeholder="Mot de passe" required>
        <button>Login</button>
    </form>

    <p class="message-erreur"><?= $error ?? "" ?></p>

    <p style="text-align:center;">
        <a href="register.php">Créer un compte</a>
    </p>
</div>
</body>
</html>
