(function(){
    document.addEventListener('DOMContentLoaded', function(){

        let sidebar = document.getElementById('sidebar');
        if(sidebar === null){
            return;
        }
        
        let menuBtn = document.getElementById('menu-toggle');
        let closeBtn = document.getElementById('close-sidebar');
        let overlay = document.getElementById('overlay');

        // ouvrir
        menuBtn.addEventListener('click', function(){
            sidebar.classList.add('active');
            overlay.classList.add('active');
        });

        // fermer bouton
        closeBtn.addEventListener('click', function(){
            sidebar.classList.remove('active');
            overlay.classList.remove('active');
        });

        // fermer clic extérieur
        overlay.addEventListener('click', function(){
            sidebar.classList.remove('active');
            overlay.classList.remove('active');
        });

    });
})();