<?php
session_start();
require_once "../includes/panier.php";

ajouterPanier($_POST['id'], $_POST['nom'], $_POST['prix']);

header("Location: panier.php");