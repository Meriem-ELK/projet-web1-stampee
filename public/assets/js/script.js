 window.addEventListener('scroll', function() {
    const headerMain = document.querySelector('.header-main');
    const headerTop = document.querySelector('.header-top');
    const body = document.body;
    
    const headerTopHeight = headerTop.offsetHeight;
    
    if (window.scrollY > headerTopHeight) {
        headerMain.classList.add('sticky-header');
        body.classList.add('header-sticky-active');
    } else {
        headerMain.classList.remove('sticky-header');
        body.classList.remove('header-sticky-active');
    }
});

function previewImages(input) {
    var previewContainer = document.getElementById('imagePreview');
    previewContainer.innerHTML = '';
    
    if (input.files.length > 0) {
        // Afficher le nombre de fichiers
        var summary = document.createElement('div');
        summary.innerHTML = '<p><strong>' + input.files.length + ' image(s) sélectionnée(s)</strong></p>';
        summary.style.background = '#e8f5e8';
        summary.style.padding = '8px';
        summary.style.marginBottom = '10px';
        previewContainer.appendChild(summary);
        
        // Afficher chaque image (max 5)
        for (var i = 0; i < input.files.length && i < 5; i++) {
            var file = input.files[i];
            
            if (file.type.indexOf('image/') === 0) {
                // Créer une fonction qui capture le fichier actuel
                (function(currentFile, index) {
                    var reader = new FileReader();
                    
                    reader.onload = function(e) {
                        var imageDiv = document.createElement('div');
                        imageDiv.style.display = 'inline-block';
                        imageDiv.style.margin = '5px';
                        imageDiv.style.border = '1px solid #ddd';
                        imageDiv.style.padding = '5px';
                        imageDiv.style.width = '120px';
                        imageDiv.style.textAlign = 'center';
                        
                        imageDiv.innerHTML = 
                            '<img src="' + e.target.result + '" style="width:100%; height:80px; object-fit:cover;">' +
                            '<p style="font-size:11px; margin:5px 0;">Image ' + (index + 1) + ': ' + currentFile.name + '</p>' +
                            '<small style="font-size:9px; color:#666;">' + Math.round(currentFile.size / 1024) + ' KB</small>';
                        
                        previewContainer.appendChild(imageDiv);
                    };
                    
                    reader.readAsDataURL(currentFile);
                })(file, i); // Passer le fichier et l'index à la fonction
            }
        }
    }
}
