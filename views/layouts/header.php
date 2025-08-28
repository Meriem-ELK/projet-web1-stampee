<!DOCTYPE html>
<html lang="fr">
    <head>
        <meta charset="UTF-8" >
        <meta name="viewport" content="width=device-width, initial-scale=1.0" >
        <meta name="description" content="Portail des enchères Stampee - Enchères philatéliques de Lord Reginald Stampee III" >
        <meta name="keywords" content="enchères, timbres, philatélie, Lord Stampee, collection" >
        <link rel="icon" type="image/svg+xml" href="{{ asset }}assets/img/favicon.svg">
        <title>{{ title }}</title>
        <!-- Fonts -->
        <link rel="preconnect" href="https://fonts.googleapis.com" >
        <link rel="preconnect" href="https://fonts.gstatic.com" crossorigin >
        <link href="https://fonts.googleapis.com/css2?family=Playfair+Display:wght@400;600;700&family=Raleway:wght@300;400;500;600&display=swap" rel="stylesheet" >
        <!-- Icones -->
        <link href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/5.15.4/css/all.min.css" rel="stylesheet" >
        <link rel="stylesheet" href="{{ asset }}assets/css/main.css" >
        <script src="{{ asset }}assets/js/script.js" defer></script>

    </head>
    <body>
        <!-- nav_principale -->
        <div class="nav_principale">
            <div class="container">
                <input type="checkbox" id="bouton-nav" aria-label="bouton-nav">
                <!-- Top Header -->
                <div class="header-top">
                    <div class="header-info">
                        <span>
                            <i class="fas fa-phone"></i>
                            &nbsp;&nbsp;+44 20 7946 0958
                        </span>
                        <span>
                            <i class="fas fa-envelope"></i>
                            &nbsp;&nbsp; lord.stampee@stampee.co.uk
                        </span>
                    </div>
                    <div class="header-actions">
                        <button class="btn btn-devise" aria-label="Changer de devise">
                            GBP £
                            <i class="fas fa-chevron-down"></i>
                        </button>
                        <span class="separateur">|</span>
                        <span class="header-langue">
                            <i class="fas fa-globe"></i>
                            <a href="">Anglais</a>
                            -
                            <a href="">Français</a>
                        </span>

                        {% if guest %}
                        <a href="{{base}}/login" class="btn btn-premium">
                            <i class="fas fa-sign-in-alt"></i>
                            Se connecter
                        </a>
                        <a href="{{base}}/user/create" class="btn">
                            <i class="fas fa-user-plus"></i>
                            Devenir membre
                        </a>

                        {% else %}
                        <a href="{{base}}/profil" class="btn"><i class="fas fa-user-cog"></i> Mon compte</a>
                        <a href="{{base}}/logout" class="btn btn-premium"><i class="fas fa-sign-out-alt"></i> Déconnexion</a>
                        {% endif %}


                    </div>
                </div>
                <!-- Main Header -->
                <div class="header-main">
                    <a href="{{base}}/" class="nav-logo">
                        <div class="nav-logoIcone">
                            <i class="fas fa-crown"></i>
                        </div>
                        <div class="nav-logoTexte">
                            <h1>STAMPEE</h1>
                            <div class="nav-logoSlogan">Lord Reginald Stampee III</div>
                        </div>
                    </a>
                    <div class="recherche-container">
                        
                        <!-- Formulaire de recherche -->
                        <form method="get" action="{{ base }}/enchere" class="recherche-form">
                            <input type="text" name="recherche" class="recherche-input" placeholder="Rechercher une enchère..." value="{{ recherche }}"  aria-label="Rechercher une enchère">
                            <button type="submit" class="btn btn-recherche">Rechercher</button>
                        </form>

                    </div>
                </div>
            </div>
            <!-- Navigation principale -->
            <nav class="nav_principale-site">
                <div class="container">
                    <input type="checkbox" id="bouton-menu" aria-label="bouton-menu">
                    <ul class="nav-liste">
                        <li>
                            <a href="{{base}}/" class="nav-lien active">
                                <i class="fas fa-home"></i>
                                Accueil
                            </a>
                        </li>
                        <li>
                            <a href="{{base}}/enchere" class="nav-lien">
                                <i class="fas fa-gavel"></i>
                                Enchères
                            </a>
                        </li>
                        <li>
                            <a href="{{base}}/about" class="nav-lien">
                                <i class="fas fa-user-tie"></i>
                                À propos du Lord
                            </a>
                        </li>
                        <li>
                            <a href="{{base}}/" class="nav-lien">
                                <i class="fas fa-newspaper"></i>
                                Actualités
                            </a>
                        </li>
                        <li>
                            <a href="{{base}}/contact" class="nav-lien">
                                <i class="fas fa-envelope"></i>
                                Contact
                            </a>
                        </li>
                    </ul>
                </div>
            </nav>
        </div>
        <!-- Contenu principal -->
        <main class="contenu-principal">