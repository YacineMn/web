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

// ids actuels de la recette (pour pré-cocher)
$ids_ingredients_actuels = array_map(function($i) { return $i->id ?? null; }, $recette->ingredients ?? []);
$ids_tags_actuels        = array_map(function($t) { return $t->id ?? null; }, $recette->tags ?? []);

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
                if (strpos($val, 'new_') === 0) {
                    $nom_new = trim(substr($val, 4));
                    if (!empty($nom_new)) {
                        $id_ing = $ingredientObj->ajouter($nom_new, "default_ingredient.jpg");
                        $st = $pdo->prepare("INSERT INTO recette_ingredients (recette_id, ingredient_id, quantite) VALUES (?,?,?)");
                        $st->execute([$id, $id_ing, ""]);
                    }
                } else {
                    $st = $pdo->prepare("INSERT INTO recette_ingredients (recette_id, ingredient_id, quantite) VALUES (?,?,?)");
                    $st->execute([$id, $val, ""]);
                }
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

// JSON pour le JS
$ingredients_json = json_encode(array_map(function($i) {
    return ['id' => $i->id, 'nom' => $i->nom];
}, $ingredients));

$tags_json = json_encode(array_map(function($t) {
    return ['id' => $t->id, 'nom' => $t->nom];
}, $tags));

// IDs pré-sélectionnés (pour pré-remplir les chips)
// On utilise les ids récupérés depuis getById via la jointure
// Note : getById retourne des objets sans 'id' pour ingredients (seulement nom/image/quantite)
// On doit récupérer les ids directement
$sql_ing_ids = "SELECT ingredient_id FROM recette_ingredients WHERE recette_id=?";
$st_ing = $pdo->prepare($sql_ing_ids);
$st_ing->execute([$id]);
$sel_ingredient_ids = $st_ing->fetchAll(PDO::FETCH_COLUMN);

$sql_tag_ids = "SELECT tag_id FROM recette_tags WHERE recette_id=?";
$st_tag = $pdo->prepare($sql_tag_ids);
$st_tag->execute([$id]);
$sel_tag_ids = $st_tag->fetchAll(PDO::FETCH_COLUMN);
?>

<?php require("../includes/header.php"); ?>

<script>
    window.TASTELAB_INGREDIENTS     = <?= $ingredients_json ?>;
    window.TASTELAB_TAGS            = <?= $tags_json ?>;
    window.TASTELAB_SEL_INGREDIENTS = <?= json_encode($sel_ingredient_ids) ?>;
    window.TASTELAB_SEL_TAGS        = <?= json_encode($sel_tag_ids) ?>;
</script>

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
            <img src="../uploads/recettes/<?= htmlspecialchars($recette->photo) ?>"
                 style="max-width:150px; border-radius:8px; margin-bottom:0.5rem;"><br>
            <label for="photo">Changer la photo</label>
            <input type="file" name="photo" id="photo" accept="image/*">
            <img id="preview" src="" style="display:none; max-width:200px; margin-top:0.5rem; border-radius:8px;">
        </div>

        <!-- INGRÉDIENTS — autocomplétion -->
        <div class="form-group autocomplete-group">
            <label for="search-ingredients">Ingrédients</label>
            <div class="autocomplete-wrapper">
                <div class="chips-container" id="chips-ingredients"></div>
                <div class="autocomplete-input-wrap">
                    <input
                        type="text"
                        id="search-ingredients"
                        placeholder="Rechercher ou créer un ingrédient…"
                        autocomplete="off">
                    <ul class="autocomplete-dropdown" id="dropdown-ingredients"></ul>
                </div>
                <p class="autocomplete-hint">Tapez pour chercher, appuyez sur <kbd>Entrée</kbd> pour créer un nouvel ingrédient.</p>
            </div>
        </div>

        <!-- TAGS — autocomplétion -->
        <div class="form-group autocomplete-group">
            <label for="search-tags">Tags</label>
            <div class="autocomplete-wrapper">
                <div class="chips-container" id="chips-tags"></div>
                <div class="autocomplete-input-wrap">
                    <input
                        type="text"
                        id="search-tags"
                        placeholder="Rechercher ou créer un tag…"
                        autocomplete="off">
                    <ul class="autocomplete-dropdown" id="dropdown-tags"></ul>
                </div>
                <p class="autocomplete-hint">Tapez pour chercher, appuyez sur <kbd>Entrée</kbd> pour créer un nouveau tag.</p>
            </div>
        </div>

        <button type="submit">Enregistrer les modifications</button>
        <a href="../admin.php">Annuler</a>

    </form>
</section>

<script src="../assets/js/validation_recette.js"></script>

<?php require("../includes/footer.php"); ?>