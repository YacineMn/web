<?php
require_once("../includes/session.php");
require_once("../includes/functions.php");
require_once("../includes/db.php");
require_once("../classes/Recette.php");

requireAdmin();

$pdo = getPDO();
$recetteObj = new Recette($pdo);

if(empty($_GET['id'])){ header("Location: ../admin.php"); exit(); }
$id = $_GET['id'];

$recetteObj->supprimer($id);
// ON DELETE CASCADE supprime automatiquement les ingredients et tags lies

header("Location: ../admin.php");
exit();