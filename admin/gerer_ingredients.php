<?php
require_once("../includes/session.php");
require_once("../includes/functions.php");
require_once("../includes/db.php");
require_once("../classes/Ingredient.php");

requireAdmin();

$pdo           = getPDO();
$ingredientObj = new Ingredient($pdo);

$erreurs = [];

// suppression d'un ingredient
if(isset($_GET['supprimer'])){
    // ON DELETE CASCADE supprime aussi ses liens dans recette_ingredients
    $sql = "DELETE FROM ingredients WHERE id=?";
    $st  = $pdo->prepare($sql);
    $st->execute([$_GET['supprimer']]);
    header("Location: gerer_ingredients.php");
    exit();
}

// ajout d'un ingredient
if(isset($_POST['nom_ingredient'])){
    $nom = trim($_POST['nom_ingredient']);

    if(empty($nom)){
        $erreurs[] = "Le nom de l'ingrédient est obligatoire.";
    }

    // photo OBLIGATOIRE
    if(!isset($_FILES['image_ingredient']) || $_FILES['image_ingredient']['error'] !== 0){
        $erreurs[] = "La photo de l'ingrédient est obligatoire.";
    } else {
        $extensions_ok = ['jpg','jpeg','png','webp'];
        $ext = strtolower(pathinfo($_FILES['image_ingredient']['name'], PATHINFO_EXTENSION));
        if(!in_array($ext, $extensions_ok)){
            $erreurs[] = "Format d'image non autorisé (jpg, jpeg, png, webp).";
        }
    }

    if(empty($erreurs)){
        $image = uniqid() . '.' . $ext;
        move_uploaded_file($_FILES['image_ingredient']['tmp_name'], "../uploads/ingredients/" . $image);
        $ingredientObj->ajouter($nom, $image);
        header("Location: gerer_ingredients.php");
        exit();
    }
}

$ingredients = $ingredientObj->getAll();
?>

<?php require("../includes/header.php"); ?>

<section class="admin-form" style="max-width:800px;">
    <h1>Gérer les ingrédients</h1>

    <?php if(!empty($erreurs)): ?>
        <div class="erreurs">
            <?php foreach($erreurs as $e): ?>
                <p class="erreur"><?= htmlspecialchars($e) ?></p>
            <?php endforeach; ?>
        </div>
    <?php endif; ?>

    <!-- formulaire ajout ingredient -->
    <form action="gerer_ingredients.php" method="POST"
          enctype="multipart/form-data" id="form-ingredient">

        <div class="form-group">
            <label for="nom_ingredient">Nom de l'ingrédient *</label>
            <input type="text" name="nom_ingredient" id="nom_ingredient"
                value="<?= isset($_POST['nom_ingredient']) ? htmlspecialchars($_POST['nom_ingredient']) : '' ?>">
        </div>

        <div class="form-group">
            <label for="image_ingredient">
                Photo *
                <span style="color:var(--terre); font-size:0.8rem;">(obligatoire)</span>
            </label>
            <input type="file" name="image_ingredient" id="image_ingredient" accept="image/*">
            <img id="preview-ingredient" src=""
                 style="display:none; max-width:120px; margin-top:0.6rem; border-radius:8px; border:2px solid var(--beige);">
        </div>

        <button type="submit">Ajouter l'ingrédient</button>
    </form>

    <!-- barre de recherche — hors de tout <form> pour éviter soumission via Entrée -->
    <div style="margin: 2rem 0 1rem;">
        <h2>Ingrédients existants</h2>
        <div class="ingredient-search-wrap">
            <input
                type="text"
                id="recherche-ingredients"
                placeholder="  Rechercher un ingrédient…"
                autocomplete="off">
        </div>
    </div>

    <p id="compteur-ingredients" style="font-size:0.85rem; color:var(--gris); margin-bottom:0.8rem;">
        <?= count($ingredients) ?> ingrédient<?= count($ingredients) > 1 ? 's' : '' ?>
    </p>

    <table class="admin-table" id="table-ingredients">
        <thead>
            <tr><th>Photo</th><th>Nom</th><th>Actions</th></tr>
        </thead>
        <tbody>
            <?php foreach($ingredients as $i): ?>
                <tr data-nom="<?= strtolower(htmlspecialchars($i->nom)) ?>">
                    <td>
                        <img src="../uploads/ingredients/<?= htmlspecialchars($i->image) ?>"
                             style="width:50px; height:50px; object-fit:cover; border-radius:8px;">
                    </td>
                    <td><?= htmlspecialchars($i->nom) ?></td>
                    <td>
                        <a href="modifier_ingredient.php?id=<?= $i->id ?>"
                           class="btn-modifier">Modifier</a>
                        <a href="gerer_ingredients.php?supprimer=<?= $i->id ?>"
                           onclick="return confirm('Supprimer cet ingrédient ?')"
                           class="btn-supprimer">Supprimer</a>
                    </td>
                </tr>
            <?php endforeach; ?>
        </tbody>
    </table>

    <p id="aucun-resultat"
       style="display:none; color:var(--gris); font-style:italic; margin-top:1rem;
              padding:1rem; text-align:center; background:var(--blanc);
              border:1.5px dashed var(--beige); border-radius:var(--radius);">
        Aucun ingrédient ne correspond à votre recherche.
    </p>
</section>

<script src="../assets/js/gerer_ingredients.js"></script>

<?php require("../includes/footer.php"); ?>