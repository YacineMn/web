<?php
require("includes/session.php");
require("includes/db.php");
require("includes/functions.php");
require("classes/Recette.php");
/*
Page de détail d'une recette :

- Récupère l'ID de la recette via GET
- Vérifie si l'ID existe sinon stoppe l'exécution
- Récupère la recette complète depuis la base de données

Ensuite :
- Affiche les informations de la recette (titre, description, image)
- Affiche la liste des ingrédients associés avec leurs images
- Affiche les tags associés si ils existent

Des fonctions "helper" permettent d'afficher une image par défaut
si le fichier n'existe pas (recette ou ingrédient).
*/
$pdo        = getPDO();
$recetteObj = new Recette($pdo);

$id = isset($_GET['id']) ? $_GET['id'] : "";
if(empty($id)) die("ID manquant");

$recette = $recetteObj->getById($id);

// helper : retourne le src d'une image recette avec fallback si fichier absent
function imgRecette($nom){
    $path = "uploads/recettes/" . $nom;
    if($nom && file_exists($path)) return $path;
    return "uploads/recettes/default_recette.jpg";
}

// helper : retourne le src d'une image ingredient avec fallback si fichier absent
function imgIngredient($nom){
    $path = "uploads/ingredients/" . $nom;
    if($nom && file_exists($path)) return $path;
    return "uploads/ingredients/default_ingredient.jpg";
}
?>

<?php require("includes/header.php"); ?>

<?php if($recette): ?>

<section class="recette-detail">

    <img src="<?= htmlspecialchars(imgRecette($recette->photo)) ?>"
         alt="<?= htmlspecialchars($recette->titre) ?>">

    <div class="recette-detail-body">

        <h1><?= htmlspecialchars($recette->titre) ?></h1>
        <p><?= htmlspecialchars($recette->description) ?></p>

        <h3>Ingrédients</h3>
        <ul>
            <?php foreach($recette->ingredients as $ingredient): ?>
                <li>
                    <img src="<?= htmlspecialchars(imgIngredient($ingredient->image)) ?>"
                         alt="<?= htmlspecialchars($ingredient->nom) ?>"
                         style="width:30px; height:30px; object-fit:cover; border-radius:50%; vertical-align:middle; margin-right:0.4rem;">
                    <?= htmlspecialchars($ingredient->nom) ?>
                </li>
            <?php endforeach; ?>
        </ul>

        <?php if(!empty($recette->tags)): ?>
            <h3>Tags</h3>
            <div class="tags">
                <?php foreach($recette->tags as $tag): ?>
                    <span class="tag"><?= htmlspecialchars($tag->nom) ?></span>
                <?php endforeach; ?>
            </div>
        <?php endif; ?>

    </div>
</section>

<?php else: ?>
    <p>Recette introuvable.</p>
<?php endif; ?>

<?php require("includes/footer.php"); ?>