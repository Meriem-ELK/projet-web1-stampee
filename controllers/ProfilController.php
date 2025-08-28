<?php
namespace App\Controllers;

use App\Providers\View;
use App\Providers\Auth;
use App\Providers\Validator;
use App\Models\User;
use App\Models\Timbre; 
use App\Models\Favoris; 
use App\Models\Mise;

class ProfilController{
    
public function index(){
    Auth::session();
    
    $user = new User();
    $timbre = new Timbre();
    $favoris = new Favoris(); 
    $mise = new Mise(); 

    // Récupérer les erreurs de session s'il y en a
    $errors = [];
    if (isset($_SESSION['profil_errors'])) {
        $errors = $_SESSION['profil_errors'];
        unset($_SESSION['profil_errors']); // Nettoyer après récupération
    }
    
    // Récupérer les données de l'utilisateur par son ID
    $utilisateur = $user->selectId($_SESSION['id_utilisateur']);
    
    // Récupérer tous les timbres créés par cet utilisateur
    $mesTimbres = $timbre->getTimbresByUser($_SESSION['id_utilisateur']);

    // Récupérer les enchères favorites de l'utilisateur
    $mesFavoris = $favoris->getFavorisByUser($_SESSION['id_utilisateur']);

    // Récupérer toutes les offres de l'utilisateur
    $mesOffres = $mise->getMisesByUser($_SESSION['id_utilisateur']);
    
    // Récupérer les enchères où l'utilisateur est en tête
    $encheresEnTete = $mise->getEncheresEnTete($_SESSION['id_utilisateur']);

    // Récupérer les enchères gagnées par l'utilisateur
    $encheresGagnees = $mise->getEncheresGagnees($_SESSION['id_utilisateur']);

    
    // Si l'utilisateur existe dans la base de données
    if($utilisateur){
        return View::render('profil/index', [
            'errors' => $errors, 
            'utilisateur' => $utilisateur,
            'mesTimbres' => $mesTimbres,
            'mesFavoris' => $mesFavoris,
            'mesOffres' => $mesOffres,
            'encheresEnTete' => $encheresEnTete,
            'encheresGagnees' => $encheresGagnees
        ]);
    } else {
        return View::render('error', ['message'=>"Erreur - Utilisateur introuvable."]);
    }
}


/*--------------------------------------------- Edit Form */
public function pageEdit(){
    Auth::session();
    
    // Vérifier que l'ID en paramètre correspond à l'utilisateur connecté
    if(!isset($_GET['id']) || $_GET['id'] != $_SESSION['id_utilisateur']){
        return View::render('error', ['message'=>"Erreur - Accès refusé !"]);
    } else {
        // Récupérer les informations actuelles de l'utilisateur
        $user = new User();
        $utilisateur = $user->selectId($_SESSION['id_utilisateur']);

        if($utilisateur){
            return View::render('profil/edit', ['utilisateur'=>$utilisateur]);
        } else {
            return View::render('error', ['message'=>"Erreur - Utilisateur introuvable."]);
        }
    }
}

/*--------------------------------------------- Update */
public function edit($data){
    Auth::session();
    
    // Vérifier que la requête vient bien d'un formulaire POST
    if ($_SERVER['REQUEST_METHOD'] !== 'POST') {
        return View::render('error', ['message'=>'Erreur - Page introuvable!']);
    } else {
        // Créer le validateur pour vérifier les données
        $validator = new Validator;
        $validator->field('nom_utilisateur', $data['nom_utilisateur'])->required()->min(2)->max(50);
        $validator->field('nom', $data['nom'])->required()->min(2)->max(50);
        $validator->field('prenom', $data['prenom'])->required()->min(2)->max(50);
        $validator->field('email', $data['email'])->required()->min(2)->max(100)->email();
        
        // Valider le mot de passe seulement s'il est rempli
        if(!empty($data['mot_de_passe'])){
            $validator->field('mot_de_passe', $data['mot_de_passe'])->min(6)->max(20);
            
            // Vérifier que la confirmation correspond
            if(!isset($data['confirmationMotPasse']) || $data['mot_de_passe'] !== $data['confirmationMotPasse']){
                $validator->addError('mot_de_passe', 'Les mots de passe ne correspondent pas');
            }
        }

        // Si la validation est réussie
        if($validator->isSuccess()){
            $user = new User;
            
            // Préparer les données pour l'update (exclure la confirmation)
            $dataUpdate = [
                'nom_utilisateur' => $data['nom_utilisateur'],
                'nom' => $data['nom'],
                'prenom' => $data['prenom'],
                'email' => $data['email']
            ];
            
            // Ajouter le mot de passe hashé seulement s'il a été modifié
            if(!empty($data['mot_de_passe'])){
                $dataUpdate['mot_de_passe'] = $user->hashPassword($data['mot_de_passe']);
            }

            // Mettre à jour l'utilisateur dans la base de données
            $utilisateurAjour = $user->update($dataUpdate, $_SESSION['id_utilisateur']);
            
            // Si la mise à jour a réussi
            if($utilisateurAjour){
                // Mettre à jour les données de session si nécessaire
                $_SESSION['nom'] = $data['nom'];
                $_SESSION['prenom'] = $data['prenom'];
                
                return View::redirect('profil');
            } else {
                return View::render('error', ['message'=>"Les modifications ont échoué"]);
            }
        } else {
            // Récupérer les données utilisateur actuelles pour réafficher le formulaire
            $user = new User;
            $utilisateur = $user->selectId($_SESSION['id_utilisateur']);
            
            $errors = $validator->getErrors();
            return View::render('profil/edit', [
                'errors' => $errors, 
                'utilisateur' => $utilisateur
            ]);
        }
    }
}

/*---------------------------------------------  Delete */
public function delete(){
    Auth::session();
    
    // Vérifier que l'ID en paramètre correspond à l'utilisateur connecté
    if(!isset($_GET['id']) || $_GET['id'] != $_SESSION['id_utilisateur']){
        return View::render('error', ['message'=>"Erreur - Accès refusé !"]);
    } else {
        // Vérifier que la requête vient bien d'un lien GET
        if ($_SERVER['REQUEST_METHOD'] !== 'GET') {
            return View::render('error', ['message'=>'Erreur 404 - Page introuvable!']);
        } else {
            // Récupérer l'ID à supprimer
            $id = $_GET["id"];
            $user = new User;
            $timbre = new Timbre;
            
            // Supprimer d'abord tous les timbres de l'utilisateur
            $mesTimbres = $timbre->getTimbresByUser($id);
            foreach ($mesTimbres as $timbreData) {
                // Supprimer les images du timbre
                $timbre->deleteTimbreImages($timbreData['id_timbre']);
                // Supprimer le timbre
                $timbre->delete($timbreData['id_timbre']);
            }
            
            // Supprimer l'utilisateur de la base de données
            $utilisateurSupprime = $user->delete($id);
            
            if($utilisateurSupprime){
                // Détruire la session et rediriger vers la page de connexion
                session_destroy();
                return View::redirect('login');
            } else {
                return View::render('error', ['message'=>"404 - La suppression a échoué"]);
            }
        }
    }
}

}