<!-- Header -->
{{ include('layouts/header.php', {title: 'création de compte'})}}

<!-- Section principale contenant le formulaire -->
<section class="form-control smallbox">
    <h2>Créer un nouveau compte</h2>

    <!-- Affichage des erreurs de validation s'il y en a -->
    {% if errors is defined %}
        <div class="error">
            <ul>
                {% for error in errors %}
                    <li>{{ error }}</li>
                {% endfor %}
            </ul>
        </div>
    {% endif %}

    <!-- Les avantages pour les membres -->
    <div class="benefits">
        <h4>Avantages membres :</h4>
        <ul>
            <li><i class="fas fa-gavel"></i> Participez aux enchères exclusives</li>
            <li><i class="fas fa-heart"></i> Sauvegardez vos enchères favorites</li>
            <li><i class="fas fa-crown"></i> Accès prioritaire aux "Coups de cœur du Lord"</li>
        </ul>
    </div> 

    <!-- Formulaire d'inscription -->
    <form action="{{base}}/user/store" method="post" class="form_user">
        
        <!-- Champ : prénom -->
        <div class="form-group">
            <label class="form-label">Prénom *</label>
            <input type="text" name="prenom" class="form-input" value="{{ utilisateurs.prenom }}">
        </div>

        <!-- Champ : nom -->
        <div class="form-group">
            <label class="form-label">Nom de famille *</label>
            <input type="text" name="nom" class="form-input" value="{{ utilisateurs.nom }}">
        </div>

        <!-- Champ : nom d'utilisateur  -->
        <div class="form-group">
            <label class="form-label">Nom d'utilisateur</label>
            <input type="text" name="nom_utilisateur" class="form-input" value="{{ utilisateurs.nom_utilisateur }}">
        </div>

        <!-- Champ : email  -->
        <div class="form-group">
            <label class="form-label">Email</label>
            <input type="email" name="email" class="form-input" value="{{ utilisateurs.email }}">
        </div>

        <!-- Champ : mot de passe -->
        <div class="form-group">
            <label class="form-label">Mot de passe</label>
            <input type="password" name="password" class="form-input">
        </div>

        <!-- Bouton de soumission du formulaire -->
        <button type="submit" class="btn add">
            <i class="fas fa-user-plus"></i> Créer mon compte
        </button>

        <!-- Lien vers la page de connexion pour les membres existants -->
        <div class="auth-switch">
            <p>Déjà membre ? <a href="{{base}}/login" class="switch-btn">Se connecter</a></p>
        </div>
    </form>

<!-- Footer -->
{{ include('layouts/footer.php')}}
