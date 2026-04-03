<?php
require_once("includes/session.php"); // on demarre la session
require_once("includes/functions.php"); // on inclut les fonctions utilitaires
require_once("includes/db.php"); // on inclut la connexion a la base
require_once("classes/Recette.php"); // on inclut la classe Recette

requireAdmin(); // si pas admin on redirige vers login.php automatiquement

$pdo = getPDO(); // on recupere la connexion PDO
$recetteObj = new Recette($pdo); // on cree un objet Recette
$recettes = $recetteObj->getAll(); // on recupere toutes les recettes pour les afficher
?>

<?php require("includes/header.php"); ?>

<section class="admin-page">
    <h1>Tableau de bord - Administration</h1>

    <!-- liens vers les actions admin -->
    <div class="admin-actions">
        <a href="admin/ajouter_recette.php" class="btn-admin">Ajouter une recette</a>
        <a href="admin/gerer_tags.php" class="btn-admin">Gérer les tags</a>
        <a href="admin/gerer_ingredients.php" class="btn-admin">Gérer les ingrédients</a>
    </div>

    <!-- liste de toutes les recettes avec boutons modifier et supprimer -->
    <h2>Liste des recettes</h2>
    <table class="admin-table">
        <thead>
            <tr>
                <th>Titre</th>
                <th>Description</th>
                <th>Actions</th>
            </tr>
        </thead>
        <tbody>
            <?php foreach($recettes as $recette): ?>
                <tr>
                    <td><?= htmlspecialchars($recette->titre) ?></td>
                    <td><?= htmlspecialchars($recette->description) ?></td>
                    <td>
                        <!-- lien vers la page de modification -->
                        <a href="admin/modifier_recette.php?id=<?= $recette->id ?>" class="btn-modifier">
                            Modifier
                        </a>
                        <!-- lien vers la page de suppression -->
                        <a href="admin/supprimer_recette.php?id=<?= $recette->id ?>"
                           class="btn-supprimer"
                           onclick="return confirm('Supprimer cette recette ?')">
                            Supprimer
                        </a>
                    </td>
                </tr>
            <?php endforeach; ?>
        </tbody>
    </table>
</section>

<?php require("includes/footer.php"); ?>