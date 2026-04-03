<?php
error_reporting(E_ALL);
ini_set('display_errors', 1);
ob_start();
session_start(); // on demarre la session ici
require_once("includes/functions.php"); // on inclut les fonctions utilitaires

// identifiants admin ecrits en dur comme demande par le prof
$ADMIN_LOGIN = "admin";
$ADMIN_PWD   = "1234";

$erreur = ""; // contiendra le message d'erreur si login incorrect

if(isset($_POST['login']) && isset($_POST['pwd'])){
    // si le formulaire a ete soumis on recupere les valeurs
    $login = trim($_POST['login']); // trim enleve les espaces
    $pwd   = trim($_POST['pwd']);

    if(empty($login) || empty($pwd)){
        // validation serveur : les deux champs sont obligatoires
        $erreur = "Veuillez remplir tous les champs.";
    }elseif($login === $ADMIN_LOGIN && $pwd === $ADMIN_PWD){
        $_SESSION['admin'] = true;
        header("Location: admin.php");
        exit();
    }  else {
        // si identifiants incorrects on affiche une erreur
        $erreur = "Login ou mot de passe incorrect.";
    }
}
?>

<?php require("includes/header.php"); ?>

<section class="login-page">
    
    <h1>Connexion Administrateur</h1>

    <?php if(!empty($erreur)): ?>
        <!-- affichage de l'erreur si present -->
        <div class="erreur"><?= htmlspecialchars($erreur) ?></div>
    <?php endif; ?>

    <form action="login.php" method="POST" id="form-login">

        <div class="form-group">
            <label for="login">Login</label>
            <input type="text" name="login" id="login"
                value="<?= isset($_POST['login']) ? htmlspecialchars($_POST['login']) : '' ?>">
            <!-- on reaffiche ce que l'utilisateur a tape -->
        </div>

        <div class="form-group">
            <label for="pwd">Mot de passe</label>
            <input type="password" name="pwd" id="pwd">
        </div>

        <button type="submit">Se connecter</button>

    </form>
    <!-- validation login déjà incluse dans footer via header -->
    <script src="assets/js/validation_login.js"></script>
</section>

<?php require("includes/footer.php"); ?>