<?php
namespace App\Controllers;

use App\Providers\View;
use App\Providers\Auth;
use App\Models\Enchere;
use App\Models\Favoris;

class EnchereController {

    public function index() {
        $enchere = new Enchere;
        
        // Récupérer tous les paramètres de recherche et filtres
        $recherche = isset($_GET['recherche']) ? trim($_GET['recherche']) : '';
        $annee = isset($_GET['annee']) ? $_GET['annee'] : '';
        $pays = isset($_GET['pays']) ? $_GET['pays'] : '';
        $condition = isset($_GET['condition']) ? $_GET['condition'] : '';
        $couleur = isset($_GET['couleur']) ? $_GET['couleur'] : '';
        
        // Appliquer les filtres et la recherche
            if (!empty($recherche) || !empty($annee) || !empty($pays) || !empty($condition) || !empty($couleur)) {
                // Recherche avec filtres
                $encheres = $enchere->rechercherEncheres($recherche, $annee, $pays, $condition, $couleur);
            } else {
                // Pas de recherche, on récupère toutes les enchères
                $encheres = $enchere->getEncheresWithDetails();
            }
        
        // Récupérer les options pour les filtres
        $optionsFiltres = $enchere->getOptionsFiltres();
        
        // Le temps restant à chaque enchère
        foreach ($encheres as &$uneEnchere) {
            $uneEnchere['temps_restant'] = $enchere->calculerTempsRestant($uneEnchere['date_fin']);
        
            // Déterminer le statut basé sur le temps restant
            $uneEnchere['statut_enchere'] = $uneEnchere['temps_restant']['fini'] ? 'termine' : 'active';

            //Vérifier si l'utilisateur connecté a cette enchère en favori
            $uneEnchere['est_favori'] = false;
            if (isset($_SESSION['id_utilisateur'])) {
                $favoris = new Favoris();
                $uneEnchere['est_favori'] = $favoris->estFavori($_SESSION['id_utilisateur'], $uneEnchere['id_enchere']);
            }
        }
        
        return View::render('enchere/index', [
            'encheres' => $encheres,
            'recherche' => $recherche,
            'annee' => $annee,
            'pays' => $pays,
            'condition' => $condition,
            'couleur' => $couleur,
            'optionsFiltres' => $optionsFiltres
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