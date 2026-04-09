<?php
require_once(__DIR__ . "/session.php");
$admin = isset($_SESSION['admin']) && $_SESSION['admin'] === true;
$base  = "/web/";
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


    <div class="main-wrapper" id="main-wrapper">

        
        <header class="site-header">


            <!-- titre -->
            <div class="site-brand">
                <a href="<?= $base ?>index.php" class="site-title">TasteLab</a>
            </div>

            <!-- navigation principale — toujours visible -->
             <!--
                Ce code permet de savoir quelle page est actuellement ouverte.
                basename($_SERVER['PHP_SELF']) récupère le nom du fichier (ex: index.php).

                Si on est sur index.php :
                 on ajoute la classe "active" au lien

                But :
                 appliquer un style CSS spécial (ex : couleur différente)
                 montrer à l'utilisateur sur quelle page il se trouve dans le menu
            -->
            <nav class="header-nav">
                <a href="<?= $base ?>index.php"
                   class="nav-link <?= (basename($_SERVER['PHP_SELF']) === 'index.php') ? 'active' : '' ?>">
                    Accueil
                </a>
                <a href="<?= $base ?>recettes.php"
                   class="nav-link <?= (basename($_SERVER['PHP_SELF']) === 'recettes.php') ? 'active' : '' ?>">
                    Recettes
                </a>
            </nav>
            <!-- barre de recherche globale -->
            <form action="<?= $base ?>recettes.php" method="GET" class="header-search">
                <input type="text"
                       name="titre"
                       placeholder="Rechercher une recette…"
                       value="<?= isset($_GET['titre']) ? htmlspecialchars($_GET['titre']) : '' ?>"
                       autocomplete="off">
                <button type="submit">Rechercher</button>
            </form>
            <!--
            Affichage conditionnel selon l'état de connexion admin :

                - Si l'utilisateur est admin (session active) :
                 on affiche les boutons Admin et Déconnexion

                - Sinon :
                 on affiche le bouton Connexion admin
                But :
                 adapter le menu selon que l'utilisateur est connecté ou non
            -->
            <div class="header-admin">
                <?php if ($admin): ?>
                    <a href="<?= $base ?>admin.php"  class="btn-admin">Admin</a>
                    <a href="<?= $base ?>logout.php" class="btn-logout">Déconnexion</a>
                <?php else: ?>
                    <a href="<?= $base ?>login.php"  class="btn-login">Connexion admin</a>
                <?php endif; ?>
            </div>

        </header>

        <main class="site-main">