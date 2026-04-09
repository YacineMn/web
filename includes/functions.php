<?php

// verifie si l'utilisateur connecte est admin
function isAdmin() {
    return isset($_SESSION['admin']) && $_SESSION['admin'] === true;
}
//Vérifie si l'utilisateur est admin.
//Si ce n'est pas le cas, on le redirige vers la page de connexion
//et on bloque l'accès à la page 
function requireAdmin() {
    if (!isAdmin()) {
        header("Location: /web/login.php");
        exit();
    }
}

// gere l'upload d'une image et retourne le nom du fichier genere
// $file  : l'entree $_FILES correspondante (ex: $_FILES['photo'])
// $dossier : le chemin du dossier de destination (ex: "../uploads/recettes/")
// retourne le nom du fichier (ex: "69cf6f572ab77.jpg") ou false si echec
function uploadImage($file, $dossier) {
    $extensions_ok = ['jpg', 'jpeg', 'png', 'webp'];
    $ext = strtolower(pathinfo($file['name'], PATHINFO_EXTENSION));

    // on verifie que l'extension est autorisee
    if (!in_array($ext, $extensions_ok)) {
        return false;
    }

    // on genere un nom unique pour eviter les conflits entre fichiers
    $nom_fichier = uniqid() . '.' . $ext;

    // on deplace le fichier depuis le dossier temporaire vers la destination
    move_uploaded_file($file['tmp_name'], $dossier . $nom_fichier);

    return $nom_fichier;
}

// verifie si un fichier a ete uploade sans erreur
// $file : l'entree $_FILES correspondante
function aUploade($file) {
    return isset($file) && $file['error'] === 0;
}