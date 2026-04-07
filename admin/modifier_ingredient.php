<?php
require_once("../includes/session.php");
require_once("../includes/functions.php");
require_once("../includes/db.php");
require_once("../classes/Ingredient.php");

requireAdmin();

$pdo           = getPDO();
$ingredientObj = new Ingredient($pdo);

if (empty($_GET['id'])) { header("Location: gerer_ingredients.php"); exit(); }
$id = $_GET['id'];

$sql = "SELECT * FROM ingredients WHERE id=?";
$st  = $pdo->prepare($sql);
$st->execute([$id]);
$ingredient = $st->fetch(PDO::FETCH_OBJ);

if (!$ingredient) { header("Location: gerer_ingredients.php"); exit(); }

$erreurs = [];

if (isset($_POST['nom_ingredient'])) {
    $nom = trim($_POST['nom_ingredient']);
    if (empty($nom)) $erreurs[] = "Le nom est obligatoire.";

    // on garde l'ancienne image par defaut
    $image = $ingredient->image;

    // si un nouveau fichier est uploade, on utilise uploadImage() de functions.php
    if (aUploade($_FILES['image_ingredient'])) {
        $resultat = uploadImage($_FILES['image_ingredient'], "../uploads/ingredients/");
        if (!$resultat) {
            $erreurs[] = "Format d'image non autorisé (jpg, jpeg, png, webp).";
        } else {
            $image = $resultat;
        }
    }

    if (empty($erreurs)) {
        $ingredientObj->modifier($id, $nom, $image);
        header("Location: gerer_ingredients.php");
        exit();
    }
}
?>

<?php require("../includes/header.php"); ?>

<section class="admin-form">
    <h1>Modifier l'ingrédient</h1>

    <?php if (!empty($erreurs)): ?>
        <div class="erreurs">
            <?php foreach ($erreurs as $e): ?>
                <p class="erreur"><?= htmlspecialchars($e) ?></p>
            <?php endforeach; ?>
        </div>
    <?php endif; ?>

    <form action="modifier_ingredient.php?id=<?= $id ?>" method="POST"
          enctype="multipart/form-data" id="form-modifier-ingredient">

        <div class="form-group">
            <label for="nom_ingredient">Nom *</label>
            <input type="text" name="nom_ingredient" id="nom_ingredient"
                value="<?= isset($_POST['nom_ingredient']) ? htmlspecialchars($_POST['nom_ingredient']) : htmlspecialchars($ingredient->nom) ?>">
        </div>

        <div class="form-group">
            <label>Image actuelle</label>
            <img src="../uploads/ingredients/<?= htmlspecialchars($ingredient->image) ?>"
                 style="max-width:100px; border-radius:8px; margin-bottom:0.5rem; display:block;">
            <label for="image_ingredient">Changer l'image
                <span class="label-hint">(optionnel — garde l'actuelle sinon)</span>
            </label>
            <input type="file" name="image_ingredient" id="image_ingredient" accept="image/*">
        </div>

        <button type="submit">Enregistrer</button>
        <a href="gerer_ingredients.php" style="color:var(--gris); font-size:0.9rem;
           text-decoration:underline; margin-left:0.5rem;">Annuler</a>

    </form>
</section>

<script src="../assets/js/validation_recette.js"></script>
<?php require("../includes/footer.php"); ?>