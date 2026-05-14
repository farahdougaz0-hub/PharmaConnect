<?php
session_start();
require_once "config/db.php";

if ($_POST) {
    $stmt = $pdo->prepare("INSERT INTO utilisateurs(nom,email,password) VALUES(?,?,?)");

    $stmt->execute([
        $_POST['nom'],
        $_POST['email'],
        password_hash($_POST['password'], PASSWORD_DEFAULT)
    ]);

    header("Location: login.php");
    exit();
}
?>

<!DOCTYPE html>
<html>
<head>
    <title>Inscription</title>
    <link rel="stylesheet" href="css/style.css">
</head>
<body>
<div class="container">
    <h1>Inscription</h1>

    <form method="POST">
        <input type="text" name="nom" placeholder="Nom" required>
        <input type="email" name="email" placeholder="Email" required>
        <input type="password" name="password" placeholder="Mot de passe" required>
        <button>S'inscrire</button>
    </form>

    <p style="text-align:center; margin-top:15px;">
        <a href="login.php">Déjà un compte ? Connexion</a>
    </p>
</div>
</body>
</html>
