<?php 
// Connexion à la base de données via PDO (sécurisé et réutilisable)
function getPDO(){
    //fonction pour se connecter a la base de données
    $db_name = "recettes_db";//nom de la base données (suposés comme ca a modfier apres discussion)
    $db_host = "127.0.0.1";//adresse du seveur MySQL
    $db_port = "3306";//port utilisé par MySQL (3306 port par defaut)
    //identifiants
    $db_user = "root";//nom d'utilisateur pour se connecter a la base de données
    $db_pwd  = "";//mot de passe associé a cette utilisateur
    try {
        $dsn = 'mysql:dbname=' . $db_name . ';host=' . $db_host . ';port=' . $db_port; //construction de la chaine de connexion
            //qui est une combinaison de nom de la base avec le host et le port
        $pdo = new PDO($dsn, $db_user, $db_pwd);//creation de la connexion PDOavec les infos
        $pdo->setAttribute(PDO::ATTR_ERRMODE, PDO::ERRMODE_EXCEPTION);//on active le mode d'erreurs pour avoir les erreurs possible si un probleme a eu lieu quand on connectes a la base de données
        return $pdo;//on retourne la connexion pour l'utiliser ailleurs
    } catch (Exception $ex) { //si une erreur est surevenu lheure de connexion a la base de données 
        echo "<div style='color:red'>";
        echo "<b>Erreur de connexion</b><br>";//on affiche qu ey 'a un erreur
        echo "Message : " . $ex->getMessage();//on va recuperer le message d'erreur et on l'affiche en rouge
        echo "</div>";
        die();//si l'erreur est surevenu on arrete le script on continue pas les autre etapes qui suivent la connexions
    }
}