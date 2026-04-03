// sidebar.js — faire glisser la sidebar

function toggleSidebar() {
    var sidebar = document.getElementById("sidebar");

    if(sidebar.style.left === "0px") {
        sidebar.style.left = "-260px"; // on cache la sidebar a gauche
    } else {
        sidebar.style.left = "0px"; // on affiche la sidebar
    }
}