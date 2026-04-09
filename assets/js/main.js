/*main.js — initialisation generale  */
document.addEventListener("DOMContentLoaded", function(){

    
    var liens = document.querySelectorAll(".sidebar-link");
    liens.forEach(function(lien){
        if(lien.href === window.location.href){
            lien.classList.add("active");
            // on ajoute la classe active sur le lien de la page courante
        }
    });
});

