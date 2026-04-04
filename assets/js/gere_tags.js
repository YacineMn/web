document.addEventListener("DOMContentLoaded", function(){
    document.getElementById("form-tag").addEventListener("submit", function(e){
        var nom = document.getElementById("nom_tag").value.trim();
        if(nom === ""){
            e.preventDefault();
            alert("Le nom du tag est obligatoire.");
        }
    });
});