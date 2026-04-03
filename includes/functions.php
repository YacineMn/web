<?php
function isAdmin() {
    return isset($_SESSION['admin']) && $_SESSION['admin'] === true;
}

function requireAdmin() {
    if(!isAdmin()) {
        header("Location: login.php");
        exit;
    }
}

function sanitize($str) {
    return htmlspecialchars(trim($str), ENT_QUOTES);
}

function flashMessage($msg, $type) {
    $_SESSION['flash'] = ['message' => $msg, 'type' => $type];
}

function getFlash() {
    if(isset($_SESSION['flash'])) {
        $flash = $_SESSION['flash'];
        unset($_SESSION['flash']);
        return $flash;
    }
    return null;
}

function uploadImage($file, $dest) {
    $extensions_autorisees = ['jpg', 'jpeg', 'png', 'webp'];
    $extension = strtolower(pathinfo($file['name'], PATHINFO_EXTENSION));
    if(!in_array($extension, $extensions_autorisees)) {
        return false;
    }
    $nom_fichier = uniqid() . '.' . $extension;
    move_uploaded_file($file['tmp_name'], $dest . $nom_fichier);
    return $nom_fichier;
}
