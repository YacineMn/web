<?php
require_once("../includes/session.php");
require_once("../includes/functions.php");
require_once("../includes/db.php");
require_once("../classes/Recette.php");
require_once("../classes/Ingredient.php");
require_once("../classes/Tag.php");

requireAdmin();

$pdo           = getPDO();
$recetteObj    = new Recette($pdo);
$ingredientObj = new Ingredient($pdo);
$tagObj        = new Tag($pdo);

$ingredients = $ingredientObj->getAll();
$tags        = $tagObj->getAll();

$erreurs = [];

if (isset($_POST['titre'])) {
    $titre       = trim($_POST['titre']);
    $description = trim($_POST['description']);

    if (empty($titre))       $erreurs[] = "Le titre est obligatoire.";
    if (empty($description)) $erreurs[] = "La description est obligatoire.";

    // --- validation photo recette ---
    // on verifie qu'un fichier a bien ete envoye
    if (!aUploade($_FILES['photo'])) {
        $erreurs[] = "La photo de la recette est obligatoire.";
    } else {
        // on tente l'upload pour verifier l'extension
        $photoTest = uploadImage($_FILES['photo'], "../uploads/recettes/");
        if (!$photoTest) {
            $erreurs[] = "Format de photo non autorisé (jpg, jpeg, png, webp).";
        }
        // on supprime le fichier deja deplace si erreurs ailleurs
        // (il sera recree proprement si pas d'erreurs)
    }

    // --- validation nouveaux ingredients : nom rempli → photo obligatoire ---
    if (isset($_POST['new_ingredient_noms'])) {
        foreach ($_POST['new_ingredient_noms'] as $idx => $nom_new) {
            $nom_new = trim($nom_new);
            if (!empty($nom_new) && !aUploade($_FILES['new_ingredient_photos'] ? ['error' => $_FILES['new_ingredient_photos']['error'][$idx]] : null)) {
                $erreurs[] = "La photo est obligatoire pour l'ingrédient \"" . htmlspecialchars($nom_new) . "\".";
            }
        }
    }

    if (empty($erreurs)) {
        // on upload la photo recette — uploadImage() gere tout
        $photo      = uploadImage($_FILES['photo'], "../uploads/recettes/");
        $id_recette = $recetteObj->ajouter($titre, $description, $photo);

        // --- ingredients existants coches ---
        if (isset($_POST['ingredients'])) {
            foreach ($_POST['ingredients'] as $id_ing) {
                $st = $pdo->prepare("INSERT INTO recette_ingredients (recette_id, ingredient_id, quantite) VALUES (?,?,?)");
                $st->execute([$id_recette, $id_ing, ""]);
            }
        }

        // --- nouveaux ingredients saisis avec photo ---
        if (isset($_POST['new_ingredient_noms'])) {
            foreach ($_POST['new_ingredient_noms'] as $idx => $nom_new) {
                $nom_new = trim($nom_new);
                if (empty($nom_new)) continue;
                // on reconstruit le tableau $_FILES pour cet index
                $fichier = [
                    'name'     => $_FILES['new_ingredient_photos']['name'][$idx],
                    'tmp_name' => $_FILES['new_ingredient_photos']['tmp_name'][$idx],
                    'error'    => $_FILES['new_ingredient_photos']['error'][$idx],
                ];

                // uploadImage() gere l'extension et le deplacement
                $image_new = uploadImage($fichier, "../uploads/ingredients/");
                if (!$image_new) $image_new = "default_ingredient.jpg";

                $id_ing = $ingredientObj->ajouter($nom_new, $image_new);
                $st = $pdo->prepare("INSERT INTO recette_ingredients (recette_id, ingredient_id, quantite) VALUES (?,?,?)");
                $st->execute([$id_recette, $id_ing, ""]);
            }
        }

        // --- tags existants coches ---
        if (isset($_POST['tags'])) {
            foreach ($_POST['tags'] as $id_tag) {
                $st = $pdo->prepare("INSERT INTO recette_tags (recette_id, tag_id) VALUES (?,?)");
                $st->execute([$id_recette, $id_tag]);
            }
        }

        // --- nouveaux tags saisis ---
        if (isset($_POST['new_tags'])) {
            foreach ($_POST['new_tags'] as $nom_tag) {
                $nom_tag = trim($nom_tag);
                if (empty($nom_tag)) continue;
                $id_tag = $tagObj->ajouter($nom_tag);
                $st = $pdo->prepare("INSERT INTO recette_tags (recette_id, tag_id) VALUES (?,?)");
                $st->execute([$id_recette, $id_tag]);
            }
        }

        header("Location: ../admin.php");
        exit();
    }
}
?>

<?php require("../includes/header.php"); ?>

<section class="admin-form">
    <h1>Ajouter une recette</h1>

    <?php if (!empty($erreurs)): ?>
        <div class="erreurs">
            <?php foreach ($erreurs as $e): ?>
                <p class="erreur"><?= htmlspecialchars($e) ?></p>
            <?php endforeach; ?>
        </div>
    <?php endif; ?>

    <form action="ajouter_recette.php" method="POST"
          enctype="multipart/form-data" id="form-ajouter">

        <div class="form-group">
            <label for="titre">Titre *</label>
            <input type="text" name="titre" id="titre"
                value="<?= isset($_POST['titre']) ? htmlspecialchars($_POST['titre']) : '' ?>">
        </div>

        <div class="form-group">
            <label for="description">Description *</label>
            <textarea name="description" id="description"><?= isset($_POST['description']) ? htmlspecialchars($_POST['description']) : '' ?></textarea>
        </div>

        <div class="form-group">
            <label for="photo">
                Photo de la recette *
                <span class="label-hint">(obligatoire)</span>
            </label>
            <input type="file" name="photo" id="photo" accept="image/*">
            <img id="preview-recette" src=""
                 style="display:none; max-width:200px; margin-top:0.6rem;
                        border-radius:8px; border:2px solid var(--beige);">
        </div>

        <!-- INGRÉDIENTS EXISTANTS -->
        <div class="form-group">
            <label>Ingrédients existants</label>
            <div class="filtre-wrap">
                <input type="text" id="filtre-ingredients"
                       placeholder="  Filtrer les ingrédients…" autocomplete="off">
            </div>
            <div class="checkboxes" id="liste-ingredients">
                <?php foreach ($ingredients as $i): ?>
                    <label class="checkbox-item"
                           data-nom="<?= strtolower(htmlspecialchars($i->nom)) ?>">
                        <input type="checkbox" name="ingredients[]" value="<?= $i->id ?>"
                               <?= (isset($_POST['ingredients']) && in_array($i->id, $_POST['ingredients'])) ? 'checked' : '' ?>>
                        <?= htmlspecialchars($i->nom) ?>
                    </label>
                <?php endforeach; ?>
            </div>
            <p id="aucun-ingredient-filtre"
               style="display:none; font-size:0.82rem; color:var(--gris); margin-top:0.4rem;">
                Aucun ingrédient ne correspond. Créez-en un ci-dessous.
            </p>
        </div>

        <!-- NOUVEAUX INGRÉDIENTS -->
        <div class="form-group">
            <label>Nouvel ingrédient <span class="label-hint">(si absent de la liste)</span></label>
            <div id="nouveaux-ingredients">
                <div class="new-ingredient-row" id="new-ing-row-0">
                    <div class="new-ingredient-fields">
                        <input type="text" name="new_ingredient_noms[]"
                               placeholder="Nom de l'ingrédient" class="new-ing-nom"
                               value="<?= isset($_POST['new_ingredient_noms'][0]) ? htmlspecialchars($_POST['new_ingredient_noms'][0]) : '' ?>">
                        <div class="new-ingredient-photo-wrap">
                            <input type="file" name="new_ingredient_photos[0]"
                                   accept="image/*" class="new-ing-photo">
                            <img class="new-ing-preview" src=""
                                 style="display:none; max-width:60px; border-radius:6px;
                                        border:2px solid var(--beige); margin-top:0.3rem;">
                        </div>
                    </div>
                    <button type="button" class="btn-suppr-row" style="display:none;">✕</button>
                </div>
            </div>
            <button type="button" id="btn-ajouter-ingredient" class="btn-ajouter-ligne">
                + Ajouter un autre ingrédient
            </button>
        </div>

        <!-- TAGS EXISTANTS -->
        <div class="form-group">
            <label>Tags existants</label>
            <div class="filtre-wrap">
                <input type="text" id="filtre-tags"
                       placeholder="Filtrer les tags…" autocomplete="off">
            </div>
            <div class="checkboxes" id="liste-tags">
                <?php foreach ($tags as $t): ?>
                    <label class="checkbox-item"
                           data-nom="<?= strtolower(htmlspecialchars($t->nom)) ?>">
                        <input type="checkbox" name="tags[]" value="<?= $t->id ?>"
                               <?= (isset($_POST['tags']) && in_array($t->id, $_POST['tags'])) ? 'checked' : '' ?>>
                        <?= htmlspecialchars($t->nom) ?>
                    </label>
                <?php endforeach; ?>
            </div>
            <p id="aucun-tag-filtre"
               style="display:none; font-size:0.82rem; color:var(--gris); margin-top:0.4rem;">
                Aucun tag ne correspond. Créez-en un ci-dessous.
            </p>
        </div>

        <!-- NOUVEAUX TAGS -->
        <div class="form-group">
            <label>Nouveau tag <span class="label-hint">(si absent de la liste)</span></label>
            <div id="nouveaux-tags">
                <div class="new-tag-row">
                    <input type="text" name="new_tags[]" placeholder="Nom du tag"
                           value="<?= isset($_POST['new_tags'][0]) ? htmlspecialchars($_POST['new_tags'][0]) : '' ?>">
                    <button type="button" class="btn-suppr-row" style="display:none;">✕</button>
                </div>
            </div>
            <button type="button" id="btn-ajouter-tag" class="btn-ajouter-ligne">
                + Ajouter un autre tag
            </button>
        </div>

        <button type="submit">Ajouter la recette</button>
        <a href="../admin.php" style="color:var(--gris); font-size:0.9rem;
           text-decoration:underline; margin-left:0.5rem;">Annuler</a>

    </form>
</section>

<script src="../assets/js/validation_recette.js"></script>
<?php require("../includes/footer.php"); ?>