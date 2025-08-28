<?php
namespace App\Models;
use App\Models\CRUD;
use Intervention\Image\ImageManager;

class Timbre extends CRUD {

    protected $table = "timbres";
    protected $primaryKey = "id_timbre";
    
    // Champs autorisés pour l'insertion/modification
    protected $fillable = [
        'nom',
        'date_creation',
        'tirage',
        'dimensions',
        'certifie',
        'id_utilisateur_createur',
        'id_pays_origine',
        'id_condition',
        'id_couleur',
        'description'
    ];

    /**
     * Récupère tous les timbres avec les noms des pays, conditions et couleurs
     */
    public function selectWithDetails(){
        $sql = "SELECT t.*, 
                       p.nom_pays, 
                       c.nom_condition, 
                       co.nom_couleur,
                       u.nom_utilisateur
                FROM timbres t
                LEFT JOIN pays p ON t.id_pays_origine = p.id_pays
                LEFT JOIN conditions c ON t.id_condition = c.id_condition  
                LEFT JOIN couleurs co ON t.id_couleur = co.id_couleur
                LEFT JOIN utilisateurs u ON t.id_utilisateur_createur = u.id_utilisateur
                ORDER BY t.id_timbre DESC";
        
        $stmt = $this->query($sql);
        if($stmt){
            return $stmt->fetchAll();
        }
        return false;
    }

    /**
     * Récupère un timbre spécifique avec tous les détails
    */
    public function selectIdWithDetails($id){
        $sql = "SELECT t.*, 
                       p.nom_pays, 
                       c.nom_condition, 
                       co.nom_couleur,
                       u.nom_utilisateur
                FROM timbres t
                LEFT JOIN pays p ON t.id_pays_origine = p.id_pays
                LEFT JOIN conditions c ON t.id_condition = c.id_condition
                LEFT JOIN couleurs co ON t.id_couleur = co.id_couleur
                LEFT JOIN utilisateurs u ON t.id_utilisateur_createur = u.id_utilisateur
                WHERE t.id_timbre = :id";
        
        $stmt = $this->prepare($sql);
        $stmt->bindValue(':id', $id);
        $stmt->execute();
        
        if($stmt->rowCount() == 1){
            return $stmt->fetch();
        }
        return false;
    }

    /**
     * Récupère tous les pays pour les listes déroulantes
     */
    public function getAllPays(){
        $sql = "SELECT * FROM pays ORDER BY nom_pays";
        $stmt = $this->query($sql);
        if($stmt){
            return $stmt->fetchAll();
        }
        return false;
    }

    /**
     * Récupère toutes les couleurs pour les listes déroulantes
     */
    public function getAllCouleurs(){
        $sql = "SELECT * FROM couleurs ORDER BY nom_couleur";
        $stmt = $this->query($sql);
        if($stmt){
            return $stmt->fetchAll();
        }
        return false;
    }

    /**
     * Récupère toutes les conditions pour les listes déroulantes
     */
    public function getAllConditions(){
        $sql = "SELECT * FROM conditions ORDER BY nom_condition";
        $stmt = $this->query($sql);
        if($stmt){
            return $stmt->fetchAll();
        }
        return false;
    }


/**
  * Valide les images avant upload
*/
public function validateImages($files) {
    $errors = [];
    $maxImages = 5;
    $maxSize = 5 * 1024 * 1024; // 5MB
    $allowedTypes = ['image/jpeg', 'image/jpg', 'image/png', 'image/gif', 'image/webp'];
    
    // Vérifier que $_FILES['images'] existe et n'est pas vide
    if (!isset($files) || !isset($files['name']) || empty($files['name'])) {
        $errors[] = "Au moins une image est requise pour le timbre.";
        return $errors;
    }
    
    // Compter les images valides (nom non vide)
    $validImages = array_filter($files['name']);
    $imageCount = count($validImages);
    
    // CRITÈRE : Au moins 1 image
    if ($imageCount === 0) {
        $errors[] = "Au moins une image est requise pour le timbre.";
        return $errors;
    }
    
    // CRITÈRE : Maximum 5 images
    if ($imageCount > $maxImages) {
        $errors[] = "Maximum {$maxImages} images autorisées par timbre.";
    }

    // Vérifier chaque fichier individuellement
        for ($i = 0; $i < count($files['name']); $i++) {
            if (empty($files['name'][$i])) {
                // Ignorer les fichiers vides
            } else {

                // Vérifier la taille
                if ($files['size'][$i] > $maxSize) {
                    $errors[] = "Le fichier '{$files['name'][$i]}' est trop volumineux (max 5MB).";
                }
                
                // Vérifier le type déclaré
                $fileType = strtolower($files['type'][$i]);
                if (!in_array($fileType, $allowedTypes)) {
                    $errors[] = "Le fichier '{$files['name'][$i]}' n'est pas au bon format (JPG, PNG, GIF, WEBP uniquement).";
                }
                
                // Vérifier le vrai type MIME
                if ($files['error'][$i] === UPLOAD_ERR_OK && is_uploaded_file($files['tmp_name'][$i])) {
                    $finfo = finfo_open(FILEINFO_MIME_TYPE);
                    $realMimeType = finfo_file($finfo, $files['tmp_name'][$i]);
                    finfo_close($finfo);
                    
                    if (!in_array($realMimeType, $allowedTypes)) {
                        $errors[] = "Le fichier '{$files['name'][$i]}' n'est pas une vraie image.";
                    }
                } else {
                    $errors[] = "Erreur lors de l'upload du fichier '{$files['name'][$i]}'.";
                }
            }
        }
        
        return $errors;
    }

/**
    * Vérifie si un timbre a des données liées
    * Empêche la suppression si le timbre est utilisé  
*/
public function hasRelatedData($timbreId) {
    // Vérifier s'il y a des enchères pour ce timbre
    $sql = "SELECT COUNT(*) as count FROM encheres WHERE id_timbre = :timbre_id";
    $stmt = $this->prepare($sql);
    if($stmt){
        $stmt->bindValue(':timbre_id', $timbreId);
        if($stmt->execute()){
            $encheres = $stmt->fetch();
            return ($encheres['count'] > 0);
        }
    }
    return false;
}

/**
    * Récupère le dernier ID inséré
*/
    public function getLastInsertId() {
        return $this->lastInsertId();
    }

/**
  * Gère l'upload de PLUSIEURS images pour un timbre
*/

public function uploadImages($files, $timbreId) {
    
    $uploadDir = 'public/assets/img/timbres/';
    
    // Créer le dossier si inexistant
    if (!is_dir($uploadDir)) {
        mkdir($uploadDir, 0777, true);
    }
    
    $uploadedCount = 0;

    // Instance Intervention/Image
    $manager = new ImageManager(\Intervention\Image\Drivers\Gd\Driver::class); 
    
    // Traiter chaque image
    for ($i = 0; $i < count($files['name']); $i++) {
        
        // Ignorer les fichiers vides ou avec erreur
        if (empty($files['name'][$i]) || $files['error'][$i] !== UPLOAD_ERR_OK) {
            continue;
        }
        
        // Générer nom unique et sécurisé
        $extension = strtolower(pathinfo($files['name'][$i], PATHINFO_EXTENSION));
        $newName = 'timbre_' . $timbreId . '_' . time() . '_' . ($i + 1) . '.' . $extension;
        $targetPath = $uploadDir . $newName;
        
        // Déplacer le fichier
        if (move_uploaded_file($files['tmp_name'][$i], $targetPath)) {
            
            // Redimensionner + compresser
            $image = $manager->read($targetPath) 
                ->resize(800, 600, function ($constraint) {
                    $constraint->aspectRatio();
                    $constraint->upsize();
                });

            // compression
            $image->save($targetPath, quality: 90);

            
            // Sauvegarder en base de données
            $sql = "INSERT INTO images_timbres (id_timbre, chemin_image, ordre_affichage) 
                    VALUES (?, ?, ?)";
            $stmt = $this->prepare($sql);
            
            if ($stmt->execute([$timbreId, $newName, $uploadedCount + 1])) {
                $uploadedCount++;
            } else {
                // Si erreur DB, supprimer le fichier uploadé
                if (file_exists($targetPath)) {
                    unlink($targetPath);
                }
            }
        }
    }
    
    // Retourner true si au moins 1 image uploadée avec succès
    return $uploadedCount > 0;
}
 
/**
    * Récupère toutes les images d'un timbre
*/
    public function getTimbreImages($timbreId) {
        $sql = "SELECT * FROM images_timbres 
                WHERE id_timbre = :id_timbre 
                ORDER BY ordre_affichage";
        
        $stmt = $this->prepare($sql);
        $stmt->bindValue(':id_timbre', $timbreId);
        $stmt->execute();
        
        return $stmt->fetchAll();
    }

/**
    * Supprime toutes les images d'un timbre 
*/

public function deleteTimbreImages($timbreId) {

        $uploadDir = 'public/assets/img/timbres/';

        // D'abord récupérer les chemins des images pour les supprimer du serveur
        $images = $this->getTimbreImages($timbreId);
        
         foreach ($images as $image) {
                $filePath = $uploadDir . $image['chemin_image'];
                if (file_exists($filePath)) {
                unlink($filePath); // Supprimer le fichier du serveur
                }
        }
        
        // Ensuite supprimer les entrées de la base de données
        $sql = "DELETE FROM images_timbres WHERE id_timbre = :id_timbre";
        $stmt = $this->prepare($sql);
        $stmt->bindValue(':id_timbre', $timbreId);
        
        return $stmt->execute();
    }


/**   
    * Récupère tous les timbres créés par un utilisateur spécifique
*/
    public function getTimbresByUser($userId){
        $sql = "SELECT t.*, 
                       p.nom_pays, 
                       c.nom_condition, 
                       co.nom_couleur,
                       (SELECT chemin_image FROM images_timbres WHERE id_timbre = t.id_timbre ORDER BY ordre_affichage LIMIT 1) as premiere_image
                FROM timbres t
                LEFT JOIN pays p ON t.id_pays_origine = p.id_pays
                LEFT JOIN conditions c ON t.id_condition = c.id_condition  
                LEFT JOIN couleurs co ON t.id_couleur = co.id_couleur
                WHERE t.id_utilisateur_createur = :user_id
                ORDER BY t.id_timbre DESC";
        
        $stmt = $this->prepare($sql);
        if($stmt){
            $stmt->bindValue(':user_id', $userId);
            if($stmt->execute()){
                return $stmt->fetchAll();
            }
        }
        return false;
    }

/**
 * Supprime une image spécifique par son ID
 */
public function deleteSpecificImage($imageId) {
    $uploadDir = 'public/assets/img/timbres/';
    
    // D'abord récupérer les informations de l'image
    $sql = "SELECT chemin_image FROM images_timbres WHERE id_image = :id_image";
    $stmt = $this->prepare($sql);
    $stmt->bindValue(':id_image', $imageId);
    $stmt->execute();
    
    $image = $stmt->fetch();
    if (!$image) {
        return false; // Image non trouvée en base
    }
    
    // Supprimer le fichier physique du serveur
    $filePath = $uploadDir . $image['chemin_image'];
    if (file_exists($filePath)) {
        unlink($filePath);
    }
    
    // Supprimer l'entrée de la base de données
    $sql = "DELETE FROM images_timbres WHERE id_image = :id_image";
    $stmt = $this->prepare($sql);
    $stmt->bindValue(':id_image', $imageId);
    
    return $stmt->execute();
}



}

?>