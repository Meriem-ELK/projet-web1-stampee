<?php
namespace App\Controllers;
use App\Providers\View;
use App\Models\Enchere;
use App\Models\Favoris;

/**
 * Gère la page d'accueil
*/
class HomeController {

    public function index() {
        
        $enchere = new Enchere(); 
        // Récupérer les enchères "Coups de cœur du Lord" (limite à 3 pour l'accueil)
        $encheresVedettes = $enchere->getEncheresCoupeCoeur(3);



        // Initialiser toutes les enchères comme non favorites par défaut
            foreach ($encheresVedettes as &$uneEnchere) {
                $uneEnchere['est_favori'] = false;
            }

        // Si l'utilisateur est connecté, mettre à jour l'état des favoris
            if (isset($_SESSION['id_utilisateur'])) {
                $favoris = new Favoris();
                $id_utilisateur = $_SESSION['id_utilisateur'];
                
                foreach ($encheresVedettes as &$uneEnchere) {
                    $uneEnchere['est_favori'] = $favoris->estFavori($id_utilisateur, $uneEnchere['id_enchere']);
                }
            }

        return View::render('home/index', [
            'encheresVedettes' => $encheresVedettes
        ]);
    }
}
?>