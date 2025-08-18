<!-- Header -->
{{ include('layouts/header.php', {title: 'Login'})}}

<!-- Section contenant le formulaire de connexion -->
<section class="form-control smallbox">
    <h2><i class="fas fa-sign-in-alt"></i> Connexion à votre compte</h2>

    <!-- Affichage des erreurs de validation -->
    {% if errors is defined %}
        <div class="error">
            <ul>
                {% for error in errors %}
                    <li>{{ error }}</li>
                {% endfor %}
            </ul>
        </div>
    {% endif %}

    <!-- Affichage d'une erreur de session -->
    {% if session.error is defined %}
        <div class="error">
            <ul>
                <li>{{ session.error }}</li>
            </ul>
        </div>
    {% endif %}

    <!-- Formulaire de connexion -->
    <form method="post" class="form_user">

        <!-- Champ email -->
        <div class="form-group">
            <label class="form-label">Adresse e-mail</label>
            <input type="email" class="form-input" name="email" value="{{ utilisateurs.email }}">
        </div>

        <!-- Champ mot de passe  -->
        <div class="form-group">
            <label class="form-label">Mot de passe</label>
            <input type="password" name="password" class="form-input">
        </div>

        <!-- Bouton de connexion -->
        <button type="submit" class="btn add">
            <i class="fas fa-sign-in-alt"></i> Se connecter
        </button>

        <!-- Lien vers la page de création de compte -->
        <div class="auth-switch">
            <p>Pas encore membre ? 
                <a href="{{base}}/user/create" class="switch-btn">Créer un compte</a>
            </p>
        </div>
        
    </form>
</section>

<!-- Footer -->
{{ include('layouts/footer.php')}}
