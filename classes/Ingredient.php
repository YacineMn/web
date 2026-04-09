<?php 
// Création des objets métiers pour manipuler les données
// (recettes, ingrédients, tags)
class Ingredient {
    private $pdo;//stocke la connexion a la BD 
    public function __construct($pdo){ //constrcucteur qui sauvegarde la connexion a la base de donne a la creation de l'objet
        $this->pdo=$pdo;
    }
    public function getAll(){ //la fonction qui permet d'afficher tout les ingredients dans le formulaire de recherche (parceque pour la recherche soit il tape un ingredeint on le fait defiler la liste des ingredient pour qu'il choisi)
        $sql="SELECT * FROM ingredients";//la requete sql a exceuter pour recuperer tout les ingredients
        $st=$this->pdo->prepare($sql);//on prepare la requetee
        $st->execute();//on execute la requete
        return $st->fetchAll(PDO::FETCH_OBJ); //on retourne tous les ingredients
    }
    public function ajouter($nom, $image){
        // 1. Vérifier si l'ingrédient existe déjà
        $sql = "SELECT id FROM ingredients WHERE nom = ?";
        $st = $this->pdo->prepare($sql);
        $st->execute([$nom]);
        $existant = $st->fetch(PDO::FETCH_OBJ);
        // 2. Si existe → retourner son id
        if($existant){
            return $existant->id;
        }
        // 3. Sinon → insérer
        $sql = "INSERT INTO ingredients (nom, image) VALUES (?, ?)";
        $st = $this->pdo->prepare($sql);
        $st->execute([$nom, $image]);

        return $this->pdo->lastInsertId();
    }
    public function modifier($id, $nom, $image){
        try {
            $sql = "UPDATE ingredients SET nom = ?, image = ? WHERE id = ?";
            $st = $this->pdo->prepare($sql);
            $st->execute([$nom, $image, $id]);
            echo "OK";
        } catch (PDOException $e) {
            echo $e->getMessage(); 
        }
    }

}