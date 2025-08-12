<!-- Header -->
{{ include('layouts/header.php', {title: 'Profil - Stampee '})}}

<section class="form-control">
            {% if guest is empty %}
                    <h2>Bienvenue <strong>{{ session.nom }} {{ session.prenom }}</strong></h2>
            {% endif %}
        
            <h3 class="taille_texte_200">Mes informations personnelles</h3>

            <div class="profil">
                <div class="profil_informations"><span>Nom d'utilisateur :</span> {{ utilisateur.nom_utilisateur }}</div>
                <div class="profil_informations"><span>Nom :</span> {{ utilisateur.nom }}</div>
                <div class="profil_informations"><span>Prénom :</span> {{ utilisateur.prenom }}</div>
                <div class="profil_informations"><span>Adresse courriel :</span> {{ utilisateur.email }}</div>  
                <div class="profil_informations"><span>Mot de passe :</span> ********</div>
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

<!-- Footer -->
 {{ include('layouts/footer.php')}}