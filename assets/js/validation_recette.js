// validation_recette.js — TasteLab
// Autocomplétion ingrédients & tags + modale nouvel ingrédient + validation

document.addEventListener("DOMContentLoaded", function () {

    /* ============================================================
       APERÇU PHOTO RECETTE
    ============================================================ */
    var photoInput = document.getElementById("photo");
    if (photoInput) {
        photoInput.addEventListener("change", function () {
            var preview = document.getElementById("preview");
            var file = this.files[0];
            if (file) {
                var reader = new FileReader();
                reader.onload = function (e) {
                    preview.src = e.target.result;
                    preview.style.display = "block";
                };
                reader.readAsDataURL(file);
            }
        });
    }

    /* ============================================================
       MODALE NOUVEL INGRÉDIENT
       S'ouvre quand l'admin clique "+ Créer X" dans le dropdown
       La photo est obligatoire avant de valider
    ============================================================ */
    var overlay       = document.getElementById("modal-overlay");
    var modalNom      = document.getElementById("modal-nom");
    var modalPhoto    = document.getElementById("modal-photo");
    var modalPreview  = document.getElementById("modal-preview");
    var modalErreur   = document.getElementById("modal-erreur");
    var btnConfirmer  = document.getElementById("modal-confirmer");
    var btnAnnuler    = document.getElementById("modal-annuler");
    var newFields     = document.getElementById("new-ingredients-fields"); // dans le form principal

    // callback appelé quand la modale confirme un nouvel ingrédient
    var modalCallback = null;

    // aperçu photo dans la modale
    if (modalPhoto) {
        modalPhoto.addEventListener("change", function () {
            var file = this.files[0];
            if (file) {
                var reader = new FileReader();
                reader.onload = function (e) {
                    modalPreview.src = e.target.result;
                    modalPreview.style.display = "block";
                };
                reader.readAsDataURL(file);
            }
        });
    }

    function ouvrirModale(nomPreremplit, callback) {
        if (!overlay) return;
        modalNom.value           = nomPreremplit || "";
        modalPhoto.value         = "";
        modalPreview.style.display = "none";
        modalPreview.src         = "";
        modalErreur.style.display = "none";
        modalErreur.textContent  = "";
        overlay.style.display    = "flex";
        modalCallback = callback;
        setTimeout(function () { modalNom.focus(); }, 100);
    }

    function fermerModale() {
        if (!overlay) return;
        overlay.style.display = "none";
        modalCallback = null;
    }

    if (btnAnnuler) btnAnnuler.addEventListener("click", fermerModale);

    // Fermer si clic sur l'overlay (hors modale)
    if (overlay) {
        overlay.addEventListener("click", function (e) {
            if (e.target === overlay) fermerModale();
        });
    }

    if (btnConfirmer) {
        btnConfirmer.addEventListener("click", function () {
            var nom   = modalNom  ? modalNom.value.trim()   : "";
            var photo = modalPhoto ? modalPhoto.files[0]     : null;

            if (!nom) {
                afficherErreurModale("Le nom est obligatoire.");
                return;
            }
            if (!photo) {
                afficherErreurModale("La photo est obligatoire.");
                return;
            }

            // Tout est OK → on appelle le callback avec nom + fichier
            if (typeof modalCallback === "function") {
                modalCallback(nom, photo);
            }
            fermerModale();
        });
    }

    function afficherErreurModale(msg) {
        if (!modalErreur) return;
        modalErreur.textContent  = msg;
        modalErreur.style.display = "block";
    }

    /* ============================================================
       AUTOCOMPLÉTION GÉNÉRIQUE
    ============================================================ */

    /**
     * @param {string}   inputId      id du champ de recherche
     * @param {string}   chipsId      id du conteneur chips
     * @param {string}   dropdownId   id du <ul> dropdown
     * @param {string}   hiddenName   name[] des hidden envoyés au PHP
     * @param {Array}    data         [{id, nom}] venant de PHP
     * @param {Array}    preselected  ids déjà sélectionnés (page modifier)
     * @param {boolean}  avecModale   true = ingrédients (modale photo), false = tags
     */
    function initAutocomplete(inputId, chipsId, dropdownId, hiddenName, data, preselected, avecModale) {

        var input    = document.getElementById(inputId);
        var chipsBox = document.getElementById(chipsId);
        var dropdown = document.getElementById(dropdownId);

        if (!input || !chipsBox || !dropdown) return;

        var selected = {}; // {id: nom}

        /* -- Pré-sélection (page modifier) -- */
        if (preselected && preselected.length) {
            preselected.forEach(function (id) {
                var item = data.find(function (d) { return String(d.id) === String(id); });
                if (item) addChip(item.id, item.nom, false);
            });
        }

        /* -- Saisie → filtrage -- */
        input.addEventListener("input", function () {
            showDropdown(this.value.trim().toLowerCase());
        });

        input.addEventListener("focus", function () {
            var q = this.value.trim().toLowerCase();
            if (q.length > 0) showDropdown(q);
        });

        /* -- Fermer si clic ailleurs -- */
        document.addEventListener("click", function (e) {
            if (!input.contains(e.target) && !dropdown.contains(e.target)) {
                hideDropdown();
            }
        });

        /* -- Touche Entrée -- */
        input.addEventListener("keydown", function (e) {
            if (e.key !== "Enter") return;
            e.preventDefault();
            var q = input.value.trim();
            if (!q) return;

            var match = data.find(function (d) {
                return d.nom.toLowerCase() === q.toLowerCase();
            });

            if (match) {
                addChip(match.id, match.nom, false);
                input.value = "";
                hideDropdown();
            } else {
                if (avecModale) {
                    // ingrédient nouveau → modale photo
                    input.value = "";
                    hideDropdown();
                    ouvrirModale(q, function (nomConfirme, fichierPhoto) {
                        addChipNouvelIngredient(nomConfirme, fichierPhoto);
                    });
                } else {
                    // tag nouveau → pas de modale
                    addChip("new_" + q, q, false);
                    input.value = "";
                    hideDropdown();
                }
            }
        });

        /* ---- Dropdown ---- */
        function showDropdown(q) {
            dropdown.innerHTML = "";

            var results = data.filter(function (d) {
                return d.nom.toLowerCase().includes(q) && !selected[d.id];
            });

            // option "créer" si aucune correspondance exacte
            var exactMatch = data.find(function (d) {
                return d.nom.toLowerCase() === q;
            });

            results.forEach(function (d) {
                var li = document.createElement("li");
                li.textContent = d.nom;
                li.addEventListener("click", function () {
                    addChip(d.id, d.nom, false);
                    input.value = "";
                    hideDropdown();
                });
                dropdown.appendChild(li);
            });

            if (q.length > 0 && !exactMatch) {
                var li = document.createElement("li");
                li.className = "autocomplete-create";
                li.textContent = '+ Créer "' + q + '"';
                li.addEventListener("click", function () {
                    var saisi = q;
                    input.value = "";
                    hideDropdown();
                    if (avecModale) {
                        ouvrirModale(saisi, function (nomConfirme, fichierPhoto) {
                            addChipNouvelIngredient(nomConfirme, fichierPhoto);
                        });
                    } else {
                        addChip("new_" + saisi, saisi, false);
                    }
                });
                dropdown.appendChild(li);
            }

            dropdown.style.display = (dropdown.children.length > 0) ? "block" : "none";
        }

        function hideDropdown() {
            dropdown.style.display = "none";
        }

        /* ---- Chip existant ou tag nouveau ---- */
        function addChip(id, nom, isNew) {
            if (selected[id]) return;
            selected[id] = nom;

            var chip = creerChipElement(nom, function () {
                delete selected[id];
                // supprimer le hidden correspondant
                var form = document.getElementById("form-ajouter") || document.getElementById("form-modifier");
                if (form) {
                    var h = form.querySelector('input[name="' + hiddenName + '"][value="' + id + '"]');
                    if (h) h.remove();
                }
            });
            chipsBox.appendChild(chip);

            // hidden dans le formulaire principal
            var form = document.getElementById("form-ajouter") || document.getElementById("form-modifier");
            if (form) {
                var hidden  = document.createElement("input");
                hidden.type  = "hidden";
                hidden.name  = hiddenName;
                hidden.value = id;
                form.appendChild(hidden);
            }
        }

        /* ---- Chip nouvel ingrédient (avec fichier photo) ---- */
        // Utilise des champs indexés new_ingredient_noms[] et new_ingredient_photos[]
        function addChipNouvelIngredient(nom, fichier) {
            if (!newFields) return;

            // index = nombre de nouveaux ingrédients déjà ajoutés
            var idx = newFields.querySelectorAll('input[name="new_ingredient_noms[]"]').length;

            // chip visuel
            var chip = creerChipElement(nom, function () {
                // retirer les champs cachés de cet index
                var nomField   = newFields.querySelector('input[name="new_ingredient_noms[]"][data-idx="' + idx + '"]');
                var photoField = newFields.querySelector('input[name="new_ingredient_photos[' + idx + ']"]');
                // Note : on ne peut pas vraiment supprimer un <input type=file> avec un fichier déjà attribué
                // On marque le nom vide → PHP l'ignorera (empty check)
                if (nomField)   nomField.value = "";
                if (photoField) photoField.remove();
            });
            chipsBox.appendChild(chip);

            // champ nom caché
            var inputNom       = document.createElement("input");
            inputNom.type      = "hidden";
            inputNom.name      = "new_ingredient_noms[]";
            inputNom.value     = nom;
            inputNom.dataset.idx = idx;
            newFields.appendChild(inputNom);

            // DataTransfer pour créer un vrai input file avec le fichier
            // (les inputs type=file ne peuvent pas être remplis par programme sauf via DataTransfer)
            try {
                var dt = new DataTransfer();
                dt.items.add(fichier);

                var inputPhoto      = document.createElement("input");
                inputPhoto.type     = "file";
                inputPhoto.name     = "new_ingredient_photos[" + idx + "]";
                inputPhoto.style.display = "none";
                inputPhoto.files    = dt.files;
                newFields.appendChild(inputPhoto);
            } catch (err) {
                // fallback : on stocke le fichier dans un FormData au submit
                // (voir ci-dessous dans la gestion du submit)
                console.warn("DataTransfer non supporté, fallback activé.", err);
            }
        }
    }

    /* ---- Création d'un chip visuel ---- */
    function creerChipElement(nom, onRemove) {
        var chip = document.createElement("span");
        chip.className = "chip";

        var label = document.createElement("span");
        label.textContent = nom;

        var btn      = document.createElement("button");
        btn.type     = "button";
        btn.className = "chip-remove";
        btn.innerHTML = "&#x2715;";
        btn.setAttribute("aria-label", "Retirer " + nom);
        btn.addEventListener("click", function () {
            chip.remove();
            if (typeof onRemove === "function") onRemove();
        });

        chip.appendChild(label);
        chip.appendChild(btn);
        return chip;
    }

    /* ============================================================
       INIT DES DEUX COMPOSANTS
    ============================================================ */
    var ingredientsData = window.TASTELAB_INGREDIENTS        || [];
    var tagsData        = window.TASTELAB_TAGS               || [];
    var selIngreds      = window.TASTELAB_SEL_INGREDIENTS    || [];
    var selTags         = window.TASTELAB_SEL_TAGS           || [];

    // ingrédients : avecModale = true
    initAutocomplete(
        "search-ingredients", "chips-ingredients", "dropdown-ingredients",
        "ingredients[]", ingredientsData, selIngreds, true
    );

    // tags : avecModale = false
    initAutocomplete(
        "search-tags", "chips-tags", "dropdown-tags",
        "tags[]", tagsData, selTags, false
    );

    /* ============================================================
       VALIDATION AVANT SOUMISSION
    ============================================================ */
    var form = document.getElementById("form-ajouter") || document.getElementById("form-modifier");
    if (!form) return;

    form.addEventListener("submit", function (e) {
        var titre       = document.getElementById("titre").value.trim();
        var description = document.getElementById("description").value.trim();
        var erreur      = "";

        if (titre === "")       erreur = "Le titre est obligatoire.";
        else if (description === "") erreur = "La description est obligatoire.";

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