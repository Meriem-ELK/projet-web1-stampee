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
            return View::render('timbre/edit', array_merge(
                ['errors' => $errors, 'timbre' => $data],
                $this->getDropdownData()
            ));
        };

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

        // On récupère les images existantes
        $imagesExistantes = $timbre->getTimbreImages($id);
        $nbImagesExistantes = count($imagesExistantes);

        // Validation des images si des fichiers sont uploadés
        $hasNewImages = !empty($_FILES['images']) && !empty($_FILES['images']['name'][0]);

        if ($hasNewImages) {
            $imageErrors = $timbre->validateImages($_FILES['images']);
            foreach ($imageErrors as $error) {
                $validator->addError('images', $error);
            }
        }

        // Gestion des actions sur les images
        if (!empty($data['image_action'])) {
            if ($data['image_action'] == 'add' && $hasNewImages) {
                // Vérifier le total d'images après ajout
                $newImagesCount = count(array_filter($_FILES['images']['name']));
                $total = $nbImagesExistantes + $newImagesCount;
                if ($total > 5) {
                    $validator->addError('images', "Vous ne pouvez pas avoir plus de 5 images au total. Actuellement: {$nbImagesExistantes}, nouvelles: {$newImagesCount}");
                }
            } elseif ($data['image_action'] == 'replace' && $hasNewImages) {
                // Vérifier le nombre de nouvelles images
                $newImagesCount = count(array_filter($_FILES['images']['name']));
                if ($newImagesCount > 5) {
                    $validator->addError('images', "Vous ne pouvez pas uploader plus de 5 images.");
                }
            } elseif (($data['image_action'] == 'add' || $data['image_action'] == 'replace') && !$hasNewImages) {
                $validator->addError('images', "Veuillez sélectionner des images à uploader.");
            }
        }

        if (!$validator->isSuccess()) {
            return $renderEditForm($validator->getErrors());
        }

        // Nettoyer les champs optionnels : convertir '' en null
        $data['tirage'] = $data['tirage'] !== '' ? (int)$data['tirage'] : null;
        $data['dimensions'] = $data['dimensions'] !== '' ? $data['dimensions'] : null;

        if (!empty($data['image_action']) && $hasNewImages) {
            if ($data['image_action'] == 'add') {
                // Ajouter de nouvelles images
                if (!$timbre->uploadImages($_FILES['images'], $id)) {
                    return $renderEditForm(['images' => "Erreur lors de l'upload des nouvelles images."]);
                }
            } elseif ($data['image_action'] == 'replace') {
                // Supprimer toutes les images existantes puis ajouter les nouvelles
                $timbre->deleteTimbreImages($id);
                if (!$timbre->uploadImages($_FILES['images'], $id)) {
                    return $renderEditForm(['images' => "Erreur lors de l'upload des nouvelles images."]);
                }
            }
        }
        
        // Mise à jour des informations du timbre
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

        if ($timbre->hasRelatedData($id)) {
            $errors = ['message' => "Impossible de supprimer ce timbre car il est utilisé dans des enchères."];
            return View::render('timbre/show', ['timbre' => $timbreData, 'errors' => $errors]);
        }

        $timbre->deleteTimbreImages($id);
        if ($timbre->delete($id)) {
            return View::redirect('profil');
        } else {
            $errors = ['message' => "Erreur lors de la suppression du timbre."];
            return View::render('timbre/show', ['timbre' => $timbreData, 'errors' => $errors]);
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