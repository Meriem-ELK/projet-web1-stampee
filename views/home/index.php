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
                            <div class="carte">
                        <div class="carte-image">
                            <div class="carte-lordFavorite">
                                <i class="fas fa-crown"></i>
                                Coup de cœur du Lord
                            </div>
                            <img src="{{ asset }}assets/img/encheres/timbre-1.webp" alt="" >
                        </div>
                        <div class="carte-contenu">
                            <h2 class="carte-titre">Collection de timbres variés</h2>
                            <div class="carte-details">
                                <ul>
                                    <li>
                                        <strong>Pays d'origine :</strong>
                                        collection internationale
                                    </li>
                                    <li>
                                        <strong>Date de création :</strong>
                                        1940
                                    </li>
                                    <li>
                                        <strong>Condition:</strong>
                                        Parfaite
                                    </li>
                                    <li>
                                        <strong>Certifié</strong>
                                        Oui
                                    </li>
                                </ul>
                            </div>
                            <div class="carte-info">
                                <div class="carte-prix">£2,850</div>
                                <div class="carte-temps">2j 4h restantes</div>
                            </div>
                            <div class="carte-actions">
                                <button class="btn" onclick="location.href='fiche.html'">
                                    <i class="fas fa-gavel"></i>
                                    Placer une Offre
                                </button>
                                <button class="btn voir" onclick="location.href='fiche.html'">
                                    <i class="fas fa-eye"></i>
                                    Suivre l'enchère
                                </button>
                            </div>
                        </div>
                            </div>
                            <div class="carte">
                                <div class="carte-image">
                                    <img src="{{ asset }}assets/img/encheres/timbre-2.webp" alt="" >
                                </div>
                                <div class="carte-contenu">
                                    <h2 class="carte-titre">New Zealand Health</h2>
                                    <div class="carte-details">
                                        <ul>
                                            <li>
                                                <strong>Pays d'origine :</strong>
                                                Nouvelle-Zélande
                                            </li>
                                            <li>
                                                <strong>Date de création :</strong>
                                                1930
                                            </li>
                                            <li>
                                                <strong>Condition:</strong>
                                                Parfaite
                                            </li>
                                            <li>
                                                <strong>Certifié</strong>
                                                Oui
                                            </li>
                                        </ul>
                                    </div>
                                    <div class="carte-info">
                                        <div class="carte-prix">£425</div>
                                        <div class="carte-temps">1j 12h restantes</div>
                                    </div>
                                    <div class="carte-actions">
                                        <button class="btn" onclick="location.href='fiche.html'">
                                            <i class="fas fa-gavel"></i>
                                            Placer une Offre
                                        </button>
                                        <button class="btn voir" onclick="location.href='fiche.html'">
                                            <i class="fas fa-eye"></i>
                                            Suivre l'enchère
                                        </button>
                                    </div>
                                </div>
                            </div>
                            <div class="carte">
                                <div class="carte-image">
                                    <div class="carte-lordFavorite">
                                        <i class="fas fa-crown"></i>
                                        Coup de cœur du Lord
                                    </div>
                                    <img src="{{ asset }}assets/img/encheres/timbre-3.webp" alt="" >
                                </div>
                                <div class="carte-contenu">
                                    <h2 class="carte-titre">Great Britain Machin Head</h2>
                                    <div class="carte-details">
                                        <ul>
                                            <li>
                                                <strong>Pays d'origine :</strong>
                                                Royaume-Uni (Grande-Bretagne)
                                            </li>
                                            <li>
                                                <strong>Date de création :</strong>
                                                1920
                                            </li>
                                            <li>
                                                <strong>Condition:</strong>
                                                Bonne
                                            </li>
                                            <li>
                                                <strong>Certifié</strong>
                                                Oui
                                            </li>
                                        </ul>
                                    </div>
                                    <div class="carte-info">
                                        <div class="carte-prix">£1,200</div>
                                        <div class="carte-temps">5j 8h restantes</div>
                                    </div>
                                    <div class="carte-actions">
                                        <button class="btn" onclick="location.href='fiche.html'">
                                            <i class="fas fa-gavel"></i>
                                            Placer une Offre
                                        </button>
                                        <button class="btn voir" onclick="location.href='fiche.html'">
                                            <i class="fas fa-eye"></i>
                                            Suivre l'enchère
                                        </button>
                                    </div>
                                </div>
                            </div>
                        </div>
                        <div class="grille-btn">
                            <a href="{{base}}/enchere" class="btn">
                                    <i class="fas fa-gavel"></i>
                                    Voir Toutes les Enchères
                            </a>
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