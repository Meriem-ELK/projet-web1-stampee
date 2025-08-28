<?php
namespace App\Controllers;

use App\Providers\View;
use App\Providers\Validator;
use App\Providers\Auth;
use App\Models\Timbre;

class TimbreController {

/*------------------------------- CREATE - Afficher le formulaire de création */
    public function create() {
        Auth::session(); 
        return View::render('timbre/create', $this->getDropdownData());
    }

/*------------------------------- STORE - Traiter la soumission du formulaire */
    public function store($data) {
        Auth::session();
        $timbre = new Timbre;

        $renderCreateForm = function($errors) use ($data) {
            return View::render('timbre/create', array_merge(
                ['errors' => $errors, 'timbre' => $data],
                $this->getDropdownData()
            ));
        };

        // Validation des données
        $validator = new Validator;
        $validator->field('nom', $data['nom'], 'Le nom du timbre')->required()->min(2)->max(100);
        $validator->field('id_pays_origine', $data['id_pays_origine'], 'Le pays d\'origine')->required();
        $validator->field('date_creation', $data['date_creation'], 'L\'année de création')->required()->numeric()->year();
        $validator->field('id_couleur', $data['id_couleur'], 'La couleur')->required();
        $validator->field('id_condition', $data['id_condition'], 'La condition')->required();

        if (!empty($data['tirage'])) {
            $validator->field('tirage', $data['tirage'])->numeric()->positive();
        }
        if (!empty($data['dimensions'])) {
            $validator->field('dimensions', $data['dimensions'])->min(2)->max(50);
        }

        // Nettoyer les champs optionnels : convertir '' en null
        $data['tirage'] = $data['tirage'] !== '' ? (int)$data['tirage'] : null;
        $data['dimensions'] = $data['dimensions'] !== '' ? $data['dimensions'] : null;

        // Validation des images
        if (!empty($_FILES['images']) && isset($_FILES['images']['name'])) {
            $imageErrors = $timbre->validateImages($_FILES['images']);
            foreach ($imageErrors as $error) {
                $validator->addError('images', $error);
            }
        }

        if (!$validator->isSuccess()) {
            return $renderCreateForm($validator->getErrors());
        }

        $data['id_utilisateur_createur'] = $_SESSION['id_utilisateur'];
        $insert = $timbre->insert($data);

        if (!$insert) {
            return $renderCreateForm(['message' => "Erreur lors de la création du timbre."]);
        }

        $timbreId = $timbre->getLastInsertId();
        if (!$timbre->uploadImages($_FILES['images'], $timbreId)) {
            $timbre->delete($timbreId);
            return $renderCreateForm(['images' => "Erreur lors de l'upload des images. Le timbre n'a pas été créé."]);
        }

        return View::redirect('profil');
    }

/*------------------------------- SHOW - Afficher un timbre spécifique */
    public function show($data) {
        Auth::session();

        if (!isset($data['id'])) {
            return View::render('error', ['message' => 'ID du timbre manquant']);
        }

        $timbre = new Timbre;
        $timbreData = $timbre->selectIdWithDetails($data['id']);

        if (!$timbreData) {
            return View::render('error', ['message' => 'Timbre non trouvé']);
        }

        // Vérification des permissions  
        if ($timbreData['id_utilisateur_createur'] != $_SESSION['id_utilisateur']) {
            return View::render('error', ['message' => "Vous n'avez pas l'autorisation de voir ce timbre."]);
        }
        // Récupérer toutes les images du timbre
        $images = $timbre->getTimbreImages($data['id']);

        return View::render('timbre/show', [
            'timbre' => $timbreData,
            'images' => $images
        ]);
    }

/*------------------------------- EDIT - Afficher le formulaire de modification */
    public function edit($data) {
        Auth::session();

        if (!isset($data['id'])) {
            return View::render('error', ['message' => 'ID du timbre manquant']);
        }

        $timbre = new Timbre;
        $timbreData = $timbre->selectId($data['id']);

        if (!$timbreData) {
            return View::render('error', ['message' => 'Timbre non trouvé']);
        }
        // Vérification des permissions
        if ($timbreData['id_utilisateur_createur'] != $_SESSION['id_utilisateur']) {
            return View::render('error', [
                'message' => "Vous n'avez pas l'autorisation de modifier ce timbre."
            ]);
        }
        // Récupérer les images existantes
        $images = $timbre->getTimbreImages($data['id']);

        return View::render('timbre/edit', array_merge(
            [
                'timbre' => $timbreData,
                'images' => $images
            ],
            $this->getDropdownData()
        ));
    }

/*------------------------------- UPDATE - Traiter la modification */
public function update($data, $get = null) {
    Auth::session();

    $get = $get ?? $_GET;
    $id = $get['id'] ?? null;

    $timbre = new Timbre;
    $timbreExistant = $timbre->selectId($id);

    if (!$timbreExistant) {
        return View::render('error');
    }

    if ($timbreExistant['id_utilisateur_createur'] != $_SESSION['id_utilisateur']) {
        $errors = ['message' => "Vous n'avez pas l'autorisation de modifier ce timbre."];
        return View::render('timbre/show', ['timbre' => $timbreExistant, 'errors' => $errors]);
    }
    
    // Fonction pour rendre le formulaire avec erreurs
    $renderEditForm = function($errors) use ($data, $id) {
        $timbre = new Timbre;
        $images = $timbre->getTimbreImages($id); // Récupérer les images actuelles
        return View::render('timbre/edit', array_merge(
            ['errors' => $errors, 'timbre' => $data, 'images' => $images],
            $this->getDropdownData()
        ));
    };

    // Validation des données de base
    $validator = new Validator;
    $validator->field('nom', $data['nom'], 'Le nom du timbre')->required()->min(2)->max(100);
    $validator->field('id_pays_origine', $data['id_pays_origine'], 'Le pays d\'origine')->required();
    $validator->field('date_creation', $data['date_creation'], 'L\'année de création')->required()->numeric()->year();
    $validator->field('id_couleur', $data['id_couleur'], 'La couleur')->required();
    $validator->field('id_condition', $data['id_condition'], 'La condition')->required();

    if (!empty($data['tirage'])) {
        $validator->field('tirage', $data['tirage'])->numeric()->positive();
    }
    if (!empty($data['dimensions'])) {
        $validator->field('dimensions', $data['dimensions'])->min(2)->max(50);
    }

    // Gestion des images
    $imagesExistantes = $timbre->getTimbreImages($id);
    $nbImagesExistantes = count($imagesExistantes);
    
    // Vérifier si l'utilisateur veut supprimer des images
    $imagesToDelete = [];
    if (!empty($data['images_to_delete'])) {
        $imagesToDelete = array_filter(explode(',', $data['images_to_delete']));
        // Calculer combien d'images resteront après suppression
        $nbImagesRestantes = $nbImagesExistantes - count($imagesToDelete);
    } else {
        $nbImagesRestantes = $nbImagesExistantes;
    }
    
    // Vérifier si de nouvelles images sont uploadées
    $hasNewImages = !empty($_FILES['images']) && !empty($_FILES['images']['name'][0]);
    $newImagesCount = 0;
    
    if ($hasNewImages) {
        // Valider les nouvelles images
        $imageErrors = $timbre->validateImages($_FILES['images']);
        foreach ($imageErrors as $error) {
            $validator->addError('images', $error);
        }
        
        // Compter les nouvelles images valides
        $newImagesCount = count(array_filter($_FILES['images']['name']));
        
        // Vérifier que le total ne dépasse pas 5
        $totalFinal = $nbImagesRestantes + $newImagesCount;
        if ($totalFinal > 5) {
            $validator->addError('images', "Total d'images après modification: {$totalFinal}. Maximum 5 images autorisées.");
        }
    }
    
    // Vérifier qu'il y aura au moins 1 image après modifications
    $totalFinal = $nbImagesRestantes + $newImagesCount;
    if ($totalFinal < 1) {
        $validator->addError('images', "Au moins 1 image est requise.");
    }

    // Si erreurs de validation, retourner le formulaire
    if (!$validator->isSuccess()) {
        return $renderEditForm($validator->getErrors());
    }

    // Nettoyer les champs optionnels : convertir '' en null
    $data['tirage'] = $data['tirage'] !== '' ? (int)$data['tirage'] : null;
    $data['dimensions'] = $data['dimensions'] !== '' ? $data['dimensions'] : null;

    // === TRAITEMENT DES IMAGES ===
    // 1. Supprimer les images sélectionnées
    if (!empty($imagesToDelete)) {
        foreach ($imagesToDelete as $imageId) {
            if (!$timbre->deleteSpecificImage($imageId)) {
                return $renderEditForm(['images' => "Erreur lors de la suppression d'une image."]);
            }
        }
    }
    
    // 2. Ajouter les nouvelles images
    if ($hasNewImages) {
        if (!$timbre->uploadImages($_FILES['images'], $id)) {
            return $renderEditForm(['images' => "Erreur lors de l'upload des nouvelles images."]);
        }
    }
    
    // 3. Mettre à jour les informations du timbre
    if ($timbre->update($data, $id)) {
        return View::redirect('timbre/show?id=' . $id);
    } else {
        return $renderEditForm(['message' => "Erreur lors de la modification du timbre."]);
    }
}

/*------------------------------- DELETE - Supprimer un timbre */
    public function delete($data) {
        Auth::session();

        $id = $data['id'] ?? null;

        if (!$id) {
            return View::render('error', ['message' => 'ID du timbre manquant']);
        }

        $timbre = new Timbre;
        $timbreData = $timbre->selectId($id);

        if (!$timbreData) {
            return View::render('error', ['message' => 'Timbre non trouvé']);
        }

        if ($timbreData['id_utilisateur_createur'] != $_SESSION['id_utilisateur']) {
            $errors = ['message' => "Vous n'avez pas l'autorisation de supprimer ce timbre."];
            return View::render('timbre/show', ['timbre' => $timbreData, 'errors' => $errors]);
        }

        // Vérifier si le timbre a des enchères en cours
        if ($timbre->hasRelatedData($id)) {
            // Stocker l'erreur en session pour l'afficher sur le profil
            $_SESSION['profil_errors'] = ['general' => 'Impossible de supprimer ce timbre car il a une enchère en cours.'];
            return View::redirect('profil');
        }

        $timbre->deleteTimbreImages($id);

        if ($timbre->delete($id)) {
        // Stocker le message de succès en session
        $_SESSION['profil_errors'] = ['success' => 'Timbre supprimé avec succès.'];
        return View::redirect('profil');
        } else {
        // Stocker l'erreur en session
        $_SESSION['profil_errors'] = ['general' => 'Erreur lors de la suppression du timbre.'];
        return View::redirect('profil');
        }
    }

/*------------------------------- Récupération des listes déroulantes */
    private function getDropdownData() {
        $timbre = new Timbre;
        return [
            'pays' => $timbre->getAllPays(),
            'couleurs' => $timbre->getAllCouleurs(),
            'conditions' => $timbre->getAllConditions()
        ];
    }
}
?>