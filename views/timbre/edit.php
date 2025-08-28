{{ include('layouts/header.php', {title: 'Modifier un timbre'})}}

<section class="form-control">
    <h2>Modifier le timbre : {{ timbre.nom }}</h2>
    
    <!-- Affichage des erreurs -->
    {% if errors is defined %}
    <div class="error">
        <ul>
            {% for error in errors %}
                <li>{{ error }}</li>
            {% endfor %}
        </ul>
    </div>
    {% endif %}

    <!-- Formulaire de modification avec enctype pour les fichiers -->
    <form method="post" enctype="multipart/form-data" class="form_timbre">
        
        <!-- Informations de base -->
        <div class="form-section">
            <h3 class="taille_texte_200">Informations générales</h3>
            
            <div class="form-group">
                <label class="form-label">Nom du timbre *</label>
                <input type="text" name="nom" class="form-input" 
                       value="{{ timbre.nom }}" 
                       placeholder="Ex: New Zealand Health">
            </div>

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

            <div class="form-group">
                <label class="form-label">Année de création *</label>
                <input type="number" name="date_creation" class="form-input" 
                       value="{{ timbre.date_creation }}" 
                       min="1000"
                       max="{{ "now"|date("Y") }}"
                       placeholder="Ex: 2024">
            </div>
            
        </div>

        <!-- Caractéristiques techniques -->
        <div class="form-section">
            <h3 class="taille_texte_200">Caractéristiques techniques</h3>
            
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

            <div class="form-group">
                <label class="form-label">Tirage</label>
                <input type="number" name="tirage" class="form-input" 
                       value="{{ timbre.tirage }}" 
                       placeholder="Ex: 1000000">
                <small>Nombre d'exemplaires produits (optionnel)</small>
            </div>

            <div class="form-group">
                <label class="form-label">Dimensions</label>
                <input type="text" name="dimensions" class="form-input" 
                       value="{{ timbre.dimensions }}" 
                       placeholder="Ex: 25mm x 30mm">
                <small>Format du timbre (optionnel)</small>
            </div>

            <div class="form-group">
                <label class="form-label">Certifié *</label>
                <select name="certifie" class="form-input">
                    <option value="">Sélectionner</option>
                    <option value="1" {{ timbre.certifie == '1' ? 'selected' : '' }}>Oui</option>
                    <option value="0" {{ timbre.certifie == '0' ? 'selected' : '' }}>Non</option>
                </select>
                <small>Le timbre est-il authentifié par un expert ?</small>
            </div>
            
        </div>

        <!-- Description -->
        <div class="form-section">
            <h3 class="taille_texte_200">Description</h3>
            
            <div class="form-group">
                <label class="form-label">Description du timbre</label>
                <textarea name="description" class="form-input" rows="4" 
                          placeholder="Décrivez votre timbre, son histoire, ses particularités...">{{ timbre.description }}</textarea>
                <small>Donnez des détails qui intéresseront les collectionneurs (optionnel)</small>
            </div>
        </div>

        <!-- Section Images -->
        <div class="form-section">
            <h3 class="taille_texte_200">Images du timbre</h3>
            
            <div class="form-group">
                {% if images is defined and images|length > 0 %}
                    <p>Images actuelles ({{ images|length }}/5) :</p>
                    <div class="current-images">
                        {% for image in images %}
                            <div class="image-item">
                                <img src="{{base}}/public/assets/img/timbres/{{ image.chemin_image }}" alt="Image du timbre">
                                <!-- Bouton pour supprimer une image spécifique -->
                                <button type="button" 
                                        class="btn-delete-image" 
                                        data-image-id="{{ image.id_image }}">×</button>
                            </div>
                        {% endfor %}
                    </div>

                    <!-- Champ caché pour stocker les IDs des images à supprimer -->
                    <input type="hidden" name="images_to_delete" id="images_to_delete" value="">

                      <!-- Bouton pour supprimer toutes les images -->
            <!-- <button type="button" id="btn-delete-all-images" class="btn" 
                    style="background: #dc3545; color: white; margin-bottom: 15px;">
                <i class="fas fa-trash"></i> Supprimer toutes les images
            </button> -->

            
                {% endif %}

        <!-- Zone pour ajouter de nouvelles images -->
        <div class="add-images-section" style="border-top: 1px solid #ddd; padding-top: 15px;">
            <label class="form-label">Ajouter des images (max 5 au total)</label>
            <input type="file" name="images[]" multiple accept="image/*" class="form-input" onchange="previewImages(this)">
            <small>Formats acceptés: JPG, PNG, GIF, WEBP (max 5MB par fichier)</small>
            
            <!-- Zone de prévisualisation -->
            <div id="imagePreview" style="margin-top: 10px;"></div>
        </div>
    </div>
        </div>

        <!-- Boutons d'action -->
        <div class="form__GroupBtn">
            <button type="submit" class="btn">
                <i class="fas fa-save"></i> Enregistrer les modifications
            </button>
            
            <a href="{{base}}/timbre/show?id={{timbre.id_timbre}}" class="btn annuler">
                <i class="fas fa-times"></i> Annuler
            </a>
        </div>
    </form>
</section>

<!-- Footer -->
{{ include('layouts/footer.php')}}