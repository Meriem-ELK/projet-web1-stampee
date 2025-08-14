{{ include('layouts/header.php', {title: 'Modification du Profil - Stampee'})}}

<section class="form-control">

   {% if errors is defined %}
    <div class="error">
        <ul>
            {% for error in errors %}
                <li>{{ error }}</li>
            {% endfor %}
        </ul>
    </div>
    {% endif %}



    <h2>Modification du profil</h2>

    <form class="form" method="post">
            <div class="form-group">
                <label for="nom_utilisateur">Nom d'utilisateur :</label>
                <input class="form-input"
                    type="text"
                    id="nomUtilisateur"
                    name="nom_utilisateur"
                    placeholder="Choisissez un nom d'utilisateur"
                    value="{{utilisateur.nom_utilisateur}}"
                />
            </div>
          
            <div class="form-group">
                <label class="form-label" for="nom">Nom :</label>
                <input class="form-input"
                    type="text"
                    id="nom"
                    name="nom"
                    placeholder="Entrez votre nom"
                    value="{{utilisateur.nom}}"
                />
            </div>
          
            <div class="form-group">
                <label class="form-label" for="prenom">Prénom :</label>
                <input class="form-input"
                    type="text"
                    id="prenom"
                    name="prenom"
                    placeholder="Entrez votre prénom"
                    value="{{utilisateur.prenom}}"
                />
            </div>
            
            <div class="form-group">
                <label class="form-label" for="email">Adresse e-mail :</label>
                <input class="form-input"
                    type="email"
                    id="email"
                    name="email"
                    placeholder="Entrez votre e-mail"
                    value="{{utilisateur.email}}"
                />
            </div>
           
            <div class="form-group">
                <label class="form-label" for="motDePasse">Nouveau mot de passe :</label>
                <input class="form-input"
                    type="password"
                    id="motDePasse"
                    name="mot_de_passe"
                    placeholder="Laissez vide pour conserver l'actuel"
                />
            </div>
           
            <div class="form-group">
                <label class="form-label" for="confirmationMotPasse">Confirmez le mot de passe :</label>
                <input class="form-input"
                    type="password"
                    id="confirmationMotPasse"
                    name="confirmationMotPasse"
                    placeholder="Confirmez votre nouveau mot de passe"
                />
            </div>
           
            <div class="form__GroupBtn">
                <a class="btn annuler" href="{{base}}/">
                    <i class="fas fa-times"></i> Annuler
                </a>
                <input class="btn" type="submit" value="Enregistrer">
            </div>
        </form>
</section> 


{{ include('layouts/footer.php')}}