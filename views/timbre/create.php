<!-- Header  -->
{{ include('layouts/header.php', {title: 'Ajouter un timbre'})}}

<!-- Section principale du formulaire d'ajout -->
<section class="form-control">

    <h2>Ajouter un nouveau timbre</h2>
    
    <!-- Bloc d'affichage des erreurs (si présentes) -->
    {% if errors is defined %}
        <div class="error">
            <ul>
                {% for error in errors %}
                    <li>{{ error }}</li>
                {% endfor %}
            </ul>
        </div>
    {% endif %}

    <!-- Informations importantes pour l'utilisateur -->
    <div class="benefits">
        <h4><i class="fas fa-info-circle"></i> Informations importantes :</h4>
        <ul>
            <li>Tous les champs marqués d'un * sont obligatoires</li>
            <li>Au moins une image est requise pour créer un timbre</li>
            <li>Remplissez toutes les informations du timbre</li>
            <li>Une fois créé, vous pourrez modifier ces informations</li>
        </ul>
    </div>

    <!-- Formulaire d'ajout d'un timbre -->
    <form action="{{base}}/timbre/store" method="post" class="form_timbre" enctype="multipart/form-data">
        
        <!-- Bloc : Informations générales -->
        <div class="form-section">
            <h3 class="taille_texte_200">Informations générales</h3>
            
            <!-- Champ : Nom du timbre -->
            <div class="form-group">
                <label class="form-label">Nom du timbre *</label>
                <input type="text" name="nom" class="form-input" 
                       value="{{ timbre.nom }}" 
                       placeholder="Ex: New Zealand Health">
            </div>

            <!-- Champ : Pays d'origine -->
            <div class="form-group">
                <label class="form-label">Pays d'origine *</label>
                <select name="id_pays_origine" class="form-input">
                    <option value="">Sélectionner le pays d'origine</option>
                    {% if pays is defined %}
                        {% for p in pays %}
                            <option value="{{ p.id_pays }}" {{ timbre.id_pays_origine == p.id_pays ? 'selected' : '' }}>
                                {{ p.nom_pays }}
                            </option>
                        {% endfor %}
                    {% endif %}
                </select>
            </div>

            <!-- Champ : Année de création -->
            <div class="form-group">
                <label class="form-label">Année de création *</label>
                <input type="number" name="date_creation" class="form-input" 
                       min="1000" max="9999" value="{{ timbre.date_creation }}" 
                       placeholder="Ex: 2024">
            </div>
        </div>

        <!-- Bloc : Caractéristiques techniques -->
        <div class="form-section">
            <h3 class="taille_texte_200">Caractéristiques techniques</h3>

            <!-- Champ : Couleur -->
            <div class="form-group">
                <label class="form-label">Couleur *</label>
                <select name="id_couleur" class="form-input">
                    <option value="">Sélectionner la couleur</option>
                    {% if couleurs is defined %}
                        {% for couleur in couleurs %}
                            <option value="{{ couleur.id_couleur }}" {{ timbre.id_couleur == couleur.id_couleur ? 'selected' : '' }}>
                                {{ couleur.nom_couleur }}
                            </option>
                        {% endfor %}
                    {% endif %}
                </select>
            </div>

            <!-- Champ : Condition -->
            <div class="form-group">
                <label class="form-label">Condition *</label>
                <select name="id_condition" class="form-input">
                    <option value="">Sélectionner la condition</option>
                    {% if conditions is defined %}
                        {% for condition in conditions %}
                            <option value="{{ condition.id_condition }}" {{ timbre.id_condition == condition.id_condition ? 'selected' : '' }}>
                                {{ condition.nom_condition }}
                            </option>
                        {% endfor %}
                    {% endif %}
                </select>
            </div>

            <!-- Champ : Tirage -->
            <div class="form-group">
                <label class="form-label">Tirage</label>
                <input type="number" name="tirage" class="form-input" 
                       value="{{ timbre.tirage }}" 
                       placeholder="Ex: 1000000">
                <small>Nombre d'exemplaires produits (optionnel)</small>
            </div>

            <!-- Champ : Dimensions -->
            <div class="form-group">
                <label class="form-label">Dimensions</label>
                <input type="text" name="dimensions" class="form-input" 
                       value="{{ timbre.dimensions }}" 
                       placeholder="Ex: 25mm x 30mm">
                <small>Format du timbre (optionnel)</small>
            </div>

            <!-- Champ : Certifié -->
            <div class="form-group">
                <label class="form-label">Certifié *</label>
                <div class="form-radio-group">
                    <label>
                        <input type="radio" name="certifie" value="1" {{ (timbre.certifie is not defined or timbre.certifie == '1') ? 'checked' : '' }}>
                        Oui
                    </label>
                    <label>
                        <input type="radio" name="certifie" value="0" {{ timbre.certifie == '0' ? 'checked' : '' }}>
                        Non
                    </label>
                </div>
            </div>
        </div>

        <!-- Bloc : Description du timbre -->
        <div class="form-section">
            <h3 class="taille_texte_200">Description</h3>
            <div class="form-group">
                <label class="form-label">Description du timbre</label>
                <textarea name="description" class="form-input" rows="4" 
                          placeholder="Décrivez votre timbre, son histoire, ses particularités...">{{ timbre.description }}</textarea>
            </div>
        </div>

        <!-- Bloc : Téléversement des images -->
        <div class="form-section">
            <h3 class="taille_texte_200">Images du timbre *</h3>
            <div class="form-group">
                <div class="upload-section" id="uploadSection">
                    
                    <!-- Bouton de sélection d'images -->
                    <div class="upload-text">
                        <label for="images" class="upload-button">
                            <i class="fas fa-cloud-upload-alt"></i> Choisir les images
                        </label>
                        <input type="file" name="images[]" id="images" class="upload-input" 
                               multiple accept="image/jpeg,image/png,image/gif,image/webp" 
                               onchange="previewImages(this)" required>
                    </div>

                    <!-- Infos sur les exigences des fichiers -->
                    <div class="upload-info">
                        <small>
                            <i class="fas fa-info-circle"></i> 
                            <strong>OBLIGATOIRE :</strong> 
                            <ul>
                                <li>Formats acceptés: JPG, PNG, GIF, WEBP</li>
                                <li>Minimum 1 image • Maximum 5 images</li>
                                <li>Taille max: 5MB par image</li>
                            </ul>
                        </small>
                    </div>
                </div>

                <!-- Prévisualisation des images -->
                <div class="image-preview" id="imagePreview"></div>
            </div>
        </div>

        <!-- Boutons d'action : Ajouter ou Annuler -->
        <div class="form__GroupBtn">
            <button type="submit" class="btn">
                <i class="fas fa-plus"></i> Ajouter le timbre
            </button>
            <a href="{{base}}/profil" class="btn annuler">
                <i class="fas fa-times"></i> Annuler
            </a>
        </div>
    </form>
</section>

<!-- Inclusion du footer -->
{{ include('layouts/footer.php') }}
