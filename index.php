<?php
require("includes/session.php"); //connexion a la session 
require("includes/db.php");
require("classes/Recette.php");

$pdo  = getPDO();
$base = "/web/";

$recetteObj         = new Recette($pdo);
$toutes             = $recetteObj->getAll();
$dernieres_recettes = array_slice($toutes, 0, 4);
?>

<?php require("includes/header.php"); ?>

<section class="hero">
    <h1>Bienvenue sur TasteLab</h1>
    <p>Découvrez nos recettes maison, recherchez par ingrédient ou par tag.</p>
    <a href="<?= $base ?>recettes.php" class="btn-hero">Voir toutes les recettes</a>
</section>

<section class="recettes-accueil">
    <h2>Nos recettes</h2>
    <div class="recipes-grid">
        <?php foreach($dernieres_recettes as $recette): ?>
            <article class="recipe-card">
                <img src="<?= $base ?>uploads/recettes/<?= htmlspecialchars($recette->photo) ?>"
                    alt="<?= htmlspecialchars($recette->titre) ?>">
                <div class="card-body">
                    <h3><?= htmlspecialchars($recette->titre) ?></h3>
                    <p><?= htmlspecialchars($recette->description) ?></p>
                    <a href="<?= $base ?>recette.php?id=<?= $recette->id ?>" class="recipe-link">
                        Voir la recette
                    </a>
                </div>
            </article>
        <?php endforeach; ?>
    </div>
    <a href="<?= $base ?>recettes.php" class="btn-voir-tout">Voir toutes les recettes →</a>
</section>

<?php require("includes/footer.php"); ?>