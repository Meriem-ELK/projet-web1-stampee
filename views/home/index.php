<!-- Header -->
{{ include('layouts/header.php', {title: 'Accueil - Stampee | Enchères Philatéliques de Lord Reginald Stampee III'})}}

        <!-- En-tête de page  (Hero)-->
        <header class="contenu-principal-hero">
                    <div class="container">
                        <div class="contenu-principal-herocontent">
                            <h2>
                            Bienvenue 
                            {% if guest is empty %} 
                            <strong>{{ session.nom }} {{ session.prenom }}</strong>  
                            {% endif %} 
                            chez Stampee</h2>
                            <p class="contenu-principal-herotext">
                                La plateforme d'enchères philatéliques de prestige de Lord Reginald Stampee III.<br>
                                Découvrez les plus beaux timbres du monde et participez à des enchères exclusives.
                            </p>
                            <div class="contenu-principal-herocta">
                                <a href="{{base}}/enchere" class="btn">
                                    <i class="fas fa-gavel"></i>
                                    Voir les Enchères
                                </a>
                                <a href="{{base}}/" class="btn">
                                    <i class="fas fa-user-tie"></i>
                                    À propos du Lord
                                </a>
                            </div>
                        </div>
                    </div>
        </header>

        <!-- Enchères Vedettes -->
        <div class="grille-content">
            <div class="container">
                <div class="grille-header">
                    <h2 class="grille--title">Enchères Vedettes</h2>
                    <p class="grille-subtitle">Découvrez une sélection exceptionnelle de timbres rares et précieux, choisis personnellement par Lord Reginald Stampee III</p>
                </div>

                <!-- Grille des enchères vedettes -->
                    <div class="grille">
                        {% if encheresVedettes and encheresVedettes|length > 0 %}
                            {% for enchere in encheresVedettes %}
                                <div class="carte">
                                    <div class="carte-image">
                                        <!-- Badge coup de cœur -->
                                        <div class="carte-lordFavorite">
                                            <i class="fas fa-crown"></i>
                                            Coup de cœur du Lord
                                        </div>
                                        
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
                                                    {{ enchere.date_creation }}
                                                </li>
                                                <li>
                                                    <strong>Condition:</strong>
                                                    {{ enchere.nom_condition }}
                                                </li>
                                                <li>
                                                    <strong>Certifié :</strong>
                                                    {{ enchere.certifie ? 'Oui' : 'Non' }}
                                                </li>
                                            </ul>
                                        </div>
                                        
                                        <div class="carte-info">
                                            <div class="carte-prix">
                                                £{{ enchere.mise_actuelle ? enchere.mise_actuelle : enchere.prix_plancher }}
                                            </div>
                                            <div class="carte-temps">
                                                {{ enchere.temps_restant.texte }}
                                            </div>
                                        </div>
                                        
                                        <div class="carte-actions">
                                            {% if guest is empty %}
                                                <!-- Utilisateur connecté -->
                                                <button class="btn" onclick="location.href='{{base}}/enchere/show?id={{ enchere.id_enchere }}'">
                                                    <i class="fas fa-gavel"></i>
                                                    Placer une Offre
                                                </button>
                                            {% else %}
                                                <!-- Utilisateur non connecté -->
                                                <button class="btn" onclick="location.href='{{base}}/login'">
                                                    <i class="fas fa-sign-in-alt"></i>
                                                    Se connecter
                                                </button>
                                                <button class="btn voir" onclick="location.href='{{base}}/enchere/show?id={{ enchere.id_enchere }}'">
                                                    <i class="fas fa-eye"></i>
                                                    Voir les détails
                                                </button>
                                            {% endif %}
                                        </div>
                                    </div>
                                </div>
                            {% endfor %}
                        {% else %}
                            <!-- Message si aucun coup de cœur disponible -->
                                
                                    <div class="carte_nonVedette">
                                        <h2 class="carte-titre">Aucune enchère vedette pour le moment

                                        </h2>
                                        <button class="btn" onclick="location.href='{{base}}/enchere'">
                                            <i class="fas fa-gavel"></i>
                                            Voir toutes les enchères
                                        </button>
                                    </div>
                        {% endif %}
                    </div>
                


            </div>
        </div>


        <!-- Section A propos -->
        <section>
                    <div class="container">
                        <div class="grille-twin">
                            <div><img src="{{ asset }}assets/img/lord-portrait.webp" alt="Portrait de Lord Reginald Stampee III"></div>
                            <div class="contenu">
                                <h2>Lord Reginald Stampee III</h2>
                                <p>
                                        Duc de Worcessteshear et philatéliste passionné depuis sa tendre enfance au milieu des années cinquante, 
                                        Lord Reginald Stampee III organise les enchères de timbres les plus prestigieuses du Royaume-Uni.
                                    </p>
                                    <p>
                                        Ses fameuses enchères font accourir les plus grands philatélistes du monde entier. Aujourd'hui, 
                                        Lord Stampee fait le pas dans le numérique pour étendre ses enchères au-delà du Royaume-Uni 
                                        et partager sa passion avec une communauté internationale.
                                    </p>
                                    <p>
                                        <strong>"La philatélie, c'est la vie"</strong> - Lord Reginald Stampee III
                                    </p>

                                    <div>
                                        <a href="{{base}}/" class="btn">
                                            <i class="fas fa-user-tie"></i>
                                            En savoir plus sur le Lord
                                        </a>
                                    </div>
                            </div>
                        </div>
                    </div>
        </section>

        <!-- Section Actualités -->
        <div class="grille-content">
                    <div class="container">
                        <div class="grille-header">
                            <h2 class="grille--title">Actualités Récentes</h2>
                            <p class="grille-subtitle">
                                Restez informé des dernières nouvelles du monde de la philatélie et des enchères Stampee
                            </p>
                        </div>
                    
                        <div class="grille">
                            <article class="carte">
                                <div class="carte-image">
                                    <img src="{{ asset }}assets/img/collection-timbres.webp" alt="Nouvelle collection de timbres" class="actualite">
                                </div>
                                <div class="carte-contenu">
                                    <div class="actualite-date">15 juin 2025</div>
                                    <h3 class="carte-titre">Nouvelle Collection Exceptionnelle</h3>
                                    <p class="carte-details">
                                        Lord Stampee dévoile une collection rare de timbres victoriens, 
                                        incluant des pièces jamais vues aux enchères publiques.
                                    </p>

                                    <a href="{{base}}/">Lire la suite <i class="fas fa-arrow-right"></i></a>
                                </div>
                            </article>

                             <article class="carte">
                                <div class="carte-image">
                                    <img src="{{ asset }}assets/img/exposition.webp" alt="Nouvelle collection de timbres" class="actualite">
                                </div>
                                <div class="carte-contenu">
                                    <div class="actualite-date">18 avril 2025</div>
                                    <h3 class="carte-titre">Grand succès pour l'exposition philatélique de Londres</h3>
                                    <p class="carte-details">
                                        L'exposition annuelle de la Royal Philatelic Society London a réuni plus de 5000 visiteurs ce week-end
                                    </p>

                                    <a href="{{base}}/">Lire la suite <i class="fas fa-arrow-right"></i></a>
                                </div>
                            </article>

                             <article class="carte">
                                <div class="carte-image">
                                    <img src="{{ asset }}assets/img/interview.webp" alt="Nouvelle collection de timbres" class="actualite">
                                </div>
                                <div class="carte-contenu">
                                    <div class="actualite-date">10 mars 2025</div>
                                    <h3 class="carte-titre">Interview exclusive avec Lord Stampee</h3>
                                    <p class="carte-details">
                                        Lord Reginald Stampee partage ses réflexions sur l'avenir de la philatélie et les tendances actuelles
                                    </p>

                                    <a href="{{base}}/">Lire la suite <i class="fas fa-arrow-right"></i></a>
                                </div>
                            </article>

                           
                        </div>
               
                        <div class="grille-btn">
                            <a href="{{base}}/" class="btn">
                                <i class="fas fa-newspaper"></i>
                                Toutes les actualités
                            </a>
                        </div>
                    </div>
        </div>

<!-- Footer -->
 {{ include('layouts/footer.php')}}