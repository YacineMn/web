<?php
require_once("includes/session.php");
require_once("includes/functions.php");
require_once("includes/db.php");
require_once("classes/Recette.php");

requireAdmin();

$pdo        = getPDO();
$recetteObj = new Recette($pdo);
$recettes   = $recetteObj->getAll();
?>

<?php require("includes/header.php"); ?>

<section class="admin-page">
    <h1>Tableau de bord</h1>

    <div class="admin-actions">
        <a href="admin/ajouter_recette.php" class="btn-admin">+ Ajouter une recette</a>
        <a href="admin/gerer_tags.php"       class="btn-admin">Gérer les tags</a>
        <a href="admin/gerer_ingredients.php" class="btn-admin">Gérer les ingrédients</a>
    </div>

    <!-- en-tête section recettes + barre de recherche sur la même ligne -->
    <div class="admin-table-header">
        <h2>Liste des recettes
            <span id="compteur-recettes" class="compteur-badge">
                <?= count($recettes) ?> recette<?= count($recettes) > 1 ? 's' : '' ?>
            </span>
        </h2>
        <!-- barre de recherche hors de tout <form> -->
        <div class="admin-search-wrap">
            <input
                type="text"
                id="recherche-recettes"
                placeholder="  Rechercher une recette…"
                autocomplete="off">
        </div>
    </div>

    <p id="aucune-recette"
       style="display:none; margin-top:1rem; padding:1.2rem; text-align:center;
              background:var(--blanc); border:1.5px dashed var(--beige);
              border-radius:var(--radius); color:var(--gris); font-size:0.93rem;">
        Aucune recette ne correspond à votre recherche.
    </p>

    <table class="admin-table" id="table-recettes">
        <thead>
            <tr>
                <th>Titre</th>
                <th>Description</th>
                <th>Actions</th>
            </tr>
        </thead>
        <tbody>
            <?php foreach($recettes as $recette): ?>
                <tr data-titre="<?= strtolower(htmlspecialchars($recette->titre)) ?>"
                    data-desc="<?= strtolower(htmlspecialchars($recette->description)) ?>">
                    <td><?= htmlspecialchars($recette->titre) ?></td>
                    <td style="color:var(--gris); font-size:0.88rem; max-width:340px;
                               overflow:hidden; text-overflow:ellipsis; white-space:nowrap;">
                        <?= htmlspecialchars($recette->description) ?>
                    </td>
                    <td>
                        <a href="admin/modifier_recette.php?id=<?= $recette->id ?>"
                           class="btn-modifier">Modifier</a>
                        <a href="admin/supprimer_recette.php?id=<?= $recette->id ?>"
                           class="btn-supprimer"
                           onclick="return confirm('Supprimer cette recette ?')">Supprimer</a>
                    </td>
                </tr>
            <?php endforeach; ?>
        </tbody>
    </table>
</section>

<script src="assets/js/admin_recettes.js"></script>

<?php require("includes/footer.php"); ?>