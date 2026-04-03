/* sidebar.js — gestion menu mobile */
document.addEventListener("DOMContentLoaded", function(){

    // on cree le bouton burger pour mobile
    var burger = document.createElement("button");
    burger.id = "burger";
    burger.innerHTML = "☰";
    burger.style.cssText = "display:none; background:none; border:none; font-size:1.5rem; cursor:pointer; color:var(--brun);";
    // on ajoute le bouton dans le header
    var header = document.querySelector(".site-header");
    if(header) header.insertBefore(burger, header.firstChild);

    var sidebar = document.querySelector(".sidebar");

    // on affiche le burger seulement sur mobile
    function checkMobile(){
        if(window.innerWidth <= 768){
            burger.style.display = "block";
        } else {
            burger.style.display = "none";
            if(sidebar) sidebar.style.display = "flex";
        }
        
    }

    checkMobile();
    window.addEventListener("resize", checkMobile);

    // on ouvre/ferme la sidebar au clic sur le burger
    burger.addEventListener("click", function(){
        if(sidebar){
            if(sidebar.style.display === "none" || sidebar.style.display === ""){
                sidebar.style.display = "flex";
                sidebar.style.position = "fixed";
                sidebar.style.zIndex = "200";
            } else {
                sidebar.style.display = "none";
            }
        }
    });
});