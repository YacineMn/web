<?php
require("includes/db.php"); // on inclut la connexion a la base
require("classes/Recette.php"); // on inclut la classe Recette

$pdo = getPDO(); // on recupere la connexion PDO
$recetteObj = new Recette($pdo); // on cree un objet Recette

// on recupere l'id depuis l'url
if(isset($_GET['id'])){
    $id = $_GET['id'];
} else {
    $id = "";
}

if(empty($id)){
    die("ID manquant"); // si pas d'id on arrete tout
}

$recette = $recetteObj->getById($id); // on recupere la recette complete
?>

<!DOCTYPE html>
<html lang="fr">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Détail recette</title>
</head>
<body>
<?php if($recette): ?>

    <h1><?= htmlspecialchars($recette->titre) ?></h1>
    <img src="<?= htmlspecialchars($recette->photo) ?>" width="300">
    <p><?= htmlspecialchars($recette->description) ?></p>

    <h3>Ingrédients :</h3>
    <ul>
        <?php foreach($recette->ingredients as $ingredient): ?>
            <li>
                <?= htmlspecialchars($ingredient->nom) ?>
                - <?= htmlspecialchars($ingredient->quantite) ?>
            </li>
        <?php endforeach; ?>
    </ul>

    <h3>Tags :</h3>
    <ul>
        <?php foreach($recette->tags as $tag): ?>
            <li><?= htmlspecialchars($tag->nom) ?></li>
        <?php endforeach; ?>
    </ul>

<?php else: ?>
    <p>Recette introuvable</p>
<?php endif; ?>
</body>
</html>