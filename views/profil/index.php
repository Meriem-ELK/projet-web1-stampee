<!-- Header -->
{{ include('layouts/header.php', {title: 'Profil - Stampee '})}}

<div class="container">
                <!-- Affichage des erreurs/succès -->
                {% if errors is defined and errors is not empty %}
                    <div id="notification-message">
                        {% if errors.success is defined %}
                            <div class="success">
                                {{ errors.success }}
                            </div>
                        {% else %}
                            <div class="error">
                                {% for field, message in errors %}
                                    <div class="error-item">
                                        {{ message }}
                                    </div>
                                {% endfor %}
                            </div>
                        {% endif %}
                    </div>
                {% endif %}


    <!-- Section : Informations personnelles de l'utilisateur -->
    <section class="form-control">
        <h2>Bienvenue <strong>{{ session.nom }} {{ session.prenom }}</strong></h2>

        <h3 class="taille_texte_200">Mes informations personnelles</h3>

        <div class="profil">
            <!-- Affichage des informations de l'utilisateur -->
            <div class="profil_informations"><span>Nom d'utilisateur :</span> {{ utilisateur.nom_utilisateur }}</div>
            <div class="profil_informations"><span>Nom :</span> {{ utilisateur.nom }}</div>
            <div class="profil_informations"><span>Prénom :</span> {{ utilisateur.prenom }}</div>
            <div class="profil_informations"><span>Adresse courriel :</span> {{ utilisateur.email }}</div>  
            <div class="profil_informations"><span>Mot de passe :</span> ********</div>

            <!-- Boutons pour modifier ou supprimer le profil -->
            <div class="form__GroupBtn">
                <a class="btn" href="{{base}}/profil/edit?id={{ utilisateur.id_utilisateur }}">Modifier</a>
                
                <a class="btn delete" 
                   href="{{base}}/profil/delete?id={{ utilisateur.id_utilisateur }}"
                   onclick="return confirm('Êtes-vous sûr de vouloir supprimer votre compte ? Cette action est irréversible et supprimera définitivement toutes vos données.');">
                   Supprimer
                </a>
            </div>
        </div>
    </section>

    <!-- Section : Mes offres -->
    <section class="form-control">
        <div class="profile-section" id="table_mesOffres">
            <h2 class="taille_texte_200">
                <i class="fas fa-gavel"></i> Mes offres
            </h2>

            <!-- En-tête de la section offres -->
            <div class="section-header">
                <!-- Affichage du nombre d'offres -->
                <span class="stat-badge">
                    <strong>{{ mesOffres|length }}</strong> 
                    offre{{ mesOffres|length > 1 ? 's' : '' }}
                </span>
                
                <!-- Enchères où l'utilisateur est en tête -->
                {% if encheresEnTete|length > 0 %}
                    <span class="stat-badge stat-winning">
                        <i class="fas fa-trophy"></i>
                        <strong>{{ encheresEnTete|length }}</strong> 
                        en tête
                    </span>
                {% endif %}
            </div>

            {% if mesOffres is defined and mesOffres|length > 0 %}
                <!-- Tableau des offres -->
                <div class="timbres-table-container">
                    <table class="timbres-table">
                        <thead>
                            <tr>
                                <th>Image</th>
                                <th>Enchère</th>
                                <th>Mon offre</th>
                                <th>Prix actuel</th>
                                <th>Statut</th>
                                <th>Temps restant</th>
                                <th>Actions</th>
                            </tr>
                        </thead>
                        <tbody>
                            {% for offre in mesOffres %}
                            <tr class="timbre-row mesOffres">
                                <!-- Affichage de l'image du timbre -->
                                <td class="timbre-thumb">
                                    {% if offre.premiere_image %}
                                        <img src="{{base}}/public/assets/img/timbres/{{ offre.premiere_image }}" 
                                             alt="{{ offre.nom_timbre }}" class="thumb-img">
                                    {% else %}
                                        <div class="thumb-placeholder">
                                            <i class="fas fa-image"></i>
                                        </div>
                                    {% endif %}
                                </td>

                                <!-- Nom du timbre -->
                                <td class="timbre-name">
                                    <strong>{{ offre.nom_timbre }}</strong>
                                    <br>
                                    <small class="country-tag">{{ offre.nom_pays }}</small>
                                </td>

                                <!-- Mon offre -->
                                <td class="price">
                                    <strong>£{{ offre.montant }}</strong>
                                    <br>
                                    <small>{{ offre.date_mise|date('d/m/Y H:i') }}</small>
                                </td>

                                <!-- Prix actuel -->
                                <td class="price">
                                    £ {{ offre.mise_actuelle }}
                                </td>

                                <!-- Statut de l'offre -->
                                <td>
                                    {% if offre.temps_restant.fini %}
                                        <span class="status-expired">Terminée</span>
                                    {% elseif offre.en_tete %}
                                        <span class="status-winning">
                                            <i class="fas fa-trophy"></i> En tête
                                        </span>
                                    {% else %}
                                        <span class="status-outbid">Dépassé</span>
                                    {% endif %}
                                </td>

                                <!-- Temps restant -->
                                <td>
                                    {% if offre.temps_restant.fini %}
                                        <span class="status-expired">Finie</span>
                                    {% else %}
                                        <span class="status-active">{{ offre.temps_restant.texte }}</span>
                                    {% endif %}
                                </td>

                                <!-- Actions -->
                                <td class="actions">
                                    <div class="action-buttons">
                                        <a href="{{base}}/enchere/show?id={{offre.id_enchere}}"
                                           class="action-btn view" title="Voir l'enchère">
                                            <i class="fas fa-eye"></i>
                                        </a>
                                    </div>
                                </td>
                            </tr>
                            {% endfor %}
                        </tbody>
                    </table>
                </div>

                <!-- Navigation de pagination -->
                <div class="pagination-nav">
                        <button id="btn-previous" class="pagination-btn">
                            <i class="fas fa-chevron-left"></i> Précédent
                        </button>
                        
                        <div class="pagination-nbrs" id="pagination-nbrs">
                            <!-- Les numéros de pages seront générés par JavaScript -->
                        </div>
                        
                        <button id="btn-next" class="pagination-btn">
                            Suivant <i class="fas fa-chevron-right"></i>
                        </button>
                </div>



            {% else %}
                <!-- Message si l'utilisateur n'a pas encore fait d'offre -->
                <div class="empty-collection">
                    <div class="empty-icon">
                        <i class="fas fa-gavel"></i>
                    </div>
                    <h4>Vous n'avez pas encore fait d'offre</h4>
                    <p>Explorez les enchères et faites votre première offre sur un timbre qui vous intéresse.</p>
                    <a href="{{base}}/enchere" class="btn btn-content">
                        <i class="fas fa-search"></i> Parcourir les enchères
                    </a>
                </div>
            {% endif %}
        </div>
    </section>

    <!-- Section : Collection de timbres -->
    <section class="form-control">
        <div class="profile-section" id="table_timbres">
            <h2 class="taille_texte_200">
                <i class="fas fa-stamp"></i> Ma collection de timbres
            </h2>

            <!-- En-tête de la section timbres -->
            <div class="section-header">
                <a href="{{base}}/timbre/create" class="btn btn-content">
                    <i class="fas fa-plus"></i> Ajouter un timbre
                </a> 

                <!-- Affichage du nombre de timbres -->
                <span class="stat-badge">
                    <strong>{{ mesTimbres|length }}</strong> 
                    timbre{{ mesTimbres|length > 1 ? 's' : '' }}
                </span>
            </div>

            {% if mesTimbres is defined and mesTimbres|length > 0 %}
                <!-- Tableau de timbres -->
                <div class="timbres-table-container">
                    <table class="timbres-table">
                        <thead>
                            <tr>
                                <th>Image</th>
                                <th>Nom du timbre</th>
                                <th>Pays</th>
                                <th>Année</th>
                                <th>Condition</th>
                                <th>Statut</th>
                                <th>Actions</th>
                            </tr>
                        </thead>
                        <tbody>
                            {% for timbre in mesTimbres %}
                            <tr class="timbre-row">
                                <!-- Affichage de l'image du timbre -->
                                <td class="timbre-thumb">
                                    {% if timbre.premiere_image %}
                                        <img src="{{base}}/public/assets/img/timbres/{{ timbre.premiere_image }}" 
                                             alt="{{ timbre.nom }}" class="thumb-img">
                                    {% else %}
                                        <div class="thumb-placeholder">
                                            <i class="fas fa-image"></i>
                                        </div>
                                    {% endif %}
                                </td>

                                <!-- Nom -->
                                <td class="timbre-name">
                                    <strong>{{ timbre.nom }}</strong>
                                </td>

                                <!-- Pays -->
                                <td>
                                    <span class="country-tag">{{ timbre.nom_pays }}</span>
                                </td>

                                <!-- Année de création -->
                                <td class="year">{{ timbre.date_creation }}</td>

                                <!-- Condition du timbre -->
                                <td>
                                    <span class="condition-badge condition-{{ timbre.nom_condition|lower|replace({' ': '-'}) }}">
                                        {{ timbre.nom_condition }}
                                    </span>
                                </td>

                                <!-- Statut (certifié ou non) -->
                                <td>
                                    {% if timbre.certifie == 1 %}
                                        <span class="status-certified">
                                            <i class="fas fa-certificate"></i> Certifié
                                        </span>
                                    {% else %}
                                        <span class="status-standard">Non Certifié</span>
                                    {% endif %}
                                </td>

                                <!-- Actions : Voir / Modifier / Supprimer -->
                                <td class="actions">
                                    <div class="action-buttons">
                                        <a href="{{base}}/timbre/show?id={{timbre.id_timbre}}"
                                           class="action-btn view" title="Voir les détails">
                                            <i class="fas fa-eye"></i>
                                        </a>
                                        <a href="{{base}}/timbre/edit?id={{timbre.id_timbre}}" 
                                           class="action-btn edit" title="Modifier">
                                            <i class="fas fa-edit"></i>
                                        </a>
                                        <form action="{{base}}/timbre/delete" method="post" style="display: inline;" 
                                              onsubmit="return confirm('Êtes-vous sûr de vouloir supprimer ce timbre ?')">
                                            <input type="hidden" name="id" value="{{timbre.id_timbre}}">
                                            <button type="submit" class="action-btn delete">
                                                <i class="fas fa-trash"></i>
                                            </button>
                                        </form>
                                    </div>
                                </td>
                            </tr>
                            {% endfor %}
                        </tbody>
                    </table>

                    <!-- Navigation de pagination pour les timbres -->
                    <div class="pagination-nav">
                        <button id="btn-previous-timbres" class="pagination-btn">
                            <i class="fas fa-chevron-left"></i> Précédent
                        </button>
                        
                        <div class="pagination-nbrs" id="pagination-nbrs-timbres">
                            <!-- Les numéros de pages seront générés par JavaScript -->
                        </div>
                        
                        <button id="btn-next-timbres" class="pagination-btn">
                            Suivant <i class="fas fa-chevron-right"></i>
                        </button>
                    </div>

                </div>
            {% else %}
                <!-- Message si l'utilisateur n'a pas encore de timbre -->
                <div class="empty-collection">
                    <div class="empty-icon">
                        <i class="fas fa-stamp"></i>
                    </div>
                    <h4>Votre collection est vide</h4>
                    <p>Commencez dès maintenant à constituer votre collection de timbres.</p>
                    <a href="{{base}}/timbre/create" class="btn btn-content">
                        <i class="fas fa-plus"></i> Ajouter mon premier timbre
                    </a>
                </div>
            {% endif %}
        </div>
    </section>



    <!-- Section : Enchères favorites -->
    <section class="form-control">
        <div class="profile-section" id="table_favoris">
            <h2 class="taille_texte_200">
                <i class="fas fa-heart"></i> Mes enchères favorites
            </h2>

            <!-- En-tête de la section favoris -->
            <div class="section-header">
                <!-- Affichage du nombre de favoris -->
                <span class="stat-badge">
                    <strong>{{ mesFavoris|length }}</strong> 
                    favori{{ mesFavoris|length > 1 ? 's' : '' }}
                </span>
            </div>

            {% if mesFavoris is defined and mesFavoris|length > 0 %}
                <!-- Tableau de favoris -->
                <div class="timbres-table-container">
                    <table class="timbres-table" >
                        <thead>
                            <tr>
                                <th>Image</th>
                                <th>Nom du timbre</th>
                                <th>Pays</th>
                                <th>Prix actuel</th>
                                <th>Temps restant</th>
                                <th>Actions</th>
                            </tr>
                        </thead>
                        <tbody>
                            {% for favori in mesFavoris %}
                            <tr class="timbre-row">
                                <!-- Affichage de l'image du timbre -->
                                <td class="timbre-thumb">
                                    {% if favori.premiere_image %}
                                        <img src="{{base}}/public/assets/img/timbres/{{ favori.premiere_image }}" 
                                            alt="{{ favori.nom_timbre }}" class="thumb-img">
                                    {% else %}
                                        <div class="thumb-placeholder">
                                            <i class="fas fa-image"></i>
                                        </div>
                                    {% endif %}
                                </td>

                                <!-- Nom -->
                                <td class="timbre-name">
                                    <strong>{{ favori.nom_timbre }}</strong>
                                </td>

                                <!-- Pays -->
                                <td>
                                    <span class="country-tag">{{ favori.nom_pays }}</span>
                                </td>

                                <!-- Prix actuel -->
                                <td class="price">
                                    £{{ favori.mise_actuelle ? favori.mise_actuelle : favori.prix_plancher }}
                                </td>

                                <!-- Temps restant -->
                                <td>
                                    {% if favori.temps_restant.fini %}
                                        <span class="status-expired">Terminée</span>
                                    {% else %}
                                        <span class="status-active">{{ favori.temps_restant.texte }}</span>
                                    {% endif %}
                                </td>

                                <!-- Actions : Voir / Retirer des favoris -->
                                <td class="actions">
                                    <div class="action-buttons">
                                        <a href="{{base}}/enchere/show?id={{favori.id_enchere}}"
                                        class="action-btn view" title="Suivre cette enchère">
                                            <i class="fas fa-eye"></i>
                                        </a>
                                        <a href="{{base}}/favoris/switch?id_enchere={{favori.id_enchere}}" 
                                        class="action-btn delete" title="Ne plus suivre cette enchère">
                                            <i class="fas fa-heart-broken"></i>
                                        </a>
                                    </div>
                                </td>
                            </tr>
                            {% endfor %}
                        </tbody>
                    </table>
                </div>

                <!-- Navigation de pagination pour les favoris -->
                <div class="pagination-nav">
                    <button id="btn-previous-favoris" class="pagination-btn">
                        <i class="fas fa-chevron-left"></i> Précédent
                    </button>
                    
                    <div class="pagination-nbrs" id="pagination-nbrs-favoris">
                        <!-- Les numéros de pages seront générés par JavaScript -->
                    </div>
                    
                    <button id="btn-next-favoris" class="pagination-btn">
                        Suivant <i class="fas fa-chevron-right"></i>
                    </button>
                </div>


            {% else %}
                <!-- Message si l'utilisateur n'a pas encore de favoris -->
                <div class="empty-collection">
                    <div class="empty-icon">
                        <i class="fas fa-heart"></i>
                    </div>
                    <h4>Vous n'avez pas encore d'enchères favorites</h4>
                    <p>Explorez les enchères et ajoutez celles qui vous intéressent à vos favoris.</p>
                    <a href="{{base}}/enchere" class="btn btn-content">
                        <i class="fas fa-search"></i> Parcourir les enchères
                    </a>
                </div>
            {% endif %}
        </div>
    </section>

</div>

<!-- Footer -->
{{ include('layouts/footer.php') }}
