<?php 
class Tag {
    private $pdo;//stocke la connexion a la bd
    public function __construct($pdo){ //on sauvegarde la connexion a la bd
        $this->pdo=$pdo;
    }
    public function getAll(){
        $sql="SELECT * FROM tags"; //la requette qui permet d'afficher tout les tags
        $st=$this->pdo->prepare($sql);//recuperer tous les tags pour les listes deroulantes
        $st->execute();//on execute sans parametres
        return $st->fetchAll(PDO::FETCH_OBJ); //on retourne tout les tags
         
    }
    public function ajouter($nom){
        // on verifie d'abord si le tag existe deja
        $sql = "SELECT id FROM tags WHERE nom = ?";
        $st  = $this->pdo->prepare($sql);
        $st->execute([$nom]);
        $existant = $st->fetch(PDO::FETCH_OBJ);

        if($existant){
            return $existant->id; // le tag existe deja on retourne juste son id
        }

        // sinon on insere le nouveau tag
        $sql = "INSERT INTO tags (nom) VALUES (?)";
        $st  = $this->pdo->prepare($sql);
        $st->execute([$nom]);
        return $this->pdo->lastInsertId();
    }
    public function supprimer($id){ //fonction qui permet de supprimer 
        $sql="DELETE FROM tags WHERE id=?";//requete qui permet de supprimer un tag
        $st=$this->pdo->prepare($sql); //preparer la requette a supprimer
        $st->execute([$id]); //executer la requette de suppression
    }
}