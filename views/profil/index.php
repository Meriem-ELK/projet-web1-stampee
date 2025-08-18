<!-- Header -->
{{ include('layouts/header.php', {title: 'Profil - Stampee '})}}

<div class="container">

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

    <!-- Section : Collection de timbres -->
    <section class="form-control">
        <div class="profile-section">
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

</div>

<!-- Footer -->
{{ include('layouts/footer.php') }}
