{{ include('layouts/header.php', {title: 'Error'})}}
 <div class="conatiner">
      <div class="section_erreur">
         <h2>Erreur</h2>
         <strong class="error">{{ message }}</strong>
         <a href="{{base}}" class="btn add"> Retour à la page d'accueil</a>
      </div>
      
 </div>
{{ include('layouts/footer.php')}}