<?php
require_once("../includes/session.php");
require_once("../includes/functions.php");
require_once("../includes/db.php");
require_once("../classes/Recette.php");
require_once("../classes/Ingredient.php");
require_once("../classes/Tag.php");

requireAdmin();

$pdo = getPDO();
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

    // photo recette OBLIGATOIRE
    if (!isset($_FILES['photo']) || $_FILES['photo']['error'] !== 0) {
        $erreurs[] = "La photo de la recette est obligatoire.";
    } else {
        $extensions_ok = ['jpg','jpeg','png','webp'];
        $ext = strtolower(pathinfo($_FILES['photo']['name'], PATHINFO_EXTENSION));
        if (!in_array($ext, $extensions_ok)) {
            $erreurs[] = "Format de photo non autorisé (jpg, jpeg, png, webp).";
        }
    }

    // validation des nouveaux ingrédients : si nom rempli → photo obligatoire
    if (isset($_POST['new_ingredient_noms'])) {
        foreach ($_POST['new_ingredient_noms'] as $idx => $nom_new) {
            $nom_new = trim($nom_new);
            if (!empty($nom_new)) {
                if (!isset($_FILES['new_ingredient_photos']['error'][$idx])
                    || $_FILES['new_ingredient_photos']['error'][$idx] !== 0) {
                    $erreurs[] = "La photo est obligatoire pour le nouvel ingrédient \"" . htmlspecialchars($nom_new) . "\".";
                }
            }
        }
    }

    if (empty($erreurs)) {
        $photo = uniqid() . '.' . $ext;
        move_uploaded_file($_FILES['photo']['tmp_name'], "../uploads/recettes/" . $photo);

        $id_recette = $recetteObj->ajouter($titre, $description, $photo);

        // ingrédients existants cochés
        if (isset($_POST['ingredients'])) {
            foreach ($_POST['ingredients'] as $id_ing) {
                $st = $pdo->prepare("INSERT INTO recette_ingredients (recette_id, ingredient_id, quantite) VALUES (?,?,?)");
                $st->execute([$id_recette, $id_ing, ""]);
            }
        }

        // nouveaux ingrédients saisis
        if (isset($_POST['new_ingredient_noms'])) {
            foreach ($_POST['new_ingredient_noms'] as $idx => $nom_new) {
                $nom_new = trim($nom_new);
                if (empty($nom_new)) continue;

                $image_new = "default_ingredient.jpg";
                if (isset($_FILES['new_ingredient_photos']['error'][$idx])
                    && $_FILES['new_ingredient_photos']['error'][$idx] === 0) {
                    $extensions_ok = ['jpg','jpeg','png','webp'];
                    $ext_new = strtolower(pathinfo($_FILES['new_ingredient_photos']['name'][$idx], PATHINFO_EXTENSION));
                    if (in_array($ext_new, $extensions_ok)) {
                        $image_new = uniqid() . '.' . $ext_new;
                        move_uploaded_file(
                            $_FILES['new_ingredient_photos']['tmp_name'][$idx],
                            "../uploads/ingredients/" . $image_new
                        );
                    }
                }
                $id_ing = $ingredientObj->ajouter($nom_new, $image_new);
                $st = $pdo->prepare("INSERT INTO recette_ingredients (recette_id, ingredient_id, quantite) VALUES (?,?,?)");
                $st->execute([$id_recette, $id_ing, ""]);
            }
        }

        // tags existants cochés
        if (isset($_POST['tags'])) {
            foreach ($_POST['tags'] as $id_tag) {
                $st = $pdo->prepare("INSERT INTO recette_tags (recette_id, tag_id) VALUES (?,?)");
                $st->execute([$id_recette, $id_tag]);
            }
        }

        // nouveaux tags saisis
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

        <!-- TITRE -->
        <div class="form-group">
            <label for="titre">Titre *</label>
            <input type="text" name="titre" id="titre"
                value="<?= isset($_POST['titre']) ? htmlspecialchars($_POST['titre']) : '' ?>">
        </div>

        <!-- DESCRIPTION -->
        <div class="form-group">
            <label for="description">Description *</label>
            <textarea name="description" id="description"><?= isset($_POST['description']) ? htmlspecialchars($_POST['description']) : '' ?></textarea>
        </div>

        <!-- PHOTO RECETTE obligatoire -->
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

        <!-- ================================================
             INGRÉDIENTS EXISTANTS — filtrés par recherche
        ================================================ -->
        <div class="form-group">
            <label>Ingrédients existants</label>

            <!-- barre de filtrage — hors form, Entrée bloquée par JS -->
            <div class="filtre-wrap">
                <input type="text"
                       id="filtre-ingredients"
                       placeholder="🔍  Filtrer les ingrédients…"
                       autocomplete="off">
            </div>

            <!-- cases à cocher filtrées -->
            <div class="checkboxes" id="liste-ingredients">
                <?php foreach ($ingredients as $i): ?>
                    <label class="checkbox-item"
                           data-nom="<?= strtolower(htmlspecialchars($i->nom)) ?>">
                        <input type="checkbox"
                               name="ingredients[]"
                               value="<?= $i->id ?>"
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

        <!-- ================================================
             NOUVEAUX INGRÉDIENTS — ajout dynamique
        ================================================ -->
        <div class="form-group">
            <label>Nouvel ingrédient
                <span class="label-hint">(si absent de la liste)</span>
            </label>

            <div id="nouveaux-ingredients">
                <!-- ligne 0 présente par défaut -->
                <div class="new-ingredient-row" id="new-ing-row-0">
                    <div class="new-ingredient-fields">
                        <input type="text"
                               name="new_ingredient_noms[]"
                               placeholder="Nom de l'ingrédient"
                               class="new-ing-nom"
                               value="<?= (isset($_POST['new_ingredient_noms'][0])) ? htmlspecialchars($_POST['new_ingredient_noms'][0]) : '' ?>">
                        <div class="new-ingredient-photo-wrap">
                            <input type="file"
                                   name="new_ingredient_photos[0]"
                                   accept="image/*"
                                   class="new-ing-photo">
                            <img class="new-ing-preview" src=""
                                 style="display:none; max-width:60px; border-radius:6px;
                                        border:2px solid var(--beige); margin-top:0.3rem;">
                        </div>
                    </div>
                    <!-- bouton supprimer cette ligne (caché sur la 1ère) -->
                    <button type="button"
                            class="btn-suppr-row"
                            style="display:none;"
                            title="Supprimer cette ligne">✕</button>
                </div>
            </div>

            <!-- bouton ajouter une ligne -->
            <button type="button" id="btn-ajouter-ingredient" class="btn-ajouter-ligne">
                + Ajouter un autre ingrédient
            </button>
        </div>

        <!-- ================================================
             TAGS EXISTANTS — filtrés par recherche
        ================================================ -->
        <div class="form-group">
            <label>Tags existants</label>
            <div class="filtre-wrap">
                <input type="text"
                       id="filtre-tags"
                       placeholder="🔍  Filtrer les tags…"
                       autocomplete="off">
            </div>
            <div class="checkboxes" id="liste-tags">
                <?php foreach ($tags as $t): ?>
                    <label class="checkbox-item"
                           data-nom="<?= strtolower(htmlspecialchars($t->nom)) ?>">
                        <input type="checkbox"
                               name="tags[]"
                               value="<?= $t->id ?>"
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

        <!-- ================================================
             NOUVEAUX TAGS
        ================================================ -->
        <div class="form-group">
            <label>Nouveau tag
                <span class="label-hint">(si absent de la liste)</span>
            </label>
            <div id="nouveaux-tags">
                <div class="new-tag-row">
                    <input type="text"
                           name="new_tags[]"
                           placeholder="Nom du tag"
                           value="<?= (isset($_POST['new_tags'][0])) ? htmlspecialchars($_POST['new_tags'][0]) : '' ?>">
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