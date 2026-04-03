<?php
require("includes/db.php"); // on inclut la connexion a la base
require("classes/Recette.php"); // on inclut la classe Recette

$pdo = getPDO(); // on recupere la connexion PDO
$recetteObj = new Recette($pdo); // on cree un objet Recette

// on recupere les filtres envoyes par le formulaire de recherche
$titre = isset($_GET['titre']) ? $_GET['titre'] : "";
// si l'utilisateur a tape un titre on le recupere sinon vide
$id_ingredient = isset($_GET['id_ingredient']) ? $_GET['id_ingredient'] : "";
// si l'utilisateur a choisi un ingredient on recupere son id sinon vide
$id_tag = isset($_GET['id_tag']) ? $_GET['id_tag'] : "";
// si l'utilisateur a choisi un tag on recupere son id sinon vide

// si au moins un filtre est rempli on fait une recherche sinon on affiche tout
if(!empty($titre) || !empty($id_ingredient) || !empty($id_tag)){
    $recettes = $recetteObj->search($titre, $id_ingredient, $id_tag);
    // on appelle search() avec les filtres recuperes
} else {
    $recettes = $recetteObj->getAll();
    // pas de filtre = on affiche toutes les recettes
}
?>

<!DOCTYPE html>
<html lang="fr">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Liste des recettes</title>
    <link rel="stylesheet" href="assets/css/recettes.css">
</head>
<body>
    <main class="recipes-page">
        <div class="container">
            <h1 class="page-title">Liste des recettes</h1>

            <?php if(empty($recettes)): ?>
                <!-- si aucune recette trouvee on affiche un message -->
                <p>Aucune recette trouvée.</p>
            <?php else: ?>
                <div class="recipes-grid">
                    <?php foreach($recettes as $recette): ?>
                        <article class="recipe-card">
                            <h2><?= htmlspecialchars($recette->titre) ?></h2>
                            <p><?= htmlspecialchars($recette->description) ?></p>
                            <a href="recette.php?id=<?= $recette->id ?>" class="recipe-link">
                                Voir la recette
                            </a>
                        </article>
                    <?php endforeach; ?>
                </div>
            <?php endif; ?>

        </div>
    </main>
</body>
</html>