<!-- Header -->
{{ include('layouts/header.php', {title: 'Accueil - Stampee | Enchères Philatéliques de Lord Reginald Stampee III'})}}

        <div class="container">
            {% if guest is empty %}
                    <h2>Bienvenue <strong>{{ session.nom }} {{ session.prenom }}</strong></h2>
            {% endif %}
        </div>


<!-- Footer -->
 {{ include('layouts/footer.php')}}