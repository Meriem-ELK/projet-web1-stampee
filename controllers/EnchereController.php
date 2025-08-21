<?php
namespace App\Controllers;

use App\Providers\View;
use App\Providers\Auth;
use App\Models\Enchere;
use App\Models\Favoris;

class EnchereController {

    public function index() {

        $enchere = new Enchere;
        $encheres = $enchere->getEncheresWithDetails();
        
       // Le temps restant à chaque enchère
        foreach ($encheres as &$uneEnchere) {
            $uneEnchere['temps_restant'] = $enchere->calculerTempsRestant($uneEnchere['date_fin']);
        

            //Vérifier si l'utilisateur connecté a cette enchère en favori
            $uneEnchere['est_favori'] = false;
            if (isset($_SESSION['id_utilisateur'])) {
                $favoris = new Favoris();
                $uneEnchere['est_favori'] = $favoris->estFavori($_SESSION['id_utilisateur'], $uneEnchere['id_enchere']);
            }
        }
        

        return View::render('enchere/index', [
            'encheres' => $encheres
        ]);
    }

    public function show($data) {

        if (!isset($data['id'])) {
            return View::render('error', ['message' => 'ID de l\'enchère manquant']);
        }

        $enchere = new Enchere;
        $enchereData = $enchere->getEnchereComplete($data['id']);

        if (!$enchereData) {
            return View::render('error', ['message' => 'Enchère non trouvée']);
        }

        $images = $enchere->getImagesTimbre($enchereData['id_timbre']);
        $tempsRestant = $enchere->calculerTempsRestant($enchereData['date_fin']);

        // Vérifier si l'utilisateur est connecté et si cette enchère est dans ses favoris
            $estFavori = false;
            if (isset($_SESSION['id_utilisateur'])) {
                $favoris = new Favoris();
                $estFavori = $favoris->estFavori($_SESSION['id_utilisateur'], $data['id']);
            }

        return View::render('enchere/show', [
            'enchere' => $enchereData,
            'images' => $images,
            'tempsRestant' => $tempsRestant,
            'estFavori' => $estFavori
        ]);
    }
}
?>