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

    

/**
     * Vérifie si un utilisateur a des données liées.
*/
    public function hasRelatedData($userId) {
        // Vérifier s'il a des timbres créés
        $sqlTimbres = "SELECT COUNT(*) as count FROM timbres WHERE id_utilisateur_createur = :user_id";
        $stmtTimbres = $this->prepare($sqlTimbres);
        $stmtTimbres->bindValue(':user_id', $userId);
        $stmtTimbres->execute();
        $timbres = $stmtTimbres->fetch();
        
        // Vérifier s'il a des mises
        $sqlMises = "SELECT COUNT(*) as count FROM mises WHERE id_utilisateur = :user_id";
        $stmtMises = $this->prepare($sqlMises);
        $stmtMises->bindValue(':user_id', $userId);
        $stmtMises->execute();
        $mises = $stmtMises->fetch();
        
        // Vérifier s'il a des favoris
        $sqlFavoris = "SELECT COUNT(*) as count FROM favoris WHERE id_utilisateur = :user_id";
        $stmtFavoris = $this->prepare($sqlFavoris);
        $stmtFavoris->bindValue(':user_id', $userId);
        $stmtFavoris->execute();
        $favoris = $stmtFavoris->fetch();
        
        // Vérifier s'il a des commentaires
        $sqlCommentaires = "SELECT COUNT(*) as count FROM commentaires WHERE id_utilisateur = :user_id";
        $stmtCommentaires = $this->prepare($sqlCommentaires);
        $stmtCommentaires->bindValue(':user_id', $userId);
        $stmtCommentaires->execute();
        $commentaires = $stmtCommentaires->fetch();
        
        return ($timbres['count'] > 0 || $mises['count'] > 0 || $favoris['count'] > 0 || $commentaires['count'] > 0);
    }


















}
?>