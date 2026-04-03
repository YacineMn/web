<?php
require_once("../includes/session.php");
require_once("../includes/functions.php");
require_once("../includes/db.php");
require_once("../classes/Ingredient.php");

requireAdmin();

$pdo           = getPDO();
$ingredientObj = new Ingredient($pdo);

if(empty($_GET['id'])){ header("Location: gerer_ingredients.php"); exit(); }
$id = $_GET['id'];

// on recupere l ingredient a modifier
$sql = "SELECT * FROM ingredients WHERE id=?";
$st  = $pdo->prepare($sql);
$st->execute([$id]);
$ingredient = $st->fetch(PDO::FETCH_OBJ);

if(!$ingredient){ header("Location: gerer_ingredients.php"); exit(); }

$erreurs = [];

if(isset($_POST['nom_ingredient'])){
    $nom = trim($_POST['nom_ingredient']);
    if(empty($nom)) $erreurs[] = "Le nom est obligatoire.";

    $image = $ingredient->image; // on garde l ancienne image par defaut
    if(isset($_FILES['image_ingredient']) && $_FILES['image_ingredient']['error'] === 0){
        $extensions_ok = ['jpg','jpeg','png','webp'];
        $ext = strtolower(pathinfo($_FILES['image_ingredient']['name'], PATHINFO_EXTENSION));
        if(!in_array($ext, $extensions_ok)){
            $erreurs[] = "Format d'image non autorisé.";
        } else {
            $image = uniqid() . '.' . $ext;
            move_uploaded_file($_FILES['image_ingredient']['tmp_name'], "../uploads/ingredients/" . $image);
        }
    }

    if(empty($erreurs)){
        $ingredientObj->modifier($id, $nom, $image);
        header("Location: gerer_ingredients.php");
        exit();
    }
}
?>

<?php require("../includes/header.php"); ?>

<section class="admin-form">
    <h1>Modifier l'ingrédient</h1>

    <?php if(!empty($erreurs)): ?>
        <div class="erreurs">
            <?php foreach($erreurs as $e): ?>
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
            <img src="../uploads/ingredients/<?= htmlspecialchars($ingredient->image) ?>" style="max-width:100px;"><br>
            <label for="image_ingredient">Changer l'image</label>
            <input type="file" name="image_ingredient" id="image_ingredient" accept="image/*">
        </div>

        <button type="submit">Enregistrer</button>
        <a href="gerer_ingredients.php">Annuler</a>

    </form>
</section>
<script src="../assets/js/validation_recette.js"></script>

<?php require("../includes/footer.php"); ?>
