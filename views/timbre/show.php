<!-- Header -->
{{ include('layouts/header.php', {title: timbre.nom ~ ' - Stampee'})}}

<div class="container">
    <!-- Fil d'Ariane (breadcrumb) -->
    <nav class="fil-ariane">
        <ul>
            <li><a href="{{base}}">Accueil</a></li>
            <li><a href="{{base}}/profil">Profil</a></li>
            <li>{{ timbre.nom }}</li>
        </ul>
    </nav>

    <!-- Grille principale  -->
    <div class="grille_detail">

        <!-- Section principale : Détails du timbre -->
        <section class="carte_fiche">
            
            <!-- Galerie d'images avec zoom -->
            <div class="galerie-enchere">
                <div class="zoom">
                    <picture>
                        <img src="{{base}}/public/assets/img/timbres/{{ images[0].chemin_image }}" 
                             alt="{{ timbre.nom }}" id="imageZoom">
                    </picture>

                    <!-- Bouton plein écran (zoom) -->
                    <button class="zoom-btn" title="Plein écran">
                        <i class="fas fa-expand"></i>
                    </button>
                </div>

                <span class="show">Pointez sur l'image pour zoomer</span>

                <!-- Miniatures sous l'image principale -->
                <div class="galerie-min">
                    {% for image in images %}
                        <div class="galerie-miniature {{ loop.first ? 'active' : '' }}" 
                             onclick="changeImage('{{base}}/public/assets/img/timbres/{{ image.chemin_image }}')">
                            <img src="{{base}}/public/assets/img/timbres/{{ image.chemin_image }}" 
                                 alt="{{ timbre.nom }} - Image {{ loop.index }}">
                        </div>
                    {% endfor %}
                </div>
            </div>
            
            <!-- Nom du timbre -->
            <h2>{{ timbre.nom }}</h2>

            <!-- Détails du timbre (métadonnées) -->
            <div class="carte-detaills">
                <div class="grille-meta">

                    <!-- Pays d'origine -->
                    <div class="grille-meta-item">
                        <span class="label">Pays d'origine</span>
                        <span class="value">{{ timbre.nom_pays }}</span>
                    </div>

                    <!-- Date de création -->
                    <div class="grille-meta-item">
                        <span class="label">Date de création</span>
                        <span class="value">{{ timbre.date_creation }}</span>
                    </div>

                    <!-- Couleur -->
                    <div class="grille-meta-item">
                        <span class="label">Couleur</span>
                        <span class="value">{{ timbre.nom_couleur }}</span>
                    </div>

                    <!-- Condition  -->
                    <div class="grille-meta-item">
                        <span class="label">Condition</span>
                        <span class="value grille-condition">
                            <span class="condition-badge condition-{{ timbre.nom_condition|lower|replace({' ': '-'}) }}">
                                {{ timbre.nom_condition }}
                            </span>
                        </span>
                    </div>

                    <!-- Tirage -->
                    {% if timbre.tirage %}
                        <div class="grille-meta-item">
                            <span class="label">Tirage</span>
                            <span class="value">{{ timbre.tirage|number_format }} exemplaires</span>
                        </div>
                    {% endif %}

                    <!-- Dimensions -->
                    {% if timbre.dimensions %}
                        <div class="grille-meta-item">
                            <span class="label">Dimensions</span>
                            <span class="value">{{ timbre.dimensions }}</span>
                        </div>
                    {% endif %}

                    <!-- Certification -->
                    <div class="grille-meta-item">
                        <span class="label">Certifié</span>
                        <span class="value grille-condition">
                            {% if timbre.certifie == 1 %}
                                <span class="certifie">
                                    <i class="fas fa-certificate"></i>
                                    Certifié
                                </span>
                            {% else %}
                                <span class="non-certifie">Non certifié</span>
                            {% endif %}
                        </span>
                    </div>
                    
                </div>
                
                <!-- Description du timbre (si disponible) -->
                {% if timbre.description %}
                    <div class="grille-description">
                        <h3>Description détaillée</h3>
                        <p>{{ timbre.description }}</p>
                    </div>
                {% endif %}
            </div>
        </section>
        
        <!-- Sidebar : actions liées au timbre -->
        <aside class="fiche-sidebar">
            <div class="carte_fiche">
                <div class="carte-actions">

                    <!-- Groupe d'actions : édition / suppression -->
                    <div class="action-group">
                        <h4 class="group-title">Gestion du timbre</h4>

                        <!-- Lien vers la page de modification -->
                        <a href="{{base}}/timbre/edit?id={{timbre.id_timbre}}" class="btn">
                            <span class="btn-text">
                                <i class="fas fa-edit"></i>
                                <strong>Modifier</strong>
                                <small>( Éditer les informations )</small>
                            </span>
                        </a>

                        <!-- Formulaire de suppression avec confirmation -->
                        <form action="{{base}}/timbre/delete" method="post" 
                              onsubmit="return confirm('Êtes-vous sûr de vouloir supprimer définitivement ce timbre ?')">
                            <input type="hidden" name="id" value="{{timbre.id_timbre}}">
                            <button type="submit" class="btn delete">
                                <span class="btn-text">
                                    <i class="fas fa-trash-alt"></i>
                                    <strong>Supprimer</strong>
                                    <small>( Action irréversible )</small>
                                </span>
                            </button>
                        </form>
                    </div>

                    <!-- Groupe d'actions : navigation utilisateur -->
                    <div class="action-group">
                        <h4 class="group-title">Navigation</h4>

                        <!-- Retour au profil -->
                        <a href="{{base}}/profil" class="btn btn-secondary voir">
                            <span class="btn-text">
                                <i class="fas fa-user-circle"></i>
                                <strong>Mon profil</strong>
                                <small>( Retour à ma collection )</small>
                            </span>
                        </a>

                        <!-- Créer un nouveau timbre -->
                        <a href="{{base}}/timbre/create" class="btn btn-success">
                            <span class="btn-text">
                                <i class="fas fa-plus-circle"></i>
                                <strong>Nouveau timbre</strong>
                                <small>( Ajouter à ma collection )</small>
                            </span>
                        </a>
                    </div>
                </div>
            </div>
        </aside>
        
    </div>
</div>

<!-- Footer -->
{{ include('layouts/footer.php') }}
