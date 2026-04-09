/*
Validation du formulaire de login (côté client) :

- Vérifie que les champs "login" et "mot de passe" ne sont pas vides
- Intercepte l’envoi du formulaire avant le submit
- Si une erreur est détectée :
    - bloque l’envoi au serveur (preventDefault)
    - affiche un message d’erreur dans la page

Cette validation améliore l’expérience utilisateur, mais ne remplace pas la validation PHP côté serveur
*/

document.addEventListener("DOMContentLoaded", function(){

    var form = document.getElementById("form-login");
    // on recupere le formulaire

    if(form){
        form.addEventListener("submit", function(e){

            var login = document.getElementById("login").value.trim();
            var pwd   = document.getElementById("pwd").value.trim();
            var erreur = "";

            // on verifie que les champs sont pas vides
            if(login === ""){
                erreur = "Le login est obligatoire.";
            } else if(pwd === ""){
                erreur = "Le mot de passe est obligatoire.";
            }

            if(erreur !== ""){
                e.preventDefault(); // on empeche la soumission du formulaire
                // on affiche l'erreur dans le formulaire
                var div = document.getElementById("erreur-js");
                if(!div){
                    div = document.createElement("div");
                    div.id = "erreur-js";
                    div.className = "erreur";
                    form.insertBefore(div, form.firstChild);
                }
                div.textContent = erreur;
            }
        });
    }
});