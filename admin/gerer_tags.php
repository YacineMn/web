<?php
require_once("../includes/session.php");
require_once("../includes/functions.php");
require_once("../includes/db.php");
require_once("../classes/Tag.php");

requireAdmin();
//----------
//Initialisations
//--------------
$pdo    = getPDO();
$tagObj = new Tag($pdo);

$erreurs = [];

// ajout d'un tag
if(isset($_POST['nom_tag'])){
    $nom = trim($_POST['nom_tag']);
    if(empty($nom)){
        $erreurs[] = "Le nom du tag est obligatoire.";
    } else {
        $tagObj->ajouter($nom);
        header("Location: gerer_tags.php");
        exit();
    }
}

// suppression d'un tag
if(isset($_GET['supprimer'])){
    $tagObj->supprimer($_GET['supprimer']);
    // le tag disparait de toutes les recettes grace au CASCADE
    header("Location: gerer_tags.php");
    exit();
}

$tags = $tagObj->getAll(); // on recupere tous les tags
?>

<?php require("../includes/header.php"); ?>

<section class="admin-form">
    <h1>Gérer les tags</h1>

    <?php if(!empty($erreurs)): ?>
        <div class="erreurs">
            <?php foreach($erreurs as $e): ?>
                <p class="erreur"><?= htmlspecialchars($e) ?></p>
            <?php endforeach; ?>
        </div>
    <?php endif; ?>

    <!-- formulaire ajout tag -->
    <form action="gerer_tags.php" method="POST" id="form-tag">
        <div class="form-group">
            <label for="nom_tag">Nouveau tag</label>
            <input type="text" name="nom_tag" id="nom_tag"
                value="<?= isset($_POST['nom_tag']) ? htmlspecialchars($_POST['nom_tag']) : '' ?>">
        </div>
        <button type="submit">Ajouter</button>
    </form>

    <!-- liste des tags existants -->
    <h2>Tags existants</h2>
    <table class="admin-table">
        <thead>
            <tr><th>Nom</th><th>Action</th></tr>
        </thead>
        <tbody>
            <?php foreach($tags as $t): ?>
                <tr>
                    <td><?= htmlspecialchars($t->nom) ?></td>
                    <td>
                        <a href="gerer_tags.php?supprimer=<?= $t->id ?>"
                           onclick="return confirm('Supprimer ce tag ?')"
                           class="btn-supprimer">Supprimer</a>
                    </td>
                </tr>
            <?php endforeach; ?>
        </tbody>
    </table>
</section>

<script src="../assets/js/gerer_tags.js"></script>

<?php require("../includes/footer.php"); ?>