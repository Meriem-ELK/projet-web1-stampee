<?php
namespace App\Controllers;
use App\Providers\View;
use App\Models\Enchere;

/**
 * Gère la page d'accueil
*/
class HomeController {

    public function index() {
        
        $enchere = new Enchere();
        
        // Récupérer les enchères "Coups de cœur du Lord" (limite à 3 pour l'accueil)
        $encheresVedettes = $enchere->getEncheresCoupeCoeur(3);
        
        return View::render('home/index', [
            'encheresVedettes' => $encheresVedettes
        ]);
    }
}
?>