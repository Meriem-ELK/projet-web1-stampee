<?php
namespace App\Models;
use App\Models\CRUD;

class Enchere extends CRUD {
    
    // Configuration de la table
    protected $table = "encheres";
    protected $primaryKey = "id_enchere";

    /**
     * Récupère toutes les enchères avec les détails complets
     */
    public function getEncheresWithDetails() {
        $sql = "SELECT e.*, 
                       t.nom as nom_timbre,
                       t.date_creation,
                       t.certifie,
                       p.nom_pays, 
                       c.nom_condition, 
                       co.nom_couleur,
                       u.nom_utilisateur,
                       (SELECT chemin_image FROM images_timbres WHERE id_timbre = t.id_timbre ORDER BY ordre_affichage LIMIT 1) as premiere_image,
                       (SELECT MAX(montant) FROM mises WHERE id_enchere = e.id_enchere) as mise_actuelle,
                       (SELECT COUNT(*) FROM mises WHERE id_enchere = e.id_enchere) as nombre_mises
                FROM encheres e
                LEFT JOIN timbres t ON e.id_timbre = t.id_timbre
                LEFT JOIN pays p ON t.id_pays_origine = p.id_pays
                LEFT JOIN conditions c ON t.id_condition = c.id_condition  
                LEFT JOIN couleurs co ON t.id_couleur = co.id_couleur
                LEFT JOIN utilisateurs u ON t.id_utilisateur_createur = u.id_utilisateur
                /*WHERE e.date_fin > NOW()*/
                ORDER BY e.date_debut DESC";
        
        $stmt = $this->query($sql);
        if($stmt) {
            return $stmt->fetchAll();
        }
        return [];
    }

    /**
     * Récupère une enchère complète avec tous les détails
     */
    public function getEnchereComplete($id) {
        $sql = "SELECT e.*, 
                       t.nom as nom_timbre,
                       t.date_creation,
                       t.tirage,
                       t.dimensions,
                       t.certifie,
                       t.description,
                       p.nom_pays, 
                       c.nom_condition, 
                       co.nom_couleur,
                       u.nom_utilisateur,
                       u.prenom,
                       u.nom as nom_famille,
                       (SELECT MAX(montant) FROM mises WHERE id_enchere = e.id_enchere) as mise_actuelle,
                       (SELECT COUNT(*) FROM mises WHERE id_enchere = e.id_enchere) as nombre_mises
                FROM encheres e
                LEFT JOIN timbres t ON e.id_timbre = t.id_timbre
                LEFT JOIN pays p ON t.id_pays_origine = p.id_pays
                LEFT JOIN conditions c ON t.id_condition = c.id_condition  
                LEFT JOIN couleurs co ON t.id_couleur = co.id_couleur
                LEFT JOIN utilisateurs u ON t.id_utilisateur_createur = u.id_utilisateur
                WHERE e.id_enchere = :id";
        
        $stmt = $this->prepare($sql);
        $stmt->bindValue(':id', $id);
        $stmt->execute();
        
        if($stmt->rowCount() == 1) {
            return $stmt->fetch();
        }
        return false;
    }

    /**
     * Récupère les images d'un timbre
     */
    public function getImagesTimbre($timbreId) {
        $sql = "SELECT * FROM images_timbres 
                WHERE id_timbre = :id_timbre 
                ORDER BY ordre_affichage";
        
        $stmt = $this->prepare($sql);
        $stmt->bindValue(':id_timbre', $timbreId);
        $stmt->execute();
        
        return $stmt->fetchAll();
    }

    /**
     * Calcule le temps restant avant la fin d'une enchère
     */
    public function calculerTempsRestant($dateFin) {
        $maintenant = new \DateTime();
        $fin = new \DateTime($dateFin);
        
        if ($fin <= $maintenant) {
            return ['fini' => true];
        }
        
        $diff = $maintenant->diff($fin);
        
        return [
            'fini' => false,
            'jours' => $diff->days,
            'heures' => $diff->h,
            'minutes' => $diff->i,
            'texte' => $this->formaterTempsRestant($diff)
        ];
    }

    /**
     * Formate le temps restant en texte lisible
     */
    private function formaterTempsRestant($diff) {
        if ($diff->days > 0) {
            return $diff->days . 'j ' . $diff->h . 'h restantes';
        } elseif ($diff->h > 0) {
            return $diff->h . 'h ' . $diff->i . 'min restantes';
        } else {
            return $diff->i . 'min restantes';
        }
    }


    /**
     * Récupère les enchères "Coups de cœur du Lord"
    */
public function getEncheresCoupeCoeur($limit = 3) {
    $limit = (int)$limit;
    
    $sql = "SELECT e.*, 
                   t.nom as nom_timbre,
                   t.date_creation,
                   t.certifie,
                   p.nom_pays, 
                   c.nom_condition, 
                   co.nom_couleur,
                   u.nom_utilisateur,
                   (SELECT chemin_image FROM images_timbres WHERE id_timbre = t.id_timbre ORDER BY ordre_affichage LIMIT 1) as premiere_image,
                   (SELECT MAX(montant) FROM mises WHERE id_enchere = e.id_enchere) as mise_actuelle,
                   (SELECT COUNT(*) FROM mises WHERE id_enchere = e.id_enchere) as nombre_mises
            FROM encheres e
            LEFT JOIN timbres t ON e.id_timbre = t.id_timbre
            LEFT JOIN pays p ON t.id_pays_origine = p.id_pays
            LEFT JOIN conditions c ON t.id_condition = c.id_condition  
            LEFT JOIN couleurs co ON t.id_couleur = co.id_couleur
            LEFT JOIN utilisateurs u ON t.id_utilisateur_createur = u.id_utilisateur
            WHERE e.coup_coeur_lord = 1 
            AND e.date_fin > NOW()
            ORDER BY e.date_debut DESC
            LIMIT $limit";
    
    $stmt = $this->prepare($sql);
    $stmt->execute();
    
    $encheres = $stmt->fetchAll();
    
    // Le temps restant à chaque enchère
    foreach ($encheres as &$uneEnchere) {
        $uneEnchere['temps_restant'] = $this->calculerTempsRestant($uneEnchere['date_fin']);
    }
    
    return $encheres;
}


/**
 * Recherche des enchères selon plusieurs critères
 */
public function rechercherEncheres($terme = '', $annee = '', $pays = '', $condition = '', $couleur = '') {
    $sql = "SELECT e.*, 
                   t.nom as nom_timbre,
                   t.date_creation,
                   t.certifie,
                   p.nom_pays, 
                   c.nom_condition, 
                   co.nom_couleur,
                   u.nom_utilisateur,
                   (SELECT chemin_image FROM images_timbres WHERE id_timbre = t.id_timbre ORDER BY ordre_affichage LIMIT 1) as premiere_image,
                   (SELECT MAX(montant) FROM mises WHERE id_enchere = e.id_enchere) as mise_actuelle,
                   (SELECT COUNT(*) FROM mises WHERE id_enchere = e.id_enchere) as nombre_mises
            FROM encheres e
            LEFT JOIN timbres t ON e.id_timbre = t.id_timbre
            LEFT JOIN pays p ON t.id_pays_origine = p.id_pays
            LEFT JOIN conditions c ON t.id_condition = c.id_condition  
            LEFT JOIN couleurs co ON t.id_couleur = co.id_couleur
            LEFT JOIN utilisateurs u ON t.id_utilisateur_createur = u.id_utilisateur
            WHERE 1=1";
    
    $params = [];
    
    // Filtre par terme de recherche
    if (!empty($terme)) {
        $sql .= " AND (t.nom LIKE :terme 
                  OR p.nom_pays LIKE :terme 
                  OR t.date_creation LIKE :terme 
                  OR c.nom_condition LIKE :terme
                  OR co.nom_couleur LIKE :terme)";
        $params[':terme'] = '%' . $terme . '%';
    }
    
    // Filtre par année
    if (!empty($annee)) {
        $sql .= " AND t.date_creation = :annee";
        $params[':annee'] = $annee;
    }
    
    // Filtre par pays
    if (!empty($pays)) {
        $sql .= " AND p.id_pays = :pays";
        $params[':pays'] = $pays;
    }
    
    // Filtre par condition
    if (!empty($condition)) {
        $sql .= " AND c.id_condition = :condition";
        $params[':condition'] = $condition;
    }
    
    // Filtre par couleur
    if (!empty($couleur)) {
        $sql .= " AND co.id_couleur = :couleur";
        $params[':couleur'] = $couleur;
    }
    
    $sql .= " ORDER BY e.date_debut DESC";
    
    $stmt = $this->prepare($sql);
    
    foreach ($params as $key => $value) {
        $stmt->bindValue($key, $value);
    }
    
    $stmt->execute();
    return $stmt->fetchAll();
}

/**
 * Récupère les options pour les filtres
 */
public function getOptionsFiltres() {
    $options = [];
    
    // Années disponibles
    $sql = "SELECT DISTINCT date_creation as annee FROM timbres ORDER BY annee DESC";
    $stmt = $this->query($sql);
    $options['annees'] = $stmt ? $stmt->fetchAll() : [];
    
    // Pays
    $sql = "SELECT id_pays, nom_pays FROM pays ORDER BY nom_pays";
    $stmt = $this->query($sql);
    $options['pays'] = $stmt ? $stmt->fetchAll() : [];
    
    // Conditions
    $sql = "SELECT id_condition, nom_condition FROM conditions ORDER BY nom_condition";
    $stmt = $this->query($sql);
    $options['conditions'] = $stmt ? $stmt->fetchAll() : [];
    
    // Couleurs
    $sql = "SELECT id_couleur, nom_couleur FROM couleurs ORDER BY nom_couleur";
    $stmt = $this->query($sql);
    $options['couleurs'] = $stmt ? $stmt->fetchAll() : [];
    
    return $options;
}

}
?>