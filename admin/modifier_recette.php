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

$erreurs = [];

if (isset($_POST['titre'])) {
    $titre       = trim($_POST['titre']);
    $description = trim($_POST['description']);

    if (empty($titre))       $erreurs[] = "Le titre est obligatoire.";
    if (empty($description)) $erreurs[] = "La description est obligatoire.";

    $photo = $recette->photo;
    if (isset($_FILES['photo']) && $_FILES['photo']['error'] === 0) {
        $extensions_ok = ['jpg', 'jpeg', 'png', 'webp'];
        $ext = strtolower(pathinfo($_FILES['photo']['name'], PATHINFO_EXTENSION));
        if (!in_array($ext, $extensions_ok)) {
            $erreurs[] = "Format de photo non autorisé.";
        } else {
            $photo = uniqid() . '.' . $ext;
            move_uploaded_file($_FILES['photo']['tmp_name'], "../uploads/recettes/" . $photo);
        }
    }

    if (empty($erreurs)) {
        $recetteObj->modifier($id, $titre, $description, $photo);

        $pdo->prepare("DELETE FROM recette_ingredients WHERE recette_id=?")->execute([$id]);
        $pdo->prepare("DELETE FROM recette_tags WHERE recette_id=?")->execute([$id]);

        if (isset($_POST['ingredients'])) {
            foreach ($_POST['ingredients'] as $val) {
                $st = $pdo->prepare("INSERT INTO recette_ingredients (recette_id, ingredient_id, quantite) VALUES (?,?,?)");
                $st->execute([$id, $val, ""]);
            }
        }

        if (isset($_POST['new_ingredient_noms']) && is_array($_POST['new_ingredient_noms'])) {
            foreach ($_POST['new_ingredient_noms'] as $idx => $nom_new) {
                $nom_new = trim($nom_new);
                if (empty($nom_new)) continue;
                $image_new = "default_ingredient.jpg";
                if (isset($_FILES['new_ingredient_photos']['error'][$idx])
                    && $_FILES['new_ingredient_photos']['error'][$idx] === 0) {
                    $extensions_ok = ['jpg', 'jpeg', 'png', 'webp'];
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

        if (isset($_POST['tags'])) {
            foreach ($_POST['tags'] as $val) {
                if (strpos($val, 'new_') === 0) {
                    $nom_tag = trim(substr($val, 4));
                    if (!empty($nom_tag)) {
                        $id_tag = $tagObj->ajouter($nom_tag);
                        $st = $pdo->prepare("INSERT INTO recette_tags (recette_id, tag_id) VALUES (?,?)");
                        $st->execute([$id, $id_tag]);
                    }
                } else {
                    $st = $pdo->prepare("INSERT INTO recette_tags (recette_id, tag_id) VALUES (?,?)");
                    $st->execute([$id, $val]);
                }
            }
        }

        header("Location: ../admin.php");
        exit();
    }
}

$st_ing = $pdo->prepare("SELECT ingredient_id FROM recette_ingredients WHERE recette_id=?");
$st_ing->execute([$id]);
$sel_ingredient_ids = $st_ing->fetchAll(PDO::FETCH_COLUMN);

$st_tag = $pdo->prepare("SELECT tag_id FROM recette_tags WHERE recette_id=?");
$st_tag->execute([$id]);
$sel_tag_ids = $st_tag->fetchAll(PDO::FETCH_COLUMN);

$ingredients_json = json_encode(array_map(function($i) {
    return ['id' => $i->id, 'nom' => $i->nom];
}, $ingredients));

$tags_json = json_encode(array_map(function($t) {
    return ['id' => $t->id, 'nom' => $t->nom];
}, $tags));

function imgRecette($nom) {
    $path = "../uploads/recettes/" . $nom;
    return ($nom && file_exists($path)) ? $path : "../uploads/recettes/default_recette.jpg";
}
?>

<?php require("../includes/header.php"); ?>

<script>
    window.TASTELAB_INGREDIENTS     = <?= $ingredients_json ?>;
    window.TASTELAB_TAGS            = <?= $tags_json ?>;
    window.TASTELAB_SEL_INGREDIENTS = <?= json_encode($sel_ingredient_ids) ?>;
    window.TASTELAB_SEL_TAGS        = <?= json_encode($sel_tag_ids) ?>;
</script>

<?php
/*
 * Pas de modale HTML ici.
 * La modale est créée dynamiquement par validation_recette.js
 * quand l'admin clique "+ Créer un ingrédient".
 */
?>

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

        <div class="form-group">
            <label for="titre">Titre *</label>
            <input type="text" name="titre" id="titre"
                value="<?= isset($_POST['titre']) ? htmlspecialchars($_POST['titre']) : htmlspecialchars($recette->titre) ?>">
        </div>

        <div class="form-group">
            <label for="description">Description *</label>
            <textarea name="description" id="description"><?= isset($_POST['description']) ? htmlspecialchars($_POST['description']) : htmlspecialchars($recette->description) ?></textarea>
        </div>

        <div class="form-group">
            <label>Photo actuelle</label>
            <img src="<?= htmlspecialchars(imgRecette($recette->photo)) ?>"
                 alt="photo actuelle"
                 style="max-width:150px; border-radius:8px; margin-bottom:0.5rem; display:block;">
            <label for="photo">Changer la photo <span style="color:var(--gris);font-size:0.78rem;text-transform:none;">(optionnel)</span></label>
            <input type="file" name="photo" id="photo" accept="image/*">
            <img id="preview" src="" style="display:none; max-width:200px; margin-top:0.6rem; border-radius:8px; border:2px solid var(--beige);">
        </div>

        <!-- INGRÉDIENTS — autocomplétion avec chips pré-remplis -->
        <div class="form-group autocomplete-group">
            <label for="search-ingredients">Ingrédients</label>
            <div class="autocomplete-wrapper">
                <div class="chips-container" id="chips-ingredients"></div>
                <div class="autocomplete-input-wrap">
                    <input type="text" id="search-ingredients"
                           placeholder="Tapez pour chercher un ingrédient…"
                           autocomplete="off">
                    <ul class="autocomplete-dropdown" id="dropdown-ingredients"></ul>
                </div>
                <p class="autocomplete-hint">
                    Les ingrédients liés sont pré-sélectionnés · cliquez × pour en retirer ·
                    ou cliquez <strong>+ Créer</strong> pour un nouvel ingrédient (photo obligatoire).
                </p>
            </div>
            <div id="new-ingredients-fields"></div>
        </div>

        <!-- TAGS — autocomplétion -->
        <div class="form-group autocomplete-group">
            <label for="search-tags">Tags</label>
            <div class="autocomplete-wrapper">
                <div class="chips-container" id="chips-tags"></div>
                <div class="autocomplete-input-wrap">
                    <input type="text" id="search-tags"
                           placeholder="Tapez pour chercher ou créer un tag…"
                           autocomplete="off">
                    <ul class="autocomplete-dropdown" id="dropdown-tags"></ul>
                </div>
                <p class="autocomplete-hint">
                    Sélectionnez un tag · ou appuyez sur <kbd>Entrée</kbd> /
                    cliquez <strong>+ Créer</strong> pour un nouveau tag.
                </p>
            </div>
        </div>

        <button type="submit">Enregistrer les modifications</button>
        <a href="../admin.php" style="color:var(--gris); font-size:0.9rem; text-decoration:underline; margin-left:0.5rem;">Annuler</a>

    </form>
</section>

<script src="../assets/js/validation_recette.js"></script>

<?php require("../includes/footer.php"); ?>