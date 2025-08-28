<!-- Header -->
{{ include('layouts/header.php', {title: 'Catalogue des Enchères - Stampee'})}}
 
<div class="container">

    <!-- En-tête de page -->
    <header class="contenu-principal-header">
        <h2 class="contenu-principal-titre">Portail des Enchères</h2>
        <p class="contenu-principal-sutitre">Découvrez les trésors philatéliques sélectionnés par Lord Reginald Stampee III</p>
    </header>

    <!-- Formulaire de recherche et filtres -->
    <div class="filtres-container">
        <form method="get" action="{{base}}/enchere" class="filtres-form">
                <!-- Recherche par terme -->
                <section class="filtre-actions">
                        <h2 class="hide">Filtres de recherche rapide</h2>

                        <!-- Filtre année -->
                        <div class="filtre-groupe">
                            <label class="filtre-label" for="annee">Année de publication :</label>
                            <select id="annee" name="annee" class="filtre-select">
                                <option value="">Toutes les années</option>
                                {% for a in optionsFiltres.annees %}
                                    <option value="{{ a.annee }}" {% if a.annee == annee %}selected{% endif %}>
                                        {{ a.annee }}
                                    </option>
                                {% endfor %}
                            </select>
                        </div>

                        <!-- Filtre pays -->
                        <div class="filtre-groupe">
                            <label class="filtre-label" for="pays">Pays d'origine :</label>
                            <select id="pays" name="pays" class="filtre-select">
                                <option value="">Tous les pays</option>
                                {% for p in optionsFiltres.pays %}
                                    <option value="{{ p.id_pays }}" {% if p.id_pays == pays %}selected{% endif %}>
                                        {{ p.nom_pays }}
                                    </option>
                                {% endfor %}
                            </select>
                        </div>

                        <!-- Filtre condition -->
                        <div class="filtre-groupe">
                            <label class="filtre-label" for="condition">Condition :</label>
                            <select id="condition" name="condition" class="filtre-select">
                                <option value="">Toutes conditions</option>
                                {% for c in optionsFiltres.conditions %}
                                    <option value="{{ c.id_condition }}" {% if c.id_condition == condition %}selected{% endif %}>
                                        {{ c.nom_condition }}
                                    </option>
                                {% endfor %}
                            </select>
                        </div>

                        <!-- Filtre couleur -->
                        <div class="filtre-groupe">
                            <label class="filtre-label" for="couleur">Couleur :</label>
                            <select id="couleur" name="couleur" class="filtre-select">
                                <option value="">Toutes couleurs</option>
                                {% for co in optionsFiltres.couleurs %}
                                    <option value="{{ co.id_couleur }}" {% if co.id_couleur == couleur %}selected{% endif %}>
                                        {{ co.nom_couleur }}
                                    </option>
                                {% endfor %}
                            </select>
                        </div>

                        <!-- Bouton Filtrer -->
                        <div class="filtre-groupe">
                            <button type="submit" class="btn btn-recherche">
                                <i class="fas fa-search"></i> Appliquer les filtres
                            </button>
                        </div>
                </section>

                <div class="header-resultat">
                    <div class="resultat-nbr"></div>
                    <div class="sort-options">
                        <span>Trier par:</span>
                        <select id="tri-select" class="tri-select" aria-label="tri-select">
                            <option>Prix croissant</option>
                            <option>Prix décroissant</option>
                        </select>
                    </div>
                </div>
        </form>
    </div>

    <!-- Onglets Enchères en cours / Archivées -->
    <div class="btn-tabActions">
        <button class="btn btn-tab active" data-tab="active">Enchères en Cours</button>
        <button class="btn btn-tab" data-tab="termine">Enchères Archivées</button>
    </div>

    <!-- Grille des enchères -->
    <section class="grille">
        {% if encheres and encheres|length > 0 %}
            {% for enchere in encheres %}
                <div class="carte" data-type="{{ enchere.statut_enchere }}" data-prix="{{ enchere.mise_actuelle ? enchere.mise_actuelle : enchere.prix_plancher }}">
                    <div class="carte-image">
                        <!-- Affichage du "Coup de coeur du Lord" si applicable -->
                        {% if enchere.coup_coeur_lord == 1 %}
                            <div class="carte-lordFavorite">
                                <i class="fas fa-crown"></i>
                                Coup de cœur du Lord
                            </div>
                        {% endif %}
                        
                        <!-- Image du timbre -->
                            <img src="{{base}}/public/assets/img/timbres/{{ enchere.premiere_image }}" 
                                 alt="{{ enchere.nom_timbre }}">

                        <!-- Ajouter/retirer la Favoris --> 
                        {% if not guest %}
                            <div class="carte-favoris">
                                <a href="{{base}}/favoris/switch?id_enchere={{ enchere.id_enchere }}" 
                                class="btn-favori {{ enchere.est_favori ? 'btn-favori-active' : 'btn-favori-inactive' }}"
                                title="{{ enchere.est_favori ? 'Retirer des favoris' : 'Ajouter aux favoris' }}">
                                    <i class="fas fa-heart"></i>
                                </a>
                            </div>
                        {% endif %}        
                    </div>
                    
                    <div class="carte-contenu">
                        <h2 class="carte-titre">{{ enchere.nom_timbre }}</h2>
                        
                        <div class="carte-details">
                            <ul>
                                <li>
                                    <strong>Pays d'origine :</strong>
                                    {{ enchere.nom_pays }}
                                </li>
                                <li>
                                    <strong>Date de création :</strong>
                                    {{ enchere.date_creation}}
                                </li>
                                <li>
                                    <strong>Condition:</strong>
                                    {{ enchere.nom_condition }}
                                </li>
                                <li>
                                    <strong>Certifié:</strong>
                                    {{ enchere.certifie ? 'Oui' : 'Non' }}
                                </li>
                            </ul>
                        </div>
                        
                        <div class="carte-info">
                            <div class="carte-prix">
                                £{{ enchere.mise_actuelle ? enchere.mise_actuelle : enchere.prix_plancher }}
                            </div>
                            
                            <div class="carte-temps">
                                {% if enchere.temps_restant.fini %}
                                    <span class="texte">Enchère terminée</span>
                                {% else %}
                                    {{ enchere.temps_restant.texte }}
                                {% endif %}
                            </div>
                        </div>
                        
                        <div class="carte-actions">
                        {% if guest is empty %} 
                            <!-- Utilisateur connecté -->
                            {% if enchere.statut_enchere == 'active' %}
                            <button class="btn" onclick="location.href='{{base}}/enchere/show?id={{ enchere.id_enchere }}'">
                                <i class="fas fa-gavel"></i>
                                Placer une Offre
                            </button>
                        {% endif %}
                        {% else %}
                            <!-- Utilisateur non connecté -->
                            {% if enchere.statut_enchere == 'active' %}
                                <button class="btn" onclick="location.href='{{base}}/login'">
                                    <i class="fas fa-sign-in-alt"></i>
                                    Se connecter
                                </button>
                            {% endif %}
                        {% endif %}

                            <!-- Bouton voir détails toujours visible -->
                            <button class="btn voir" onclick="location.href='{{base}}/enchere/show?id={{ enchere.id_enchere }}'">
                                <i class="fas fa-eye"></i>
                                Voir les détails
                            </button>
                        </div>
                    </div>
                </div>
            {% endfor %}
        {% else %}

        <!-- Message si aucune enchère trouvée -->
        <div class="no-results">
            <h3>Aucune enchère trouvée</h3>
            <p>Aucune enchère ne correspond à vos critères de recherche.</p>
        </div>
        {% endif %}
    </section>
</div>

<!-- Footer -->
{{ include('layouts/footer.php')}}