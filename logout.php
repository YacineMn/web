<?php
require_once("includes/session.php"); // on demarre la session
session_destroy(); // on detruit toute la session
header("Location: index.php"); // on redirige vers l'accueil
exit();