<!-- Header -->
{{ include('layouts/header.php', {title: 'Contact - Stampee | Contactez Lord Reginald Stampee III'})}}

    <!-- En-tête de page Contact -->
    <header class="contenu-principal-hero">
        <div class="container">
            <div class="contenu-principal-herocontent">
                <h2>Contactez Lord Stampee</h2>
                <p class="contenu-principal-herotext">
                    Vous avez une question sur nos enchères philatéliques ? <br>
                    Souhaitez-vous proposer une collection ?
                    Lord Reginald Stampee III sera ravi de vous répondre personnellement.
                </p>
            </div>
        </div>
    </header>


    <div class="container">

        <!-- Formulaire de contact -->
        <div class="grille-content">
            <div class="container">
                <div class="grille-two">
                    
                    <!-- Formulaire -->
                    <div class="contact-form">
                        <h3>Envoyez-nous un message</h3>
                        
                        <form action="{{base}}/contact/send" method="POST" class="form-contact">
                            <div class="form-groupe">
                                <label for="nom">Nom complet *</label>
                                <input type="text" id="nom" name="nom" required 
                                       placeholder="Votre nom et prénom">
                            </div>

                            <div class="form-groupe">
                                <label for="email">Adresse e-mail *</label>
                                <input type="email" id="email" name="email" required 
                                       placeholder="votre.email@exemple.com">
                            </div>

                            <div class="form-groupe">
                                <label for="telephone">Téléphone</label>
                                <input type="tel" id="telephone" name="telephone" 
                                       placeholder="Votre numéro de téléphone (optionnel)">
                            </div>

                            <div class="form-groupe">
                                <label for="sujet">Sujet de votre message *</label>
                                <select id="sujet" name="sujet" required>
                                    <option value="">Choisissez un sujet</option>
                                    <option value="enchere">Question sur une enchère</option>
                                    <option value="compte">Problème de compte</option>
                                    <option value="expertise">Demande d'expertise</option>
                                    <option value="partenariat">Partenariat</option>
                                    <option value="autre">Autre</option>
                                </select>
                            </div>

                            <div class="form-groupe">
                                <label for="message">Votre message *</label>
                                <textarea id="message" name="message" rows="6" required 
                                          placeholder="Décrivez votre demande en détail..."></textarea>
                            </div>

                            <div class="form-groupe form-checkbox">
                                <input type="checkbox" id="newsletter" name="newsletter" value="1">
                                <label for="newsletter">
                                    J'aimerais recevoir la newsletter de Lord Stampee avec les dernières actualités philatéliques
                                </label>
                            </div>

                            <div class="form-actions">
                                <button type="submit" class="btn">
                                    <i class="fas fa-paper-plane"></i>
                                    Envoyer le message
                                </button>
                                <button type="reset" class="btn voir">
                                    <i class="fas fa-undo"></i>
                                    Réinitialiser
                                </button>
                            </div>
                        </form>
                    </div>

                    <!-- Informations de contact -->
                    <div class="contact-info">
                        <h3>Informations de contact</h3>
                        
                        <div class="info-bloc">

                            <div class="info-item">
                                <i class="fas fa-phone"></i>
                                <div>
                                    <strong>Téléphone</strong>
                                    <p>+44 20 7946 0958</p>
                                </div>
                            </div>

                            <div class="info-item">
                                <i class="fas fa-envelope"></i>
                                <div>
                                    <strong>E-mail</strong>
                                    <p>lord.stampee@stampee.co.uk</p>
                                </div>
                            </div>

                            <div class="info-item">
                                <i class="fas fa-clock"></i>
                                <div>
                                    <strong>Horaires de contact par téléphone</strong>
                                    <p>
                                        Lundi - Vendredi : 9h00 - 17h00<br>
                                        Samedi : 10h00 - 16h00<br>
                                        Dimanche : Fermé
                                    </p>
                                </div>
                            </div>
                        </div>

                    </div>
                </div>
            </div>
        </div>
    </div>


    <!-- Section FAQ rapide -->
    <section class="grille-content">
        <div class="container">
            <div class="grille-header">
                <h2 class="grille--title">Questions Fréquentes</h2>
                <p class="grille-subtitle">Trouvez rapidement des réponses aux questions les plus courantes</p>
            </div>

            <div class="grille">
                <div class="faq-item">
                    <h4><i class="fas fa-question-circle"></i> Comment participer à une enchère ?</h4>
                    <p>Il suffit de créer un compte, de vous connecter et de placer votre offre sur l'enchère de votre choix.</p>
                </div>

                <div class="faq-item">
                    <h4><i class="fas fa-question-circle"></i> Comment savoir si j'ai gagné une enchère?</h4>
                    <p>Si vous êtes le meilleur enchérisseur à la fin de la période d'enchère, l'enchère apparaîtra dans la section "Mes enchères gagnées" de votre profil.</p>
                </div>

                <div class="faq-item">
                    <h4><i class="fas fa-question-circle"></i> Puis-je modifier mes informations de profil ? </h4>
                    <p>Oui, vous pouvez modifier vos informations personnelles (nom, prénom, email..) depuis votre page de profil. Connectez-vous à votre compte et accédez à la section "Mon compte».</p>
                </div>

                <div class="faq-item">
                    <h4><i class="fas fa-question-circle"></i> Puis-je annuler une offre que j'ai placée ?</h4>
                    <p>Une fois qu'une offre est placée, elle ne peut généralement pas être annulée. Assurez-vous de bien vérifier le montant avant de confirmer votre mise.</p>
                </div>
            </div>
        </div>
    </section>

<!-- Footer -->
{{ include('layouts/footer.php')}}