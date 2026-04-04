document.addEventListener("DOMContentLoaded", function(){

    /* ---- Aperçu photo avant envoi ---- */
    var fileInput = document.getElementById("image_ingredient");
    var preview   = document.getElementById("preview-ingredient");

    fileInput.addEventListener("change", function(){
        var file = this.files[0];
        if(file){
            var reader = new FileReader();
            reader.onload = function(e){
                preview.src = e.target.result;
                preview.style.display = "block";
            };
            reader.readAsDataURL(file);
        }
    });

    /* ---- Validation formulaire côté client ---- */
    document.getElementById("form-ingredient").addEventListener("submit", function(e){
        var nom   = document.getElementById("nom_ingredient").value.trim();
        var photo = document.getElementById("image_ingredient").files.length;
        var msg   = "";

        if(nom === "")  msg = "Le nom de l'ingrédient est obligatoire.";
        else if(!photo) msg = "La photo est obligatoire.";

        if(msg){
            e.preventDefault();
            var div = document.getElementById("erreur-form-js");
            if(!div){
                div           = document.createElement("div");
                div.id        = "erreur-form-js";
                div.className = "erreur";
                this.insertBefore(div, this.firstChild);
            }
            div.textContent = msg;
            window.scrollTo({ top: 0, behavior: "smooth" });
        }
    });

    /* ---- Recherche en temps réel ---- */
    var searchInput = document.getElementById("recherche-ingredients");
    var rows        = document.querySelectorAll("#table-ingredients tbody tr");
    
    var compteur    = document.getElementById("compteur-ingredients");
    var aucun       = document.getElementById("aucun-resultat");
    var total       = rows.length;

    // bloquer la touche Entrée sur la barre de recherche
    // sinon le <form action="recettes.php"> du header.php capte la soumission → 404
    searchInput.addEventListener("keydown", function(e){
        if(e.key === "Enter") e.preventDefault();
    });

    searchInput.addEventListener("input", function(){
        var q       = this.value.trim().toLowerCase();
        var visible = 0;

        rows.forEach(function(row){
            var nom = row.dataset.nom || "";
            if(nom.includes(q)){
                row.style.display = "";
                visible++;
            } else {
                row.style.display = "none";
            }
        });

        if(q === ""){
            compteur.textContent = total + " ingrédient" + (total > 1 ? "s" : "");
        } else {
            compteur.textContent = visible + " résultat" + (visible > 1 ? "s" : "") + " sur " + total;
        }

        aucun.style.display = (visible === 0) ? "block" : "none";
    });
});