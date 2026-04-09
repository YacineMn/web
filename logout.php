<?php
/*
Déconnexion de l'utilisateur :

- Démarre la session existante
- Détruit toutes les données de session (logout)
- Redirige l'utilisateur vers la page d'accueil

Cela permet de fermer proprement la session admin.
*/
require_once("includes/session.php"); // on recupere la session
session_destroy(); // on detruit toute la session
header("Location: index.php"); // on redirige vers l'accueil
exit();