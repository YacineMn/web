<?php
require("includes/session.php");
require("includes/db.php");
require("includes/functions.php");
require("classes/Recette.php");
require("classes/Ingredient.php");
require("classes/Tag.php");

$pdo        = getPDO();
$recetteObj = new Recette($pdo);
$ingredientObj = new Ingredient($pdo);
$tagObj     = new Tag($pdo);

$titre         = isset($_GET['titre'])        ? $_GET['titre']        : "";
$id_ingredient = isset($_GET['id_ingredient']) ? $_GET['id_ingredient'] : "";
$id_tag        = isset($_GET['id_tag'])        ? $_GET['id_tag']        : "";

if(!empty($titre) || !empty($id_ingredient) || !empty($id_tag)){
    $recettes = $recetteObj->search($titre, $id_ingredient, $id_tag);
} else {
    $recettes = $recetteObj->getAll();
}

$ingredients = $ingredientObj->getAll();
$tags        = $tagObj->getAll();

// fallback image recette
function imgRecette($nom){
    $path = "uploads/recettes/" . $nom;
    if($nom && file_exists($path)) return $path;
    return "uploads/recettes/default_recette.jpg";
}
?>

<?php require("includes/header.php"); ?>

<section class="site-main">

    <!-- filtres de recherche avancée -->
    <div class="recherche-avancee">
        <form action="recettes.php" method="GET">
            <select name="id_ingredient">
                <option value="">Tous les ingrédients</option>
                <?php foreach($ingredients as $i): ?>
                    <option value="<?= $i->id ?>"
                        <?= ($id_ingredient == $i->id) ? 'selected' : '' ?>>
                        <?= htmlspecialchars($i->nom) ?>
                    </option>
                <?php endforeach; ?>
            </select>

            <select name="id_tag">
                <option value="">Tous les tags</option>
                <?php foreach($tags as $t): ?>
                    <option value="<?= $t->id ?>"
                        <?= ($id_tag == $t->id) ? 'selected' : '' ?>>
                        <?= htmlspecialchars($t->nom) ?>
                    </option>
                <?php endforeach; ?>
            </select>

            <button type="submit">Filtrer</button>
            <?php if(!empty($titre) || !empty($id_ingredient) || !empty($id_tag)): ?>
                <a href="recettes.php" style="font-size:0.85rem; color:var(--gris); margin-left:0.5rem;">
                    Réinitialiser
                </a>
            <?php endif; ?>
        </form>
    </div>

    <h2 class="page-title">
        <?= empty($titre) && empty($id_ingredient) && empty($id_tag)
            ? "Toutes les recettes"
            : "Résultats de recherche" ?>
        <span style="font-size:0.85rem; color:var(--gris); font-family:'DM Sans',sans-serif; font-weight:400;">
            (<?= count($recettes) ?> recette<?= count($recettes) > 1 ? 's' : '' ?>)
        </span>
    </h2>

    <?php if(empty($recettes)): ?>
        <p>Aucune recette trouvée.</p>
    <?php else: ?>
        <div class="recipes-grid">
            <?php foreach($recettes as $recette): ?>
                <article class="recipe-card">
                    <img src="<?= htmlspecialchars(imgRecette($recette->photo)) ?>"
                         alt="<?= htmlspecialchars($recette->titre) ?>">
                    <div class="card-body">
                        <h3><?= htmlspecialchars($recette->titre) ?></h3>
                        <p><?= htmlspecialchars($recette->description) ?></p>
                        <a href="recette.php?id=<?= $recette->id ?>" class="recipe-link">
                            Voir la recette
                        </a>
                    </div>
                </article>
            <?php endforeach; ?>
        </div>
    <?php endif; ?>

</section>

<?php require("includes/footer.php"); ?>