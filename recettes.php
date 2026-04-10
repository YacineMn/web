<?php
require("includes/session.php");
require("includes/db.php");
require("includes/functions.php");
require("classes/Recette.php");
require("classes/Ingredient.php");
require("classes/Tag.php");
//On crée des objets pour accéder à la base et utiliser les fonctions de ces classes:
$pdo        = getPDO();
$recetteObj = new Recette($pdo);
$ingredientObj = new Ingredient($pdo);
$tagObj     = new Tag($pdo);
/*
Récupère les paramètres envoyés via l'URL (méthode GET).

- titre : texte saisi dans la barre de recherche
- id_ingredient : valeur choisie dans la liste déroulante des ingrédients ou ecrit dans la barre de recherche
- id_tag : valeur choisie dans la liste déroulante des tags ou ecrit dans la barre de recherche

Si un paramètre n'existe pas, on lui attribue une chaîne vide par défaut.
*/

$titre         = isset($_GET['titre'])        ? $_GET['titre']        : "";
$id_ingredient = isset($_GET['id_ingredient']) ? $_GET['id_ingredient'] : "";
$id_tag        = isset($_GET['id_tag'])        ? $_GET['id_tag']        : "";

/*
Logique de recherche
-------
si au moins il existe un critère de recherche (titre,tag ou ingrédient ) on fait une recherche et on recupère le resultat de la recehrche dans $recette
si c'est pas le cas on garde toujours toutes les recettes existante (non filtrés)
*/ 
if(!empty($titre) || !empty($id_ingredient) || !empty($id_tag)){
    $recettes = $recetteObj->search($titre, $id_ingredient, $id_tag);
} else {
    $recettes = $recetteObj->getAll();
}
/*
On récupère tous les ingrédients et tous les tags depuis la base de données
afin de les afficher dans les listes déroulantes 
*/
$ingredients = $ingredientObj->getAll();
$tags        = $tagObj->getAll();

// fallback image recette
/* cette fonction permet de vérifier si une image de recette existe dans la base de données (ex: si elle est pas supprimer sans faire attention)
Si oui elle la retourne sinon elle retourne une image par defaut  */
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
            <!-- 
            L’attribut selected permet de conserver le choix de l’utilisateur dans les listes déroulantes
            après soumission du formulaire, afin d’améliorer l’expérience utilisateur
            -->
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
            <!--
                Le bouton Filtrer lance la recherche, et le lien Réinitialiser 
                apparaît seulement si un filtre est actif pour revenir à l’état initial de la page 
            -->
            <button type="submit">Filtrer</button>
            <?php if(!empty($titre) || !empty($id_ingredient) || !empty($id_tag)): ?>
                <a href="recettes.php" style="font-size:0.85rem; color:var(--gris); margin-left:0.5rem;">
                    Réinitialiser
                </a>
            <?php endif; ?>
        </form>
    </div>
    <!-- 
        Le titre change selon si une recherche est active ou non, 
        et on affiche dynamiquement le nombre de recettes avec gestion du pluriel
    -->
    <h2 class="page-title">
        <?= empty($titre) && empty($id_ingredient) && empty($id_tag)
            ? "Toutes les recettes"
            : "Résultats de recherche" ?>
        <span style="font-size:0.85rem; color:var(--gris); font-family:'DM Sans',sans-serif; font-weight:400;">
            (<?= count($recettes) ?> recette<?= count($recettes) > 1 ? 's' : '' ?>)
        </span>
    </h2>
    <!-- 
        Affichage conditionnel des recettes :

        - Si aucune recette n'est trouvée : message "Aucune recette trouvée"
        - Sinon : affichage de toutes les recettes sous forme de cartes
        Chaque carte contient :
        - l'image de la recette (avec image par défaut si absente)
        - le titre
        - la description
        - un lien vers la page détaillée de la recette
    -->
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