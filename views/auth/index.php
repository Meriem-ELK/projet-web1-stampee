{{ include('layouts/header.php', {title: 'Login'})}}

<section class="form-control">
    <h2><i class="fas fa-sign-in-alt"></i> Connexion à votre compte</h2>

    {% if errors is defined %}
    <div class="error">
        <ul>
            {% for error in errors %}
                <li>{{ error }}</li>
            {% endfor %}
        </ul>
    </div>
    {% endif %}


    <!-- -->
    {% if session.error is defined %}
    <div class="error">
        <ul>
            <li>{{ session.error }}</li>
        </ul>
    </div>
    {% endif %}


    <form method="post" class="form_user">
        <div class="form-group">
            <label class="form-label">Adresse e-mail</label>
            <input type="email" class="form-input" name="email" value="{{ utilisateurs.email }}">
        </div>

        <div class="form-group">
            <label class="form-label">Mot de passe</label>
            <input type="password" name="password" class="form-input">
        </div>

        <button type="submit" class="btn add">
            <i class="fas fa-sign-in-alt"></i> Se connecter
        </button>

        <div class="auth-switch">
                <p>Pas encore membre ? <a href="{{base}}/user/create" class="switch-btn">Créer un compte</a></p>
        </div>
        
    </form>
</section>
{{ include('layouts/footer.php')}}