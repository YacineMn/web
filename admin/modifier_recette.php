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

if (empty($_GET['id'])) { header("Location: ../admin.php"); exit(); }
$id = $_GET['id'];

$recette     = $recetteObj->getById($id);
$ingredients = $ingredientObj->getAll();
$tags        = $tagObj->getAll();

if (!$recette) { header("Location: ../admin.php"); exit(); }

// ids déjà liés à cette recette
$st_ing = $pdo->prepare("SELECT ingredient_id FROM recette_ingredients WHERE recette_id=?");
$st_ing->execute([$id]);
$sel_ingredient_ids = $st_ing->fetchAll(PDO::FETCH_COLUMN);

$st_tag = $pdo->prepare("SELECT tag_id FROM recette_tags WHERE recette_id=?");
$st_tag->execute([$id]);
$sel_tag_ids = $st_tag->fetchAll(PDO::FETCH_COLUMN);

$erreurs = [];

if (isset($_POST['titre'])) {
    $titre       = trim($_POST['titre']);
    $description = trim($_POST['description']);

    if (empty($titre))       $erreurs[] = "Le titre est obligatoire.";
    if (empty($description)) $erreurs[] = "La description est obligatoire.";

    // photo recette optionnelle (on garde l'ancienne si rien uploadé)
    $photo = $recette->photo;
    if (isset($_FILES['photo']) && $_FILES['photo']['error'] === 0) {
        $extensions_ok = ['jpg','jpeg','png','webp'];
        $ext = strtolower(pathinfo($_FILES['photo']['name'], PATHINFO_EXTENSION));
        if (!in_array($ext, $extensions_ok)) {
            $erreurs[] = "Format de photo non autorisé.";
        } else {
            $photo = uniqid() . '.' . $ext;
            move_uploaded_file($_FILES['photo']['tmp_name'], "../uploads/recettes/" . $photo);
        }
    }

    // validation nouveaux ingrédients : nom rempli → photo obligatoire
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
        $recetteObj->modifier($id, $titre, $description, $photo);

        $pdo->prepare("DELETE FROM recette_ingredients WHERE recette_id=?")->execute([$id]);
        $pdo->prepare("DELETE FROM recette_tags WHERE recette_id=?")->execute([$id]);

        // ingrédients existants cochés
        if (isset($_POST['ingredients'])) {
            foreach ($_POST['ingredients'] as $id_ing) {
                $st = $pdo->prepare("INSERT INTO recette_ingredients (recette_id, ingredient_id, quantite) VALUES (?,?,?)");
                $st->execute([$id, $id_ing, ""]);
            }
        }

        // nouveaux ingrédients
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
                        move_uploaded_file($_FILES['new_ingredient_photos']['tmp_name'][$idx], "../uploads/ingredients/" . $image_new);
                    }
                }
                $id_ing = $ingredientObj->ajouter($nom_new, $image_new);
                $st = $pdo->prepare("INSERT INTO recette_ingredients (recette_id, ingredient_id, quantite) VALUES (?,?,?)");
                $st->execute([$id, $id_ing, ""]);
            }
        }

        // tags existants cochés
        if (isset($_POST['tags'])) {
            foreach ($_POST['tags'] as $id_tag) {
                $st = $pdo->prepare("INSERT INTO recette_tags (recette_id, tag_id) VALUES (?,?)");
                $st->execute([$id, $id_tag]);
            }
        }

        // nouveaux tags
        if (isset($_POST['new_tags'])) {
            foreach ($_POST['new_tags'] as $nom_tag) {
                $nom_tag = trim($nom_tag);
                if (empty($nom_tag)) continue;
                $id_tag = $tagObj->ajouter($nom_tag);
                $st = $pdo->prepare("INSERT INTO recette_tags (recette_id, tag_id) VALUES (?,?)");
                $st->execute([$id, $id_tag]);
            }
        }

        header("Location: ../admin.php");
        exit();
    }
}

function imgRecette($nom) {
    $path = "../uploads/recettes/" . $nom;
    return ($nom && file_exists($path)) ? $path : "../uploads/recettes/default_recette.jpg";
}
?>

<?php require("../includes/header.php"); ?>

<section class="admin-form">
    <h1>Modifier la recette</h1>

    <?php if (!empty($erreurs)): ?>
        <div class="erreurs">
            <?php foreach ($erreurs as $e): ?>
                <p class="erreur"><?= htmlspecialchars($e) ?></p>
            <?php endforeach; ?>
        </div>
    <?php endif; ?>

    <form action="modifier_recette.php?id=<?= $id ?>" method="POST"
          enctype="multipart/form-data" id="form-modifier">

        <!-- TITRE -->
        <div class="form-group">
            <label for="titre">Titre *</label>
            <input type="text" name="titre" id="titre"
                value="<?= isset($_POST['titre']) ? htmlspecialchars($_POST['titre']) : htmlspecialchars($recette->titre) ?>">
        </div>

        <!-- DESCRIPTION -->
        <div class="form-group">
            <label for="description">Description *</label>
            <textarea name="description" id="description"><?= isset($_POST['description']) ? htmlspecialchars($_POST['description']) : htmlspecialchars($recette->description) ?></textarea>
        </div>

        <!-- PHOTO -->
        <div class="form-group">
            <label>Photo actuelle</label>
            <img src="<?= htmlspecialchars(imgRecette($recette->photo)) ?>"
                 alt="photo actuelle"
                 style="max-width:150px; border-radius:8px; margin-bottom:0.5rem; display:block;">
            <label for="photo">Changer la photo
                <span class="label-hint">(optionnel — garde l'actuelle sinon)</span>
            </label>
            <input type="file" name="photo" id="photo" accept="image/*">
            <img id="preview-recette" src=""
                 style="display:none; max-width:200px; margin-top:0.6rem;
                        border-radius:8px; border:2px solid var(--beige);">
        </div>

        <!-- ================================================
             INGRÉDIENTS EXISTANTS — filtrés, pré-cochés
        ================================================ -->
        <div class="form-group">
            <label>Ingrédients</label>
            <div class="filtre-wrap">
                <input type="text"
                       id="filtre-ingredients"
                       placeholder="Filtrer les ingrédients…"
                       autocomplete="off">
            </div>
            <div class="checkboxes" id="liste-ingredients">
                <?php foreach ($ingredients as $i): ?>
                    <label class="checkbox-item"
                           data-nom="<?= strtolower(htmlspecialchars($i->nom)) ?>">
                        <input type="checkbox"
                               name="ingredients[]"
                               value="<?= $i->id ?>"
                               <?php
                                // pré-cocher si liés à la recette, ou si rechargement après erreur
                                $checked = false;
                                if (isset($_POST['ingredients'])) {
                                    $checked = in_array($i->id, $_POST['ingredients']);
                                } else {
                                    $checked = in_array($i->id, $sel_ingredient_ids);
                                }
                                echo $checked ? 'checked' : '';
                               ?>>
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
            <label>Nouvel ingrédient
                <span class="label-hint">(si absent de la liste)</span>
            </label>
            <div id="nouveaux-ingredients">
                <div class="new-ingredient-row" id="new-ing-row-0">
                    <div class="new-ingredient-fields">
                        <input type="text"
                               name="new_ingredient_noms[]"
                               placeholder="Nom de l'ingrédient"
                               class="new-ing-nom">
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
                    <button type="button" class="btn-suppr-row" style="display:none;">✕</button>
                </div>
            </div>
            <button type="button" id="btn-ajouter-ingredient" class="btn-ajouter-ligne">
                + Ajouter un autre ingrédient
            </button>
        </div>

        <!-- ================================================
             TAGS EXISTANTS — filtrés, pré-cochés
        ================================================ -->
        <div class="form-group">
            <label>Tags</label>
            <div class="filtre-wrap">
                <input type="text"
                       id="filtre-tags"
                       placeholder="Filtrer les tags…"
                       autocomplete="off">
            </div>
            <div class="checkboxes" id="liste-tags">
                <?php foreach ($tags as $t): ?>
                    <label class="checkbox-item"
                           data-nom="<?= strtolower(htmlspecialchars($t->nom)) ?>">
                        <input type="checkbox"
                               name="tags[]"
                               value="<?= $t->id ?>"
                               <?php
                                $checked = false;
                                if (isset($_POST['tags'])) {
                                    $checked = in_array($t->id, $_POST['tags']);
                                } else {
                                    $checked = in_array($t->id, $sel_tag_ids);
                                }
                                echo $checked ? 'checked' : '';
                               ?>>
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
            <label>Nouveau tag
                <span class="label-hint">(si absent de la liste)</span>
            </label>
            <div id="nouveaux-tags">
                <div class="new-tag-row">
                    <input type="text" name="new_tags[]" placeholder="Nom du tag">
                    <button type="button" class="btn-suppr-row" style="display:none;">✕</button>
                </div>
            </div>
            <button type="button" id="btn-ajouter-tag" class="btn-ajouter-ligne">
                + Ajouter un autre tag
            </button>
        </div>

        <button type="submit">Enregistrer les modifications</button>
        <a href="../admin.php" style="color:var(--gris); font-size:0.9rem;
           text-decoration:underline; margin-left:0.5rem;">Annuler</a>

    </form>
</section>

<script src="../assets/js/validation_recette.js"></script>

<?php require("../includes/footer.php"); ?>