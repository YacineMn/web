// sidebar.js — ouvrir et fermer la sidebar

function toggleSidebar() {
    var sidebar     = document.getElementById("sidebar");
    var mainWrapper = document.querySelector(".main-wrapper");
    var footer      = document.querySelector(".site-footer");

    // on ajoute ou retire la classe ouverte sur la sidebar
    sidebar.classList.toggle("ouverte");

    // le contenu se decale en meme temps que la sidebar
    mainWrapper.classList.toggle("decale");

    // le footer se decale aussi
    if(footer) footer.classList.toggle("decale");
}