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
                    <p>Images actuelles ({{ images|length }}) :</p>
                    <div class="current-images" style="margin-bottom: 20px;">
                        {% for image in images %}
                            <img src="{{base}}/public/assets/img/timbres/{{ image.chemin_image }}" 
                                 style="max-height:80px; margin-right:10px; border:1px solid #ddd;" 
                                 alt="Image du timbre">
                        {% endfor %}
                    </div>
                {% else %}
                    <p>Aucune image actuellement.</p>
                {% endif %}

                <p><strong>Gestion des images :</strong></p>

                <div style="margin-bottom: 15px;">
                    <label style="display: block; margin-bottom: 10px;">
                        <input type="radio" name="image_action" value="keep" checked style="margin-right: 8px;">
                        Garder les images existantes uniquement
                    </label>

                    <label style="display: block; margin-bottom: 10px;">
                        <input type="radio" name="image_action" value="add" style="margin-right: 8px;">
                        Ajouter de nouvelles images (max 5 au total)
                    </label>

                    <label style="display: block; margin-bottom: 10px;">
                        <input type="radio" name="image_action" value="replace" style="margin-right: 8px;">
                        Remplacer toutes les images existantes (max 5 nouvelles)
                    </label>
                </div>

                <div id="file-input-container" style="display: none;">
                    <input type="file" name="images[]" multiple accept="image/*" class="form-input">
                    <small>Formats acceptés: JPG, PNG, GIF, WEBP (max 5MB par fichier)</small>
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