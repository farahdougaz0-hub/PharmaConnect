<?php
// navbar.php - inclure apres avoir defini $root dans chaque page
// Exemple : $root = '' (racine), $root = '../' (1 niveau), $root = '../../' (2 niveaux)
if (!isset($_SESSION)) session_start();
$isAdmin = (isset($_SESSION['role']) && $_SESSION['role'] === 'admin');
$nom = isset($_SESSION['nom']) ? htmlspecialchars($_SESSION['nom']) : null;
?>
<div class="navbar">
    <a href="<?= $root ?>dashboard.php" class="brand">PharmaConnect</a>
    <nav>
        <?php if ($nom): ?>
            <a href="<?= $root ?>dashboard.php">Accueil</a>
            <?php if ($isAdmin): ?>
                <a href="<?= $root ?>admin/medicaments/liste.php">Medicaments</a>
                <a href="<?= $root ?>admin/categories/liste.php">Categories</a>
                <a href="<?= $root ?>admin/commandes/liste.php">Commandes</a>
                <a href="<?= $root ?>admin/reservations/liste.php">Reservations</a>
                <a href="<?= $root ?>admin/clients/liste.php">Clients</a>
                <a href="<?= $root ?>admin/statistiques.php">Statistiques</a>
            <?php else: ?>
                <a href="<?= $root ?>liste.php">Medicaments</a>
                <a href="<?= $root ?>commander/panier.php">Panier</a>
                <a href="<?= $root ?>reservations/mes-reservations.php">Mes reservations</a>
            <?php endif; ?>
            | Bonjour <?= $nom ?>
            <?php if ($isAdmin): ?><span class="badge-admin">ADMIN</span><?php endif; ?>
            | <a href="<?= $root ?>logout.php">Deconnexion</a>
        <?php else: ?>
            <a href="<?= $root ?>login.php">Connexion</a>
            <a href="<?= $root ?>register.php">Inscription</a>
        <?php endif; ?>
    </nav>
</div>
