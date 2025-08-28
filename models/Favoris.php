<?php
namespace App\Models;
use App\Models\CRUD;

class Favoris extends CRUD {
    
    protected $table = "favoris";
    protected $primaryKey = ["id_utilisateur", "id_enchere"];
    
    /**
     * Ajoute une enchère aux favoris d'un utilisateur
     */
    public function ajouterFavori($id_utilisateur, $id_enchere) {
        $sql = "INSERT INTO favoris (id_utilisateur, id_enchere) 
                VALUES (:id_utilisateur, :id_enchere)";
        
        $stmt = $this->prepare($sql);
        $stmt->bindValue(':id_utilisateur', $id_utilisateur);
        $stmt->bindValue(':id_enchere', $id_enchere);
        
        return $stmt->execute();
    }
    
    /**
    * Supprime une enchère des favoris d'un utilisateur.
     */
    public function supprimerFavori($id_utilisateur, $id_enchere) {
        $sql = "DELETE FROM favoris 
                WHERE id_utilisateur = :id_utilisateur 
                AND id_enchere = :id_enchere";
        
        $stmt = $this->prepare($sql);
        $stmt->bindValue(':id_utilisateur', $id_utilisateur);
        $stmt->bindValue(':id_enchere', $id_enchere);
        
        return $stmt->execute();
    }
    
    /**
     * Vérifie si une enchère est dans les favoris d'un utilisateur
     */
    public function estFavori($id_utilisateur, $id_enchere) {
        $sql = "SELECT COUNT(*) as count 
                FROM favoris 
                WHERE id_utilisateur = :id_utilisateur 
                AND id_enchere = :id_enchere";
        
        $stmt = $this->prepare($sql);
        $stmt->bindValue(':id_utilisateur', $id_utilisateur);
        $stmt->bindValue(':id_enchere', $id_enchere);
        $stmt->execute();
        
        $result = $stmt->fetch();
        return $result['count'] > 0;
    }
    
    /**
      * Récupère toutes les enchères favorites d'un utilisateur avec leurs détails.
     */
    public function getFavorisByUser($id_utilisateur) {
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
                FROM favoris f
                JOIN encheres e ON f.id_enchere = e.id_enchere
                LEFT JOIN timbres t ON e.id_timbre = t.id_timbre
                LEFT JOIN pays p ON t.id_pays_origine = p.id_pays
                LEFT JOIN conditions c ON t.id_condition = c.id_condition  
                LEFT JOIN couleurs co ON t.id_couleur = co.id_couleur
                LEFT JOIN utilisateurs u ON t.id_utilisateur_createur = u.id_utilisateur
                WHERE f.id_utilisateur = :id_utilisateur
                ORDER BY f.date_ajout DESC";
        
        $stmt = $this->prepare($sql);
        $stmt->bindValue(':id_utilisateur', $id_utilisateur);
        $stmt->execute();
        
        $encheres = $stmt->fetchAll();
        
         // Calculer le temps restant pour chaque enchère favorite
        $enchereModel = new Enchere();
        foreach ($encheres as &$uneEnchere) {
            $uneEnchere['temps_restant'] = $enchereModel->calculerTempsRestant($uneEnchere['date_fin']);
        }
        
        return $encheres;
    }
}
?>