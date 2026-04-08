document.addEventListener("DOMContentLoaded", function () {

    /* ============================================================
       APERÇU PHOTO RECETTE
    ============================================================ */
    var photoRecette = document.getElementById("photo");
    if (photoRecette) {
        photoRecette.addEventListener("change", function () {
            var preview = document.getElementById("preview-recette");
            if (!preview) return;
            var file = this.files[0];
            if (file) {
                var reader = new FileReader();
                reader.onload = function (e) {
                    preview.src           = e.target.result;
                    preview.style.display = "block";
                };
                reader.readAsDataURL(file);
            }
        });
    }
    /* ============================================================
       APERÇU PHOTO sur les lignes nouvel ingrédient
       On délègue l'événement au conteneur parent
       pour couvrir aussi les lignes ajoutées dynamiquement
    ============================================================ */
    var conteneurIngredients = document.getElementById("nouveaux-ingredients");
    if (conteneurIngredients) {
        conteneurIngredients.addEventListener("change", function (e) {
            if (!e.target.classList.contains("new-ing-photo")) return;
            var file    = e.target.files[0];
            var preview = e.target.parentNode.querySelector(".new-ing-preview");
            if (file && preview) {
                var reader = new FileReader();
                reader.onload = function (ev) {
                    preview.src           = ev.target.result;
                    preview.style.display = "block";
                };
                reader.readAsDataURL(file);
            }
        });
    }

    /* ============================================================
       FILTRAGE DES CHECKBOXES INGRÉDIENTS
    ============================================================ */
    var filtreIng  = document.getElementById("filtre-ingredients");
    var listeIng   = document.getElementById("liste-ingredients");
    var aucunIng   = document.getElementById("aucun-ingredient-filtre");

    if (filtreIng && listeIng) {
        // bloquer Entrée pour éviter soumission du form header
        filtreIng.addEventListener("keydown", function (e) {
            if (e.key === "Enter") e.preventDefault();
        });

        filtreIng.addEventListener("input", function () {
            var q       = this.value.trim().toLowerCase();
            var items   = listeIng.querySelectorAll(".checkbox-item");
            var visible = 0;

            items.forEach(function (item) {
                if (item.dataset.nom.includes(q)) {
                    item.style.display = "";
                    visible++;
                } else {
                    item.style.display = "none";
                }
            });

            if (aucunIng) {
                aucunIng.style.display = (visible === 0 && q !== "") ? "block" : "none";
            }
        });
    }

    /* ============================================================
       FILTRAGE DES CHECKBOXES TAGS
    ============================================================ */
    var filtreTag = document.getElementById("filtre-tags");
    var listeTag  = document.getElementById("liste-tags");
    var aucunTag  = document.getElementById("aucun-tag-filtre");

    if (filtreTag && listeTag) {
        filtreTag.addEventListener("keydown", function (e) {
            if (e.key === "Enter") e.preventDefault();
        });

        filtreTag.addEventListener("input", function () {
            var q       = this.value.trim().toLowerCase();
            var items   = listeTag.querySelectorAll(".checkbox-item");
            var visible = 0;

            items.forEach(function (item) {
                if (item.dataset.nom.includes(q)) {
                    item.style.display = "";
                    visible++;
                } else {
                    item.style.display = "none";
                }
            });

            if (aucunTag) {
                aucunTag.style.display = (visible === 0 && q !== "") ? "block" : "none";
            }
        });
    }

    /* ============================================================
       AJOUT DYNAMIQUE DE LIGNES — NOUVEL INGRÉDIENT
       Chaque ligne a : champ texte nom + input file photo + bouton suppr
    ============================================================ */
    var btnAjouterIng = document.getElementById("btn-ajouter-ingredient");
    var conteneurIng  = document.getElementById("nouveaux-ingredients");

    if (btnAjouterIng && conteneurIng) {
        btnAjouterIng.addEventListener("click", function () {
            // calcule l'index = nombre de lignes existantes
            var idx = conteneurIng.querySelectorAll(".new-ingredient-row").length;

            var row = document.createElement("div");
            row.className = "new-ingredient-row";
            row.id        = "new-ing-row-" + idx;

            row.innerHTML =
                '<div class="new-ingredient-fields">' +
                    '<input type="text"' +
                    '       name="new_ingredient_noms[]"' +
                    '       placeholder="Nom de l\'ingrédient"' +
                    '       class="new-ing-nom">' +
                    '<div class="new-ingredient-photo-wrap">' +
                        '<input type="file"' +
                        '       name="new_ingredient_photos[' + idx + ']"' +
                        '       accept="image/*"' +
                        '       class="new-ing-photo">' +
                        '<img class="new-ing-preview" src=""' +
                        '     style="display:none; max-width:60px; border-radius:6px;' +
                        '            border:2px solid var(--beige); margin-top:0.3rem;">' +
                    '</div>' +
                '</div>' +
                '<button type="button" class="btn-suppr-row" title="Supprimer">✕</button>';

            conteneurIng.appendChild(row);

            // focus sur le champ nom de la nouvelle ligne
            row.querySelector(".new-ing-nom").focus();
        });

        // suppression d'une ligne (délégation)
        conteneurIng.addEventListener("click", function (e) {
            if (!e.target.classList.contains("btn-suppr-row")) return;
            var row = e.target.closest(".new-ingredient-row");
            if (row) row.remove();
        });
    }

    /* ============================================================
       AJOUT DYNAMIQUE DE LIGNES — NOUVEAU TAG
    ============================================================ */
    var btnAjouterTag  = document.getElementById("btn-ajouter-tag");
    var conteneurTags  = document.getElementById("nouveaux-tags");

    if (btnAjouterTag && conteneurTags) {
        btnAjouterTag.addEventListener("click", function () {
            var row = document.createElement("div");
            row.className = "new-tag-row";
            row.innerHTML =
                '<input type="text" name="new_tags[]" placeholder="Nom du tag">' +
                '<button type="button" class="btn-suppr-row" title="Supprimer">✕</button>';
            conteneurTags.appendChild(row);
            row.querySelector("input").focus();
        });

        conteneurTags.addEventListener("click", function (e) {
            if (!e.target.classList.contains("btn-suppr-row")) return;
            var row = e.target.closest(".new-tag-row");
            if (row) row.remove();
        });
    }

    /* ============================================================
       VALIDATION AVANT SOUMISSION
    ============================================================ */
    var form = document.getElementById("form-ajouter") || document.getElementById("form-modifier");
    if (!form) return;

    form.addEventListener("submit", function (e) {
        var titre       = document.getElementById("titre").value.trim();
        var description = document.getElementById("description").value.trim();
        var photoInput  = document.getElementById("photo");
        var erreur      = "";

        if (titre === "") {
            erreur = "Le titre est obligatoire.";

        } else if (description === "") {
            erreur = "La description est obligatoire.";

        } else if (form.id === "form-ajouter" && photoInput && photoInput.files.length === 0) {
            // photo obligatoire uniquement à l'ajout
            erreur = "La photo de la recette est obligatoire.";

        } else {
            // vérifier que chaque nouvel ingrédient avec un nom a bien une photo
            var rows = form.querySelectorAll(".new-ingredient-row");
            for (var i = 0; i < rows.length; i++) {
                var nomInput   = rows[i].querySelector(".new-ing-nom");
                var photoFile  = rows[i].querySelector(".new-ing-photo");
                var nom        = nomInput  ? nomInput.value.trim()      : "";
                var hasPhoto   = photoFile ? photoFile.files.length > 0 : false;

                if (nom !== "" && !hasPhoto) {
                    erreur = "La photo est obligatoire pour l'ingrédient \"" + nom + "\".";
                    break;
                }
            }
        }
        if (erreur) {
            e.preventDefault();
            var div = document.getElementById("erreur-js");
            if (!div) {
                div           = document.createElement("div");
                div.id        = "erreur-js";
                div.className = "erreur";
                form.insertBefore(div, form.firstChild);
            }
            div.textContent = erreur;
            window.scrollTo({ top: 0, behavior: "smooth" });
        }
    });
});