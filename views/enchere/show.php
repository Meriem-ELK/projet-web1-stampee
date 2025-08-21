<!-- Header -->
{{ include('layouts/header.php', {title: enchere.nom_timbre ~ ' - Stampee'})}}

<div class="container">
    <!-- Fil d'Ariane (breadcrumb) -->
    <nav class="fil-ariane">
        <ul>
            <li><a href="{{base}}">Accueil</a></li>
            <li><a href="{{base}}/enchere">Enchères</a></li>
            <li><a href="{{base}}/enchere">Enchères Actives</a></li>
            <li>{{ enchere.nom_timbre }}</li>
        </ul>
    </nav>

    <!-- Grille principale -->
    <div class="grille_detail">

        <!-- Section principale : Détails de l'enchère -->
        <section class="carte_fiche">
            
            <!-- Galerie d'images avec zoom -->
            <div class="galerie-enchere">
                <div class="zoom">
                    <picture>
                        <!-- Image principale affichée -->
                        <img id="imageZoom"
                            src="{{base}}/public/assets/img/timbres/{{ images[0].chemin_image }}"
                            alt="{{ enchere.nom_timbre }}"
                            data-fancybox="galerie"
                            data-caption="{{ enchere.nom_timbre }}">
                    </picture>

                    <!-- Bouton plein écran (zoom) -->
                    <button class="zoom-btn" id="fullscreenBtn" title="Plein écran">
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
                                 alt="{{ enchere.nom_timbre }} - Image {{ loop.index }}">
                        </div>
                    {% endfor %}
                </div>
            </div>
            
            <!-- Nom du timbre -->
            <h2>{{ enchere.nom_timbre }}</h2>

            <!-- Détails du timbre -->
            <div class="carte-detaills">
                <div class="grille-meta">

                    <!-- Pays d'origine -->
                    <div class="grille-meta-item">
                        <span class="label">Pays d'origine</span>
                        <span class="value">{{ enchere.nom_pays }}</span>
                    </div>

                    <!-- Date de création -->
                    <div class="grille-meta-item">
                        <span class="label">Date de création</span>
                        <span class="value">{{ enchere.date_creation }}</span>
                    </div>

                    <!-- Couleur -->
                    <div class="grille-meta-item">
                        <span class="label">Couleur</span>
                        <span class="value">{{ enchere.nom_couleur }}</span>
                    </div>

                    <!-- Condition -->
                    <div class="grille-meta-item">
                        <span class="label">Condition</span>
                        <span class="value grille-condition">
                            <span class="condition-badge condition-{{ enchere.nom_condition|lower|replace({' ': '-'}) }}">
                                {{ enchere.nom_condition }}
                            </span>
                        </span>
                    </div>

                    <!-- Tirage -->
                    {% if enchere.tirage %}
                        <div class="grille-meta-item">
                            <span class="label">Tirage</span>
                            <span class="value">{{ enchere.tirage|number_format }} exemplaires</span>
                        </div>
                    {% endif %}

                    <!-- Dimensions -->
                    {% if enchere.dimensions %}
                        <div class="grille-meta-item">
                            <span class="label">Dimensions</span>
                            <span class="value">{{ enchere.dimensions }}</span>
                        </div>
                    {% endif %}

                    <!-- Certification -->
                    <div class="grille-meta-item">
                        <span class="label">Certifié</span>
                        <span class="value grille-condition">
                            {% if enchere.certifie == 1 %}
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
                
                <!-- Description du timbre -->
                {% if enchere.description %}
                    <div class="grille-description">
                        <h3>Description détaillée</h3>
                        <p>{{ enchere.description }}</p>
                    </div>
                {% endif %}
            </div>
        </section>
        
        <!-- Sidebar : informations de l'enchère -->
        <aside class="fiche-sidebar">

            <!-- Carte principale de l'enchère -->
            <div class="carte_fiche">
                <!-- Coup de coeur du Lord (si applicable) -->
                {% if enchere.coup_coeur_lord == 1 %}
                            <div class="carte-lordFavorite">
                                <i class="fas fa-crown"></i>
                                Coup de cœur du Lord
                            </div>
                 {% endif %}

                <!-- Status de l'enchère -->
                <div class="carte_fiche-status">
                    {% if tempsRestant.fini %}
                        <div class="termine"></div>
                        <span class="texte">Enchère terminée</span>
                    {% else %}
                        <div class="live"></div>
                        <span class="texte">Enchère en cours</span>
                    {% endif %}
                </div>
              
                <!-- Prix actuel -->
                <div class="carte_fiche-prix">
                    <div class="label"> Prix de départ </div>
                    <div class="prixActuelle">
                        £{{ enchere.mise_actuelle ? enchere.mise_actuelle : enchere.prix_plancher }}
                    </div>
                </div>

                <!-- Temps restant -->
                {% if not tempsRestant.fini %}
                    <div class="carte_fiche-temps">
                        <div class="label">Temps restant</div>
                        <div class="temps">{{ tempsRestant.texte }}</div>
                    </div>
                {% endif %}

                {% if not guest %}
                    <div class="carte-actions">
                        <a href="{{base}}/favoris/switch?id_enchere={{ enchere.id_enchere }}" 
                        class="btn {{ estFavori ? 'btn-favori-active' : 'btn-favori' }}">
                            <i class="fas fa-heart"></i>
                            {{ estFavori ? 'Ne plus suivre cette enchère' : 'Suivre cette enchère' }}
                        </a>
                    </div>
                {% endif %}

            </div>

            <!-- Carte de connexion (si utilisateur non connecté) -->
            {% if guest %}
            <div class="carte_fiche">
                <h3>Participer à l'enchère</h3>
                <form class="carte-actions">
                    <button type="button" class="btn" onclick="location.href='{{base}}/login'">
                        <i class="fas fa-sign-in-alt"></i>
                        Se connecter
                    </button>
                    <button type="button" class="btn voir" onclick="location.href='{{base}}/user/create'">
                        <i class="fas fa-user-plus"></i>
                        Devenir membre
                    </button>
                </form>
                <div class="info">
                    <a href="{{base}}/">En savoir plus sur Stampee</a>
                </div>
            </div>
            {% endif %}

            <!-- Détails de l'enchère -->
            <div class="carte_fiche">
                <h3>Détails de l'enchère</h3>
                
                <div class="fiche-details">
                    <div class="details">
                        <div class="label">Prix de départ</div>
                        <div class="value">
                            <strong>£{{ enchere.prix_plancher }}</strong>
                        </div>
                    </div>
                </div>

                <div class="fiche-details">
                    <div class="details">
                        <div class="label">Date de début</div>
                        <div class="value">
                            <strong>{{ enchere.date_debut|date('d M Y') }}</strong>
                        </div>
                    </div>
                </div>

                <div class="fiche-details">
                    <div class="details">
                        <div class="label">Date de fin</div>
                        <div class="value">
                            <strong>{{ enchere.date_fin|date('d M Y H:i') }}</strong>
                        </div>
                    </div>
                </div>

            </div>

           

        </aside>
        
    </div>
</div>

<!-- Footer -->
{{ include('layouts/footer.php') }}