// Sticky header au scroll
window.addEventListener('scroll', function() {
    const headerMain = document.querySelector('.header-main');
    const headerTop = document.querySelector('.header-top');
    const bodyEl = document.body;
    
    const headerTopHeight = headerTop.offsetHeight;
    
    if (window.scrollY > headerTopHeight) {
        headerMain.classList.add('sticky-header');
        bodyEl.classList.add('header-sticky-active');
    } else {
        headerMain.classList.remove('sticky-header');
        bodyEl.classList.remove('header-sticky-active');
    }
});

// Prévisualisation des images
function previewImages(input) {
    const previewContainer = document.getElementById('imagePreview');
    previewContainer.innerHTML = '';
    
    // Vérifie si des fichiers ont été sélectionnés
    if (input.files.length > 0) {
        // Crée un résumé indiquant combien d'images ont été sélectionnées
        const summary = document.createElement('div');
        summary.innerHTML = `<p><strong>${input.files.length} image(s) sélectionnée(s)</strong></p>`;
        
        // Applique des styles au résumé
        summary.style.background = '#e8f5e8';
        summary.style.padding = '8px';
        summary.style.marginBottom = '10px';
        previewContainer.appendChild(summary);
        
        // Affichage des images (max 5)
        Array.from(input.files)
        .slice(0, 5) // Limite l'affichage aux 5 premières images
        .forEach((file, index) => {

            // Vérifie que le fichier est bien une image 
            if (file.type.startsWith('image/')) {
                const reader = new FileReader();
                
                reader.onload = function(e) {
                    
                    const imageDiv = document.createElement('div');
                    imageDiv.style.display = 'inline-block';
                    imageDiv.style.margin = '5px';
                    imageDiv.style.border = '1px solid #ddd';
                    imageDiv.style.padding = '5px';
                    imageDiv.style.width = '120px';
                    imageDiv.style.textAlign = 'center';
                    
                    imageDiv.innerHTML = 
                        `<img src="${e.target.result}" style="width:100%; height:80px; object-fit:cover;">
                         <p style="font-size:11px; margin:5px 0;">Image ${index + 1}: ${file.name}</p>
                         <small style="font-size:9px; color:#666;">${Math.round(file.size / 1024)} KB</small>`;
                    
                    previewContainer.appendChild(imageDiv);
                };
                
                reader.readAsDataURL(file);
            }
        });
    }
}

// Script pour Fancybox + Zoom + Plein écran
document.addEventListener('DOMContentLoaded', function() {

    // Active Fancybox sur tous les éléments avec l'attribut [data-fancybox]
    if (window.Fancybox) {
        Fancybox.bind('[data-fancybox]');
    }
    
    // Zoom avec loupe personnalisée ---
    const img = document.getElementById('imageZoom');
    const container = document.querySelector('.zoom');
    
    if (img && container) {

        // Création de l'élément loupe
        const loupe = document.createElement('div');
        loupe.style.cssText = 'position:absolute;border:1px solid #000;border-radius:50%;width:150px;height:150px;display:none;pointer-events:none;z-index:100;background-repeat:no-repeat;box-shadow:0 0 10px rgba(0,0,0,0.5)';
        container.appendChild(loupe);
        
        // Affiche la loupe au survol de l'image
        img.onmouseenter = () => {
            loupe.style.display = 'block';
            loupe.style.backgroundImage = `url(${img.src})`;
            loupe.style.backgroundSize = `${img.width * 2.5}px ${img.height * 2.5}px`;
        };
        
        // Cache la loupe à la sortie de l'image
        img.onmouseleave = () => loupe.style.display = 'none';
        
        // Déplace la loupe et ajuste le zoom en fonction de la position de la souris
        img.onmousemove = (e) => {
            const rect = img.getBoundingClientRect();
            const x = e.clientX - rect.left;
            const y = e.clientY - rect.top;

            // Positionne la loupe au bon endroit
            loupe.style.left = (x - 60) + 'px';
            loupe.style.top = (y - 60) + 'px';

            // Ajuste la position du fond pour l'effet de zoom
            loupe.style.backgroundPosition = `${-(x * 2.5 - 60)}px ${-(y * 2.5 - 60)}px`;
        };
    }
    
    // Bouton plein écran 
    const btn = document.getElementById('fullscreenBtn');
    // Si le bouton "fullscreenBtn" existe, un clic dessus déclenche un clic sur l'image
    if (btn) btn.onclick = () => img.click();
});

//  fonction changeImage
function changeImage(newSrc, element) {
    const img = document.getElementById('imageZoom');
    if (img) {
        img.src = newSrc;
        const loupe = document.querySelector('.zoom div[style*="position:absolute"]');
        if (loupe) loupe.style.backgroundImage = `url(${newSrc})`;
    }
    
    document.querySelectorAll('.galerie-miniature').forEach(thumb => {
        thumb.classList.remove('active');
    });
    element.classList.add('active');
}


// Confirmation pour retirer des favoris
document.addEventListener('DOMContentLoaded', function() {
    const linksFavoris = document.querySelectorAll('a[href*="/favoris/switch"]');
    
    linksFavoris.forEach(link => {
        link.addEventListener('click', function(e) {
            // Si c'est pour retirer des favoris, demander confirmation
            if (this.classList.contains('btn-favori-active') || 
                this.querySelector('.fa-heart-broken')) {
                if (!confirm('Êtes-vous sûr de vouloir retirer cette enchère de vos favoris ?')) {
                    e.preventDefault();
                }
            }
        });
    });
});

// Pagination pour les tables dans la page profil
document.addEventListener("DOMContentLoaded", function () {
    
    // Configuration des tables à paginer
    const tablesConfig = [
        {
            tableId: "table_mesOffres",
            rowsSelector: ".mesOffres", 
            paginationId: "pagination-nbrs",
            btnPrevId: "btn-previous",
            btnNextId: "btn-next",
            rowsPerPage: 5
        },
        {
            tableId: "table_timbres",
            rowsSelector: "#table_timbres .timbre-row",
            paginationId: "pagination-nbrs-timbres", 
            btnPrevId: "btn-previous-timbres",
            btnNextId: "btn-next-timbres",
            rowsPerPage: 5
        },
        {
            tableId: "table_favoris", 
            rowsSelector: "#table_favoris .timbre-row",
            paginationId: "pagination-nbrs-favoris",
            btnPrevId: "btn-previous-favoris", 
            btnNextId: "btn-next-favoris",
            rowsPerPage: 5
        }
    ];

    // Fonction pour initialiser la pagination d'une table
    function initTablePagination(config) {
        // Vérification de l'existence de tous les éléments nécessaires AVANT d'initialiser la pagination
        const tableContainer = document.getElementById(config.tableId);
        const rows = document.querySelectorAll(config.rowsSelector);
        const paginationContainer = document.getElementById(config.paginationId);
        const btnPrev = document.getElementById(config.btnPrevId);
        const btnNext = document.getElementById(config.btnNextId);

        // Si un des éléments critiques n'existe pas, on sort de la fonction
        if (!rows.length || !paginationContainer || !btnPrev || !btnNext || !tableContainer) {
            return; // Arrête l'exécution si les éléments de pagination n'existent pas
        }

        const rowsPerPage = config.rowsPerPage;
        const totalPages = Math.ceil(rows.length / rowsPerPage);
        let currentPage = 1;

        function showPage(page) {
            const start = (page - 1) * rowsPerPage;
            const end = start + rowsPerPage;

            rows.forEach((row, index) => {
                row.style.display = (index >= start && index < end) ? "" : "none";
            });

            // Mettre à jour les boutons
            btnPrev.disabled = page === 1;
            btnNext.disabled = page === totalPages;

            // Mettre à jour la pagination active pour cette table spécifique
            paginationContainer.querySelectorAll(".pagination-nbr").forEach(btn => {
                btn.classList.toggle("active", parseInt(btn.textContent) === page);
            });

        }

        function setupPagination() {
            // Vider le conteneur de pagination avant d'ajouter les boutons
            paginationContainer.innerHTML = '';
                     
            for (let i = 1; i <= totalPages; i++) {
                const btn = document.createElement("button");
                btn.textContent = i;
                btn.classList.add("pagination-nbr");
                if (i === currentPage) btn.classList.add("active");

                btn.addEventListener("click", () => {
                    currentPage = i;
                    showPage(currentPage);
                    tableContainer.scrollIntoView({ behavior: "smooth" });
                });

                paginationContainer.appendChild(btn);
            }
        }

        btnPrev.addEventListener("click", () => {
            if (currentPage > 1) {
                currentPage--;
                showPage(currentPage);
                tableContainer.scrollIntoView({ behavior: "smooth" });
            }
        });

        btnNext.addEventListener("click", () => {
            if (currentPage < totalPages) {
                currentPage++;
                showPage(currentPage);
                tableContainer.scrollIntoView({ behavior: "smooth" });
            }
        });

        // Initialiser la pagination seulement si on a des lignes à paginer
        if (totalPages > 0) {
            setupPagination();
            showPage(currentPage);
        }
    }

    // Initialiser la pagination pour chaque table
    tablesConfig.forEach(config => {
        initTablePagination(config);
    });
});

// Gestion des onglets Enchères Actives / Archivées
document.addEventListener('DOMContentLoaded', function() {

    // Fonction pour mettre à jour le nombre total d'enchères
    function mettreAJourResultats() {
        const resultNbr = document.querySelector('.resultat-nbr');
        if (!resultNbr) return;

        const cartes = document.querySelectorAll('.carte');
        let totalActives = 0;
        let totalArchivees = 0;

        cartes.forEach(carte => {
            const typeEnchere = carte.getAttribute('data-type');
            if (typeEnchere === 'active') totalActives++;
            if (typeEnchere === 'termine') totalArchivees++;
        });

        const total = totalActives + totalArchivees;
        resultNbr.innerHTML = `<strong>${total}</strong> enchères trouvées (<strong>${totalActives} actives</strong>, <strong>${totalArchivees} archivées</strong>)`;
    }

    // Fonction pour filtrer les enchères selon le type
    function filtrerEncheres(type) {
        const cartes = document.querySelectorAll('.carte');
        const grille = document.querySelector('.grille');
        let compteur = 0;

        // Retirer le message "no-results" s'il existe
        const messageExistant = grille.querySelector('.no-results');
        if (messageExistant) messageExistant.remove();

        cartes.forEach(carte => {
            const typeEnchere = carte.getAttribute('data-type');
            if (typeEnchere === type) {
                carte.style.display = 'block';
                compteur++;
            } else {
                carte.style.display = 'none';
            }
        });

        // Si aucune enchère trouvée, afficher un message
        if (compteur === 0) {
            const message = document.createElement('div');
            message.className = 'no-results';
            message.innerHTML = `
                <h3 class="taille_texte_300">Aucune enchère ${type === 'active' ? 'en cours' : 'archivée'}</h3>
                <p>Il n'y a actuellement aucune enchère ${type === 'active' ? 'active' : 'terminée'}.</p>
            `;
            grille.appendChild(message);
        }

        mettreAJourResultats();
    }

    // Gestion des onglets
    const tabButtons = document.querySelectorAll('.btn-tab');
    const grille = document.querySelector('.grille');

    if (tabButtons.length > 0 && grille) {
        filtrerEncheres('active'); // afficher par défaut

        tabButtons.forEach(button => {
            button.addEventListener('click', function() {
                tabButtons.forEach(btn => btn.classList.remove('active'));
                this.classList.add('active');

                const type = this.getAttribute('data-tab');
                filtrerEncheres(type);
            });
        });
    }
});


// Fonction de tri des cartes par prix
document.addEventListener('DOMContentLoaded', function() {
    const triSelect = document.querySelector('.tri-select');
    const grille = document.querySelector('.grille');
    
    if (triSelect && grille) {
        triSelect.addEventListener('change', function() {
            const ordre = this.selectedIndex; // 0 = croissant, 1 = décroissant
            trierCartes(ordre);
        });
    }
    
    function trierCartes(ordre) {
        const grille = document.querySelector('.grille');
        if (!grille) return;
        
        // Récupérer toutes les cartes visibles (non masquées par les onglets)
        const cartes = Array.from(grille.querySelectorAll('.carte')).filter(carte => {
            return carte.style.display !== 'none';
        });
        
        // Sauvegarder les cartes masquées pour les réinsérer à la fin
        const cartesMasquees = Array.from(grille.querySelectorAll('.carte')).filter(carte => {
            return carte.style.display === 'none';
        });
        
        // Sauvegarder les autres éléments (no-results, etc.)
        const autresElements = Array.from(grille.children).filter(element => {
            return !element.classList.contains('carte');
        });
        
        // Trier les cartes visibles par prix
        cartes.sort((a, b) => {
            const prixA = parseFloat(a.getAttribute('data-prix')) || 0;
            const prixB = parseFloat(b.getAttribute('data-prix')) || 0;
            
            if (ordre === 0) {
                // Tri croissant
                return prixA - prixB;
            } else {
                // Tri décroissant
                return prixB - prixA;
            }
        });
        
        // Vider la grille
        grille.innerHTML = '';
        
        // Réinsérer les cartes triées
        cartes.forEach(carte => {
            grille.appendChild(carte);
        });
        
        // Réinsérer les cartes masquées
        cartesMasquees.forEach(carte => {
            grille.appendChild(carte);
        });
        
        // Réinsérer les autres éléments
        autresElements.forEach(element => {
            grille.appendChild(element);
        });
    }
});


// Gestion des images dans la page d'édition
document.addEventListener('DOMContentLoaded', function() {
    // Vérifier si nous sommes sur la page d'édition
    const deleteImageButtons = document.querySelectorAll('.btn-delete-image');
    const imagesToDeleteField = document.getElementById('images_to_delete');
   
    let imagesToDelete = []; // Tableau pour stocker les IDs des images à supprimer
    
    // Supprimer une image spécifique
    deleteImageButtons.forEach(button => {
        button.addEventListener('click', function() {
            if (confirm('Êtes-vous sûr de vouloir supprimer cette image ?')) {
                const imageId = this.getAttribute('data-image-id');
                const imageContainer = this.closest('.image-item');
                
                // Ajouter l'ID à la liste des images à supprimer
                imagesToDelete.push(imageId);
                imagesToDeleteField.value = imagesToDelete.join(',');
                
                // Masquer visuellement l'image
                imageContainer.style.opacity = '0.5';
                imageContainer.style.pointerEvents = 'none';
                
                // Ajouter un badge "Image supprimée"
                const badge = document.createElement('div');
                badge.innerHTML = 'Image supprimée';
                badge.style.cssText = 'position: absolute; top: 0; left: 0; background: red; color: white; padding: 2px 5px; font-size: 10px;';
                imageContainer.appendChild(badge);
                
                // Masquer le bouton de suppression
                this.style.display = 'none';
                
                // Mettre à jour le compteur
                updateImageCounter();
            }
        });
    });
    

    
    // Fonction pour mettre à jour le compteur d'images
    function updateImageCounter() {
        const currentImagesText = document.querySelector('p');
        if (currentImagesText && currentImagesText.textContent.includes('Images actuelles')) {
            const totalImages = deleteImageButtons.length;
            const imagesToDeleteCount = imagesToDelete.length;
            const remainingImages = totalImages - imagesToDeleteCount;
            
            currentImagesText.innerHTML = `Images actuelles (${remainingImages}/5) :`;
            
            // Mettre à jour le texte de l'input file
            const fileInput = document.querySelector('input[type="file"]');
            const label = fileInput.previousElementSibling;
            if (label) {
                const maxNew = 5 - remainingImages;
                label.textContent = `Ajouter des images (max ${maxNew} nouvelles)`;
            }
        }
    }
});