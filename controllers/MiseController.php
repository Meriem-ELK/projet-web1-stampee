<?php
namespace App\Controllers;

use App\Providers\View;
use App\Providers\Auth;
use App\Providers\Validator;
use App\Models\Mise;
use App\Models\Enchere;
use App\Models\Favoris;

class MiseController {

    /**
     * Placer une nouvelle mise (offre) sur une enchère
    */
    public function store($data) {
        Auth::session();

        $mise = new Mise();
        $enchere = new Enchere();

        // Récupère les données complètes de l'enchère
        $enchereData = $enchere->getEnchereComplete($data['id_enchere']);

        // Si l'enchère n'existe pas, afficher une page d'erreur
        if (!$enchereData) {
            return View::render('error', ['message' => 'Enchère introuvable']);
        }

        // Récupère les images associées au timbre de l'enchère et le Calcule du temps restant pour l'enchere 
        $images = $enchere->getImagesTimbre($enchereData['id_timbre']);
        $tempsRestant = $enchere->calculerTempsRestant($enchereData['date_fin']);
        
        // Vérifier si l'enchère est dans les favoris
        $estFavori = false;
        if (isset($_SESSION['id_utilisateur'])) {
            $favoris = new Favoris ();
            $estFavori = $favoris->estFavori($_SESSION['id_utilisateur'], $data['id_enchere']);
        }

        // Fonction pour rendre la page avec erreurs 
        $renderShowWithErrors = function($errors) use ($enchereData, $images, $tempsRestant, $estFavori) {
            return View::render('enchere/show', [
                'errors' => $errors,
                'enchere' => $enchereData,
                'images' => $images,
                'tempsRestant' => $tempsRestant,
                'estFavori' => $estFavori
            ]);
        };

        // Créer le validateur pour vérifier les données
        $validator = new Validator;
        $validator->field('id_enchere', $data['id_enchere'])->required()->numeric();
        $validator->field('montant', $data['montant'])->required()->numeric()->min(1);

        // Vérifier que l'enchère n'est pas terminée
        if ($tempsRestant['fini']) {
            $validator->addError('montant', 'Cette enchère est terminée');
        }

        // Vérifier que le montant est supérieur à la mise actuelle
        $miseActuelle = $mise->getMiseActuelle($data['id_enchere']);
        $montantMinimum = $miseActuelle ? $miseActuelle + 1 : $enchereData['prix_plancher'];

        if (isset($data['montant']) && $data['montant'] < $montantMinimum) {
            $validator->addError('montant', 'Votre offre doit être supérieure à £' . $montantMinimum);
        }

        // Si la validation échoue
        if (!$validator->isSuccess()) {
            return $renderShowWithErrors($validator->getErrors());
        }
        
        // Préparer les données pour l'insertion
        $dataInsert = [
            'id_enchere' => $data['id_enchere'],
            'id_utilisateur' => $_SESSION['id_utilisateur'],
            'montant' => $data['montant']
        ];

        // Insérer la nouvelle mise
        $nouvelleMise = $mise->insert($dataInsert);

        if ($nouvelleMise) {
            return $renderShowWithErrors(['success' => 'Votre offre a été placée avec succès!']);
        } else {
            return $renderShowWithErrors(['montant' => 'Erreur lors du placement de l\'offre']);
        }
    }


}