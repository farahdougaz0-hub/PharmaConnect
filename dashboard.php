 
<?php
session_start();

// Vérifier si l'utilisateur est connecté
if (!isset($_SESSION['user_id'])) {
    header("Location: login.php");
    exit();
}

// Vérifier si le rôle est défini et si c'est un admin
$isAdmin = (isset($_SESSION['role']) && $_SESSION['role'] === 'admin');

// Récupérer le nom de l'utilisateur en toute sécurité
$nom = isset($_SESSION['nom']) ? htmlspecialchars($_SESSION['nom']) : "Utilisateur";
?>
<!DOCTYPE html>
<html lang="fr">
<head>
    <meta charset="UTF-8">
    <title>Dashboard</title>
    <link rel="stylesheet" href="css/style.css">
</head>
<body>
<div class="container">
    <h1>Dashboard</h1>
    <p style="text-align:center;">
        Bienvenue <strong><?= $nom ?></strong>
        <?php if ($isAdmin): ?>
            <span style="background:#e74c3c;color:white;padding:2px 8px;border-radius:10px;font-size:12px;margin-left:8px;">ADMIN</span>
        <?php endif; ?>
    </p>

    <?php if ($isAdmin): ?>
    <h3 style="color:#e74c3c; text-align:center; margin-top:25px;">🛠️ Administration</h3>
    <div class="actions">
        <a href="admin/medicaments/liste.php">Gérer Médicaments</a>
        <a href="admin/commandes/liste.php"> Gérer Commandes</a>
        <a href="admin/clients/liste.php"> Gérer Clients</a>
        <a href="admin/statistiques.php"> Statistiques</a>
    </div>
    <?php endif; ?>

    
    <h3 style="text-align:center; margin-top:25px;"> Espace Client</h3>
    <div class="actions">
        <a href="chercher-medicaments/liste.php"> Chercher Médicaments</a>
        <a href="commander/panier.php"> Panier</a>
        <a href="logout.php"> Logout</a>
    </div>
</div>
</body>
</html>
