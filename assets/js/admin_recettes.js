document.addEventListener("DOMContentLoaded", function(){

    var searchInput = document.getElementById("recherche-recettes");
    var rows        = document.querySelectorAll("#table-recettes tbody tr");
    var compteur    = document.getElementById("compteur-recettes");
    var aucune      = document.getElementById("aucune-recette");
    var total       = rows.length;

    // bloquer Entrée pour éviter la soumission du form header
    searchInput.addEventListener("keydown", function(e){
        if(e.key === "Enter") e.preventDefault();
    });

    searchInput.addEventListener("input", function(){
        var q       = this.value.trim().toLowerCase();
        var visible = 0;

        rows.forEach(function(row){
            // on cherche dans le titre ET la description
            var titre = row.dataset.titre || "";
            var desc  = row.dataset.desc  || "";
            if(titre.includes(q) || desc.includes(q)){
                row.style.display = "";
                visible++;
            } else {
                row.style.display = "none";
            }
        });

        if(q === ""){
            compteur.textContent = total + " recette" + (total > 1 ? "s" : "");
        } else {
            compteur.textContent = visible + " résultat" + (visible > 1 ? "s" : "") + " sur " + total;
        }

        aucune.style.display = (visible === 0 && q !== "") ? "block" : "none";
    });
});