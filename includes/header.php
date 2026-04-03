<?php
require_once(__DIR__ . "/session.php"); // on demarre la session
$admin = isset($_SESSION['admin']) && $_SESSION['admin'] === true; // on verifie si admin connecte
$base = "/web/";
?>

<!DOCTYPE html>
<html lang="fr">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>TasteLab</title>
<link rel="stylesheet" href="<?= $base ?>assets/css/style.css">
<script src="<?= $base ?>assets/js/main.js"></script>
<script src="<?= $base ?>assets/js/sidebar.js"></script>
</head>
<body>
<div class="layout">

    <!-- sidebar gauche -->
    <aside class="sidebar">
        <div class="sidebar-logo">
            <a href="index.php">TasteLab</a>
        </div>
        <nav class="sidebar-nav">
            <a href="<?= $base ?>index.php" class="sidebar-link">Accueil</a>
            <a href="<?= $base ?>recettes.php" class="sidebar-link">Toutes les recettes</a>
        </nav>
    </aside>

    <div class="main-wrapper">
        <header class="site-header">
              <!-- bouton burger pour mobile -->
                <button class="burger">☰</button>
            <!-- barre de recherche commune a toutes les pages -->
          <form action="<?= $base ?>recettes.php" method="GET" class="header-search">
                <input type="text" name="titre"
                    placeholder="Rechercher une recette..."
                    value="<?= isset($_GET['titre']) ? htmlspecialchars($_GET['titre']) : '' ?>"
                    autocomplete="off">
                <button type="submit">Rechercher</button>
            </form>

            <!-- lien connexion ou deconnexion selon la session -->
            <div class="header-admin">
                <?php if($admin): ?>
                    <a href="<?= $base ?>admin.php" class="btn-admin">Administration</a>
                    <a href="<?= $base ?>logout.php" class="btn-logout">Déconnexion</a>
                <?php else: ?>
                   <a href="<?= $base ?>login.php" class="btn-login">Connexion admin</a>
                <?php endif; ?>
            </div>

        </header>
        <main class="site-main">