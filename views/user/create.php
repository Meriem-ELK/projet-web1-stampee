{{ include('layouts/header.php', {title: 'création de compte'})}}
<section class="form-control">
    <h2>Créer un nouveau compte</h2>
                {% if errors is defined %}
                <div class="error">
                    <ul>
                        {% for error in errors %}
                            <li>{{ error }}</li>
                        {% endfor %}
                    </ul>
                </div>
                {% endif %}
    <div class="benefits">
            <h4>Avantages membres :</h4>
            <ul>
                <li><i class="fas fa-gavel"></i> Participez aux enchères exclusives</li>
                <li><i class="fas fa-heart"></i> Sauvegardez vos enchères favorites</li>
                <li><i class="fas fa-crown"></i> Accès prioritaire aux "Coups de cœur du Lord"</li>
            </ul>
    </div>       
    <form action="{{base}}/user/store" method="post" class="form_user">
        <div class="form-group">
            <label class="form-label">Prénom *</label>
            <input type="text" name="prenom" class="form-input" value="{{ utilisateurs.prenom }}">
        </div>

        <div class="form-group">
            <label class="form-label">Nom de famille *</label>
            <input type="text" name="nom" class="form-input" value="{{ utilisateurs.nom }}">
        </div>

        <div class="form-group">
            <label class="form-label">Nom d'utilisateur</label>
            <input type="text" name="nom_utilisateur" class="form-input" value="{{ utilisateurs.nom_utilisateur }}">
        </div>

        <div class="form-group">
            <label class="form-label">Email</label>
            <input type="email" name="email" class="form-input" value="{{ utilisateurs.email }}">
        </div>

        <div class="form-group">
            <label class="form-label">Mot de passe</label>
            <input type="password" name="password" class="form-input">
        </div>

        <button type="submit" class="btn add">
            <i class="fas fa-user-plus"></i> Créer mon compte
        </button>

        <div class="auth-switch">
                <p>Déjà membre ? <a href="{{base}}/login" class="switch-btn">Se connecter</a></p>
        </div>
 
    </form>

{{ include('layouts/footer.php')}}