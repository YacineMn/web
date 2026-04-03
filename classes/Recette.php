<?php
class Recette{
    private $pdo;//stocke la connexion a la BD accessible uniquement dans la classe
    public function __construct($pdo){
        $this->pdo=$pdo; //au moment de creation de l'objet on sauvegarde la connexion
    }
    public function getAll(){ //cette fonction permet de recuperer toute les recette existante 
        $sql="SELECT * FROM recettes";//requete sql pour tout recuperer
        $st=$this->pdo->prepare($sql);//on prepare la requete
        $st->execute();//executer la requete
        return $st->fetchAll(PDO::FETCH_OBJ);//on retournes toutes les lignes sous forme d'objets
    }
    public function getById($id){
        $sql="SELECT * FROM recettes WHERE id=?";//requete pour recuperer la recette dont l'id correspond au ?
        $st=$this->pdo->prepare($sql); //preparer la requete (pas encore executée)
        $st->execute([$id]); //on remplace le ? par l'id recu en parametres et on execute 
        $recette=$st->fetch(PDO::FETCH_OBJ); //recueperation de la recette recuperer pare l'id
        if(!$recette){ //si la recette correspandante a l'id est null 
            return null; //on recuperer null
        }
        //maintenant qu'on a recuperer cette recette on recupere ses ingredients
        $sql2="SELECT i.nom, i.image, ri.quantite FROM ingredients i JOIN recette_ingredients ri ON i.id=ri.ingredient_id WHERE ri.recette_id=?"; //on joint ingredients et recette ingredients (les deux tables dans la base données)
        //pour avoir nom + image +quantite de chaque ingredient de cette recette  
        $st2=$this->pdo->prepare($sql2); //on prepare la requette pour recuperer les ingredients 
        $st2->execute([$id]);//on execute la 2 eme recette par le meme id de la recette recuperer
        $recette->ingredients=$st2->fetchAll(PDO::FETCH_OBJ); //on recupere tous les ingredients et on attache direcetment les ingredients a la recette
        //donc par la suite recette->ingredients contient le tableau des ingredients
        //tags de la recette 
        $sql3="SELECT t.nom FROM tags t JOIN recette_tags rt ON t.id = rt.tag_id WHERE rt.recette_id = ?";
        //on join tags et recettes_tags pour avoir le nom de chaque tag associé a cette recette
        $st3 = $this->pdo->prepare($sql3); //on prepare la 3 eme recette
        $st3->execute([$id]); //on filtre par l'id de la recette 
        $recette->tags=$st3->fetchAll(PDO::FETCH_OBJ); //on recupere tous les tags et on les rattache pour l'objet recette donc par la suite recette->tags contient un tableau de tags
        return $recette; //on retourne la recette complete 
    }
    public function search($titre="", $id_ingredient="", $id_tag=""){
        // les 3 parametres sont optionnels
        
        $sql="SELECT DISTINCT r.* FROM recettes r
            LEFT JOIN recette_ingredients ri ON r.id = ri.recette_id
            LEFT JOIN ingredients i ON i.id = ri.ingredient_id
            LEFT JOIN recette_tags rt ON r.id = rt.recette_id
            LEFT JOIN tags t ON t.id = rt.tag_id
            WHERE 1=1";
        // on joint toutes les tables d'un coup
        // LEFT JOIN = on garde toutes les recettes meme si elles ont pas d'ingredients ou de tags
        // WHERE 1=1 = condition toujours vraie qui permet d'ajouter des AND apres facilement
        $params=[];
       if(!empty($titre)){
            $sql.=" AND (r.titre LIKE ? OR i.nom LIKE ? OR t.nom LIKE ?)";
            // on cherche dans le titre ET dans les ingredients ET dans les tags
            $params[]="%".$titre."%";
            $params[]="%".$titre."%";
            $params[]="%".$titre."%";
            // on ajoute 3 fois car 3 ?
        }
        if(!empty($id_ingredient)){
            $sql.=" AND ri.ingredient_id = ?";
            // on ajoute le filtre ingredient seulement si l'utilisateur en a choisi un
            $params[]=$id_ingredient;
        }
        if(!empty($id_tag)){
            $sql.=" AND rt.tag_id = ?";
            // on ajoute le filtre tag seulement si l'utilisateur en a choisi un
            $params[]=$id_tag;
        }

        $st=$this->pdo->prepare($sql); // on prepare la requete finale
        $st->execute($params); // on envoie les valeurs des filtres
        return $st->fetchAll(PDO::FETCH_OBJ); // on retourne les recettes trouvees
    }
    public function ajouter($titre,$description,$photo){ //fonctions qui permet d'inserer une nouvelle recette dans la tables recettes
        $sql="INSERT INTO recettes (titre,description,photo) VALUES(?,?,?)";//la requete sql qui permet d'inserer une recette avec les parametre (titre description et photo)
        $st=$this->pdo->prepare($sql);//on prepare la requete 
        $st->execute([$titre,$description,$photo]);//on execute la recette avec les parametres recu (ce que l'utilisateur a saisi) 
        return $this->pdo->lastInsertId();//on retourne l'id de la recette qu'on vient d'ajouter
        //utile pour ensuite lui ajouter ses ingredients et ses tags 
    }
    public function modifier($id,$titre,$description,$photo){ //fonction qui permet de modifier une recette existante  
        $sql="UPDATE recettes SET titre=? ,description=?,photo=? WHERE id=?";
        //requette sql 
        //update: permet de modfier une ligne existante dans la base de données
        //SET:les colonnes a modifer avec leurs nouvelle valeurs
        //where id=? = on modifie uniquement la recette avec cet id
        $st=$this->pdo->prepare($sql);//on prepare la requete
        $st->execute([$titre,$description,$photo,$id]);//on envoie les nouvelles valeurs + l'id de la nouvelle recette
    }
    public function supprimer($id){ //fonction qui permet de supprimer toute une recette 
        $sql="DELETE FROM recettes WHERE id=?"; 
        //DELETE FROM recettes WHERE id=? 
        //WHERE id=? on supprime uniquement la recette avec cet id 
        $st=$this->pdo->prepare($sql); //on prepare la requete 
        $st->execute([$id]); // on envoie l'id de la recette a supprimer
    }
}