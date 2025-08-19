<?php
namespace App\Controllers;

use App\Providers\View;
use App\Providers\Validator;
use App\Models\User;
class AuthController{
    

    // Méthode qui affiche la page de connexion
    public function index(){
        return View::render('auth/index');
    }

    // Méthode qui traite la soumission du formulaire de connexion
    public function store($data){
        $validator = new Validator;
        $validator->field('email', $data['email'])->min(2)->max(50)->email();
        $validator->field('mot_de_passe', $data['password'], 'Le mot de passe')->min(6)->max(20);
        
         if($validator->isSuccess()){
            $user = new User;
            $checkuser = $user->checkUser($data['email'], $data['password']);
            if($checkuser){
                return View::redirect('');
            }else{
                $errors['message'] = "Veuillez vérifier vos informations d'identification!";
                return View::render('auth/index', ['errors'=>$errors, 'utilisateurs'=>$data]);
            }

        }else{
            $errors = $validator->getErrors();
            return View::render('auth/index', ['errors'=>$errors, 'utilisateurs'=>$data]);
        }


    }
    public function delete(){
        session_destroy();
        return View::redirect('login');
    }
}

?>