<?php
namespace App\Controllers;

use App\Providers\View;
use App\Providers\Auth;
use App\Models\Favoris;

class FavorisController {

    /**
     * Ajoute ou supprime une enchère des favoris
     */
    public function switchFavoris() {
        Auth::session();
        
        // Vérifier que l'utilisateur est connecté
        if (!isset($_SESSION['id_utilisateur'])) {
            return View::render('error', ['message' => 'Vous devez être connecté pour ajouter aux favoris']);
        }
        
        // Vérifier que l'ID de l'enchère est fourni
        if (!isset($_GET['id_enchere'])) {
            return View::render('error', ['message' => 'ID de l\'enchère manquant']);
        }
        
        $id_utilisateur = $_SESSION['id_utilisateur'];
        $id_enchere = $_GET['id_enchere'];
        
        $favoris = new Favoris();
        
        // Vérifier si l'enchère est déjà en favoris
        if ($favoris->estFavori($id_utilisateur, $id_enchere)) {
            // Supprimer des favoris
            $favoris->supprimerFavori($id_utilisateur, $id_enchere);
        } else {
            // Ajouter aux favoris
            $favoris->ajouterFavori($id_utilisateur, $id_enchere);
        }
        
        // Rediriger vers la page précédente 
        if (isset($_SERVER['HTTP_REFERER'])) {
            header('Location: ' . $_SERVER['HTTP_REFERER']);
            exit;
        } else {
            return View::redirect('enchere');
        }
    }
}
?>