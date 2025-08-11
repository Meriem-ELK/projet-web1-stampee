<?php
namespace App\Controllers;

use App\Providers\View;
use App\Providers\Validator;
use App\Providers\Auth;
use App\Models\User;

class UserController {

/*------------------------------- Create */
// Méthode pour afficher le formulaire de création d'utilisateur
    public function create(){

        return View::render('user/create');
    }

/*------------------------------- Store */
// Méthode qui traite la soumission du formulaire d'inscription
    public function store($data)
    {
        $validator = new Validator;
        $validator->field('nom', $data['nom'])->required()->min(2)->max(50);
        $validator->field('prenom', $data['prenom'])->required()->min(2)->max(50);
        $validator->field('nom_utilisateur', $data['nom_utilisateur'], 'Le nom utilisateur')->required()->min(2)->max(50)->unique('User');
        $validator->field('mot_de_passe', $data['password'], 'Le mot de passe')->required()->min(6)->max(20);
        $validator->field('email', $data['email'])->required()->min(2)->max(50)->email()->unique('User');

        if($validator->isSuccess())
        {
            $user = new User;
            $data['mot_de_passe'] = $user->hashPassword($data['password']);

            // Insère l'utilisateur dans la base de données
            $insert = $user->insert($data);
            
            if($insert){
                // Créer automatiquement la session après inscription
                $newUser = $user->unique('email', $data['email']);
                if($newUser){
                    session_regenerate_id();
                    $_SESSION['id_utilisateur'] = $newUser['id'];
                    $_SESSION['nom'] = $newUser['nom'];
                    $_SESSION['prenom'] = $newUser['prenom'];
                    $_SESSION['fingerPrint'] = md5($_SERVER['HTTP_USER_AGENT'].$_SERVER['REMOTE_ADDR']);
                }
                
                return View::redirect('login');
                
            } else {
                $errors['message'] = "Erreur lors de la création du compte.";
                return View::render('user/create', ['errors'=>$errors, 'user'=>$data]);
            }
        } else 
        {
            $errors = $validator->getErrors();
            return View::render('user/create', ['errors'=>$errors, 'user'=>$data]);
        }
    }
}