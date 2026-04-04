// validation_recette.js — TasteLab
// Modale créée dynamiquement (pas dans le DOM au chargement)
// Photo recette obligatoire + photo ingrédient obligatoire

document.addEventListener("DOMContentLoaded", function () {

    /* ============================================================
       APERÇU PHOTO RECETTE
    ============================================================ */
    var photoInput = document.getElementById("photo");
    if (photoInput) {
        photoInput.addEventListener("change", function () {
            var preview = document.getElementById("preview");
            var file    = this.files[0];
            if (file) {
                var reader = new FileReader();
                reader.onload = function (e) {
                    preview.src          = e.target.result;
                    preview.style.display = "block";
                };
                reader.readAsDataURL(file);
            }
        });
    }

    /* ============================================================
       CRÉATION DYNAMIQUE DE LA MODALE
       La modale n'existe pas dans le HTML — on la crée ici
       pour qu'elle soit invisible au chargement de la page.
       Elle s'injecte dans le <body> uniquement quand on en a besoin.
    ============================================================ */
    function creerModale() {
        // overlay
        var overlay = document.createElement("div");
        overlay.id  = "modal-overlay";
        overlay.style.cssText = [
            "display:none",
            "position:fixed",
            "inset:0",
            "background:rgba(44,26,14,0.55)",
            "z-index:1000",
            "align-items:center",
            "justify-content:center",
            "backdrop-filter:blur(3px)"
        ].join(";");

        // boîte blanche centrale
        var box = document.createElement("div");
        box.id  = "modal-ingredient";
        box.innerHTML = [
            '<h3 style="font-family:\'Playfair Display\',serif;font-size:1.3rem;color:#2C1A0E;margin-bottom:0.3rem;">Nouvel ingrédient</h3>',
            '<p style="font-size:0.84rem;color:#7A6E65;margin-bottom:1.2rem;">',
                'Une <strong>photo est obligatoire</strong> pour créer un nouvel ingrédient.',
            '</p>',

            '<div class="form-group">',
                '<label for="modal-nom" style="display:block;font-size:0.83rem;font-weight:500;color:#7A6E65;margin-bottom:0.4rem;text-transform:uppercase;letter-spacing:0.4px;">Nom *</label>',
                '<input type="text" id="modal-nom" placeholder="Ex : Parmesan" autocomplete="off"',
                '    style="width:100%;padding:0.7rem 1rem;border:1.5px solid #EDE0CC;border-radius:10px;',
                '    background:#FAF6F0;font-family:\'DM Sans\',sans-serif;font-size:0.93rem;color:#2C1A0E;outline:none;">',
            '</div>',

            '<div class="form-group">',
                '<label for="modal-photo" style="display:block;font-size:0.83rem;font-weight:500;color:#7A6E65;margin-bottom:0.4rem;text-transform:uppercase;letter-spacing:0.4px;">',
                    'Photo * <span style="color:#C4622D;font-size:0.76rem;text-transform:none;">(obligatoire)</span>',
                '</label>',
                '<input type="file" id="modal-photo" accept="image/*" style="font-size:0.87rem;padding:0.4rem;">',
                '<img id="modal-preview" src="" style="display:none;max-width:100px;margin-top:0.5rem;border-radius:8px;border:2px solid #EDE0CC;">',
            '</div>',

            '<div id="modal-erreur" style="display:none;background:#FEF0EC;border:1.5px solid #C4622D;color:#C4622D;padding:0.7rem 1rem;border-radius:10px;font-size:0.87rem;margin-bottom:0.8rem;"></div>',

            '<div style="display:flex;gap:0.75rem;margin-top:1rem;align-items:center;">',
                '<button type="button" id="modal-confirmer"',
                '    style="background:#C4622D;color:#fff;border:none;padding:0.7rem 1.6rem;',
                '    border-radius:50px;font-family:\'DM Sans\',sans-serif;font-size:0.9rem;',
                '    font-weight:500;cursor:pointer;">Ajouter l\'ingrédient</button>',
                '<button type="button" id="modal-annuler"',
                '    style="background:transparent;border:1.5px solid #EDE0CC;color:#7A6E65;',
                '    padding:0.7rem 1.3rem;border-radius:50px;font-family:\'DM Sans\',sans-serif;',
                '    font-size:0.87rem;cursor:pointer;">Annuler</button>',
            '</div>'
        ].join("");

        // style boîte
        box.style.cssText = [
            "background:#ffffff",
            "border-radius:16px",
            "padding:2rem",
            "width:100%",
            "max-width:420px",
            "box-shadow:0 20px 60px rgba(44,26,14,0.25)",
            "animation:slideModal 0.22s ease"
        ].join(";");

        overlay.appendChild(box);
        document.body.appendChild(overlay);

        // animation CSS injectée une seule fois
        if (!document.getElementById("modal-style")) {
            var style = document.createElement("style");
            style.id  = "modal-style";
            style.textContent = [
                "@keyframes slideModal{",
                "  from{transform:translateY(-20px) scale(0.97);opacity:0}",
                "  to{transform:translateY(0) scale(1);opacity:1}",
                "}"
            ].join("");
            document.head.appendChild(style);
        }

        return overlay;
    }

    // on crée la modale une fois et on la réutilise
    var modalOverlay = creerModale();
    var modalCallback = null;

    /* -- Aperçu photo dans la modale -- */
    document.getElementById("modal-photo").addEventListener("change", function () {
        var file = this.files[0];
        if (file) {
            var reader = new FileReader();
            reader.onload = function (e) {
                var p = document.getElementById("modal-preview");
                p.src          = e.target.result;
                p.style.display = "block";
            };
            reader.readAsDataURL(file);
        }
    });

    /* -- Focus input nom quand la modale s'ouvre -- */
    function ouvrirModale(nomPreremplit, callback) {
        document.getElementById("modal-nom").value        = nomPreremplit || "";
        document.getElementById("modal-photo").value      = "";
        document.getElementById("modal-preview").style.display = "none";
        document.getElementById("modal-preview").src      = "";
        document.getElementById("modal-erreur").style.display = "none";
        document.getElementById("modal-erreur").textContent   = "";
        modalOverlay.style.display = "flex";
        modalCallback = callback;
        setTimeout(function () { document.getElementById("modal-nom").focus(); }, 80);
    }

    function fermerModale() {
        modalOverlay.style.display = "none";
        modalCallback = null;
    }

    document.getElementById("modal-annuler").addEventListener("click", fermerModale);

    // clic sur l'overlay (hors boîte) → fermer
    modalOverlay.addEventListener("click", function (e) {
        if (e.target === modalOverlay) fermerModale();
    });

    // bouton Ajouter dans la modale
    document.getElementById("modal-confirmer").addEventListener("click", function () {
        var nom   = document.getElementById("modal-nom").value.trim();
        var photo = document.getElementById("modal-photo").files[0];
        var err   = document.getElementById("modal-erreur");

        if (!nom) {
            err.textContent  = "Le nom de l'ingrédient est obligatoire.";
            err.style.display = "block";
            return;
        }
        if (!photo) {
            err.textContent  = "La photo est obligatoire pour créer un nouvel ingrédient.";
            err.style.display = "block";
            return;
        }

        if (typeof modalCallback === "function") modalCallback(nom, photo);
        fermerModale();
    });

    /* ============================================================
       AUTOCOMPLÉTION GÉNÉRIQUE
    ============================================================ */
    function initAutocomplete(inputId, chipsId, dropdownId, hiddenName, data, preselected, avecModale) {

        var input    = document.getElementById(inputId);
        var chipsBox = document.getElementById(chipsId);
        var dropdown = document.getElementById(dropdownId);
        var newFields = document.getElementById("new-ingredients-fields");

        if (!input || !chipsBox || !dropdown) return;

        var selected = {};

        /* -- Pré-sélection -- */
        if (preselected && preselected.length) {
            preselected.forEach(function (id) {
                var item = data.find(function (d) { return String(d.id) === String(id); });
                if (item) addChip(item.id, item.nom);
            });
        }

        /* -- Saisie -- */
        input.addEventListener("input", function () {
            showDropdown(this.value.trim().toLowerCase());
        });

        input.addEventListener("focus", function () {
            var q = this.value.trim().toLowerCase();
            if (q.length > 0) showDropdown(q);
        });

        document.addEventListener("click", function (e) {
            if (!input.contains(e.target) && !dropdown.contains(e.target)) {
                hideDropdown();
            }
        });

        /* -- Entrée -- */
        input.addEventListener("keydown", function (e) {
            if (e.key !== "Enter") return;
            e.preventDefault();
            var q = input.value.trim();
            if (!q) return;

            var match = data.find(function (d) {
                return d.nom.toLowerCase() === q.toLowerCase();
            });

            if (match) {
                addChip(match.id, match.nom);
                input.value = "";
                hideDropdown();
            } else {
                if (avecModale) {
                    input.value = "";
                    hideDropdown();
                    ouvrirModale(q, function (nom, fichier) {
                        addChipNouvelIngredient(nom, fichier);
                    });
                } else {
                    addChip("new_" + q, q);
                    input.value = "";
                    hideDropdown();
                }
            }
        });

        /* -- Dropdown -- */
        function showDropdown(q) {
            dropdown.innerHTML = "";

            var results = data.filter(function (d) {
                return d.nom.toLowerCase().includes(q) && !selected[d.id];
            });

            var exactMatch = data.find(function (d) {
                return d.nom.toLowerCase() === q;
            });

            results.forEach(function (d) {
                var li = document.createElement("li");
                li.textContent = d.nom;
                li.addEventListener("click", function () {
                    addChip(d.id, d.nom);
                    input.value = "";
                    hideDropdown();
                });
                dropdown.appendChild(li);
            });

            // option "+ Créer" si pas de correspondance exacte
            if (q.length > 0 && !exactMatch) {
                var li = document.createElement("li");
                li.className   = "autocomplete-create";
                li.textContent = '+ Créer "' + q + '"';
                li.addEventListener("click", function () {
                    var saisi = q;
                    input.value = "";
                    hideDropdown();
                    if (avecModale) {
                        ouvrirModale(saisi, function (nom, fichier) {
                            addChipNouvelIngredient(nom, fichier);
                        });
                    } else {
                        addChip("new_" + saisi, saisi);
                    }
                });
                dropdown.appendChild(li);
            }

            dropdown.style.display = dropdown.children.length > 0 ? "block" : "none";
        }

        function hideDropdown() { dropdown.style.display = "none"; }

        /* -- Chip existant ou tag nouveau -- */
        function addChip(id, nom) {
            if (selected[id]) return;
            selected[id] = nom;

            var chip = creerChipElement(nom, function () {
                delete selected[id];
                var form = document.getElementById("form-ajouter") || document.getElementById("form-modifier");
                if (form) {
                    var h = form.querySelector('input[type="hidden"][name="' + hiddenName + '"][value="' + id + '"]');
                    if (h) h.remove();
                }
            });
            chipsBox.appendChild(chip);

            var form = document.getElementById("form-ajouter") || document.getElementById("form-modifier");
            if (form) {
                var hidden   = document.createElement("input");
                hidden.type  = "hidden";
                hidden.name  = hiddenName;
                hidden.value = id;
                form.appendChild(hidden);
            }
        }

        /* -- Chip nouvel ingrédient avec photo -- */
        function addChipNouvelIngredient(nom, fichier) {
            if (!newFields) return;
            var idx = newFields.querySelectorAll('input[name="new_ingredient_noms[]"]').length;

            var chip = creerChipElement(nom, function () {
                var nomField = newFields.querySelector('[data-idx="' + idx + '"]');
                if (nomField) nomField.value = "";
            });
            chipsBox.appendChild(chip);

            var inputNom         = document.createElement("input");
            inputNom.type        = "hidden";
            inputNom.name        = "new_ingredient_noms[]";
            inputNom.value       = nom;
            inputNom.dataset.idx = idx;
            newFields.appendChild(inputNom);

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
                console.warn("DataTransfer non supporté.", err);
            }
        }
    }

    /* -- Chip visuel -- */
    function creerChipElement(nom, onRemove) {
        var chip      = document.createElement("span");
        chip.className = "chip";

        var label     = document.createElement("span");
        label.textContent = nom;

        var btn       = document.createElement("button");
        btn.type      = "button";
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
       INIT
    ============================================================ */
    var ingredientsData = window.TASTELAB_INGREDIENTS        || [];
    var tagsData        = window.TASTELAB_TAGS               || [];
    var selIngreds      = window.TASTELAB_SEL_INGREDIENTS    || [];
    var selTags         = window.TASTELAB_SEL_TAGS           || [];

    initAutocomplete(
        "search-ingredients", "chips-ingredients", "dropdown-ingredients",
        "ingredients[]", ingredientsData, selIngreds, true   // avecModale = true
    );

    initAutocomplete(
        "search-tags", "chips-tags", "dropdown-tags",
        "tags[]", tagsData, selTags, false                   // avecModale = false
    );

    /* ============================================================
       VALIDATION AVANT SOUMISSION
       Photo recette obligatoire côté JS
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
        } else if (photoInput && photoInput.files.length === 0) {
            // sur la page modifier, la photo est optionnelle (on garde l'ancienne)
            // sur ajouter, elle est obligatoire — on le détecte via l'id du form
            if (form.id === "form-ajouter") {
                erreur = "La photo de la recette est obligatoire.";
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