<?php
namespace App\Models;
use App\Models\CRUD;

/**
 * Modèle User pour la gestion des utilisateurs
 */
class User extends CRUD {
    protected $table = "utilisateurs";
    protected $primaryKey = "id_utilisateur";
    protected $fillable = [
        'nom_utilisateur',
        'email',
        'mot_de_passe',
        'nom',
        'prenom',
        'date_inscription'
    ];

    public function hashPassword($password, $cost = 10){
        $options = [
            'cost' => $cost
        ];
        return password_hash($password, PASSWORD_BCRYPT, $options); 
    }

    public function checkUser($email, $password){
        $user = $this->unique('email', $email);
        if($user){
            if(password_verify($password, $user['mot_de_passe'])){
                session_regenerate_id();
                $_SESSION['id_utilisateur'] = $user['id_utilisateur'];
                $_SESSION['nom'] = $user['nom'];
                $_SESSION['prenom'] = $user['prenom'];
                $_SESSION['fingerPrint'] = md5($_SERVER['HTTP_USER_AGENT'].$_SERVER['REMOTE_ADDR']);
                
                return true;
            } else {
                return false;   
            }
        } else {
            return false; 
        }
    }

}
?>