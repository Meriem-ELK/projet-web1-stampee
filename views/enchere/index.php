<!-- Header -->
{{ include('layouts/header.php', {title: 'Catalogue des Enchères - Stampee'})}}
 
<div class="container">
    <!-- En-tête de page -->
    <header class="contenu-principal-header">
        <h2 class="contenu-principal-titre">Portail des Enchères</h2>
        <p class="contenu-principal-sutitre">Découvrez les trésors philatéliques sélectionnés par Lord Reginald Stampee III</p>
    </header>

    <!-- Grille des enchères -->
    <section class="grille">
        {% if encheres and encheres|length > 0 %}
            {% for enchere in encheres %}
                <div class="carte">
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
                                    £{{ enchere.prix_plancher }}
                            </div>
                            
                            <div class="carte-temps">
                                    {{ enchere.temps_restant.texte }}
                            </div>
                        </div>
                        
                        <div class="carte-actions">
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
            <p>Il n'y a actuellement aucune enchère disponible.</p>
        </div>
        {% endif %}
    </section>
</div>

<!-- Footer -->
{{ include('layouts/footer.php')}}