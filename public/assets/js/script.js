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


// window.addEventListener('DOMContentLoaded', function () {
//         const notification = document.getElementById('notification-message');
//         if (notification) {
//             window.scrollTo({
//                 top: notification.offsetTop - 20,
//                 behavior: 'smooth'
//             });
//         }
//     });

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

// Affichage/masquage du champ fichier
document.addEventListener('DOMContentLoaded', function() {
    const radioButtons = document.querySelectorAll('input[name="image_action"]');
    const fileInputContainer = document.getElementById('file-input-container');
    const fileInput = document.querySelector('input[name="images[]"]');
    
    function toggleFileInput() {
        const checkedRadio = document.querySelector('input[name="image_action"]:checked');
        
        // Vérifier si un bouton radio est coché
        if (checkedRadio) {
            const selectedValue = checkedRadio.value;
            
            if (selectedValue === 'add' || selectedValue === 'replace') {
                fileInputContainer.style.display = 'block';
                fileInput.required = true;
            } else {
                fileInputContainer.style.display = 'none';
                fileInput.required = false;
                fileInput.value = ''; // Vider la sélection
            }
        } else {
            // Si aucun bouton radio n'est coché, masquer par défaut
            fileInputContainer.style.display = 'none';
            fileInput.required = false;
            fileInput.value = '';
        }
    }
    
    radioButtons.forEach(radio => {
        radio.addEventListener('change', toggleFileInput);
    });
    
    // Initialisation seulement si les éléments existent
    if (radioButtons.length > 0 && fileInputContainer && fileInput) {
        toggleFileInput();
    }
});

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