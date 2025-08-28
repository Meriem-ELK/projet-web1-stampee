<?php
namespace App\Models;
use App\Models\CRUD;

class Mise extends CRUD {
    
    // Configuration de la table
    protected $table = "mises";
    protected $primaryKey = "id_mise";
    
    // Champs autorisés pour l'insertion/modification
    protected $fillable = [
        'id_enchere',
        'id_utilisateur', 
        'montant'
    ];

    /**
     * Récupère la mise actuelle (la plus élevée) d'une enchère
     */
    public function getMiseActuelle($id_enchere) {
        $sql = "SELECT MAX(montant) as mise_actuelle 
                FROM mises 
                WHERE id_enchere = :id_enchere";
        
        $stmt = $this->prepare($sql);
        $stmt->bindValue(':id_enchere', $id_enchere);
        $stmt->execute();
        
        $result = $stmt->fetch();
        return $result ? $result['mise_actuelle'] : null;
    }

    /**
     * Récupère toutes les mises d'un utilisateur avec les détails des enchères
     */
    public function getMisesByUser($id_utilisateur) {
        $sql = "SELECT m.*,
                       e.prix_plancher,
                       e.date_debut,
                       e.date_fin,
                       e.coup_coeur_lord,
                       t.nom as nom_timbre,
                       p.nom_pays,
                       c.nom_condition,
                       co.nom_couleur,
                       (SELECT chemin_image FROM images_timbres WHERE id_timbre = t.id_timbre ORDER BY ordre_affichage LIMIT 1) as premiere_image,
                       (SELECT MAX(m2.montant) FROM mises m2 WHERE m2.id_enchere = e.id_enchere) as mise_actuelle,
                       (SELECT COUNT(*) FROM mises m3 WHERE m3.id_enchere = e.id_enchere) as nombre_mises
                FROM mises m
                LEFT JOIN encheres e ON m.id_enchere = e.id_enchere
                LEFT JOIN timbres t ON e.id_timbre = t.id_timbre
                LEFT JOIN pays p ON t.id_pays_origine = p.id_pays
                LEFT JOIN conditions c ON t.id_condition = c.id_condition  
                LEFT JOIN couleurs co ON t.id_couleur = co.id_couleur
                WHERE m.id_utilisateur = :id_utilisateur
                ORDER BY m.date_mise DESC";
        
        $stmt = $this->prepare($sql);
        $stmt->bindValue(':id_utilisateur', $id_utilisateur);
        $stmt->execute();
        
        if ($stmt) {
            $mises = $stmt->fetchAll();
            
            // Calculer le temps restant et le statut pour chaque mise
            foreach ($mises as &$mise) {
                // Calculer le temps restant
                $maintenant = new \DateTime();
                $fin = new \DateTime($mise['date_fin']);
                
                if ($fin <= $maintenant) {
                    $mise['temps_restant'] = ['fini' => true];
                    $mise['statut_enchere'] = 'Terminée';
                } else {
                    $diff = $maintenant->diff($fin);
                    $mise['temps_restant'] = [
                        'fini' => false,
                        'jours' => $diff->days,
                        'heures' => $diff->h,
                        'minutes' => $diff->i,
                        'texte' => $this->formaterTempsRestant($diff)
                    ];
                    $mise['statut_enchere'] = 'En cours';
                }
                
                // Déterminer si l'utilisateur est en tête
                $mise['en_tete'] = ($mise['montant'] == $mise['mise_actuelle']);
            }
            
            return $mises;
        }
        return [];
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
     * Récupère les enchères où l'utilisateur est actuellement en tête
     */
    public function getEncheresEnTete($id_utilisateur) {
        $sql = "SELECT DISTINCT e.id_enchere, 
                       e.prix_plancher,
                       e.date_debut,
                       e.date_fin,
                       e.coup_coeur_lord,
                       t.nom as nom_timbre,
                       p.nom_pays,
                       (SELECT chemin_image FROM images_timbres WHERE id_timbre = t.id_timbre ORDER BY ordre_affichage LIMIT 1) as premiere_image,
                       (SELECT MAX(m2.montant) FROM mises m2 WHERE m2.id_enchere = e.id_enchere) as mise_actuelle
                FROM mises m
                LEFT JOIN encheres e ON m.id_enchere = e.id_enchere
                LEFT JOIN timbres t ON e.id_timbre = t.id_timbre
                LEFT JOIN pays p ON t.id_pays_origine = p.id_pays
                WHERE m.id_utilisateur = :id_utilisateur
                AND m.montant = (SELECT MAX(m3.montant) FROM mises m3 WHERE m3.id_enchere = e.id_enchere)
                AND e.date_fin > NOW()
                ORDER BY e.date_fin ASC";
        
        $stmt = $this->prepare($sql);
        $stmt->bindValue(':id_utilisateur', $id_utilisateur);
        $stmt->execute();
        
        return $stmt->fetchAll();
    }


    /**
     * Récupère les enchères gagnées par un utilisateur
    */
    public function getEncheresGagnees($id_utilisateur) {
        $sql = "SELECT DISTINCT e.id_enchere, 
                   e.prix_plancher,
                   e.date_debut,
                   e.date_fin,
                   e.coup_coeur_lord,
                   t.nom as nom_timbre,
                   p.nom_pays,
                   (SELECT chemin_image FROM images_timbres WHERE id_timbre = t.id_timbre ORDER BY ordre_affichage LIMIT 1) as premiere_image,
                   (SELECT MAX(m2.montant) FROM mises m2 WHERE m2.id_enchere = e.id_enchere) as mise_actuelle
            FROM mises m
            LEFT JOIN encheres e ON m.id_enchere = e.id_enchere
            LEFT JOIN timbres t ON e.id_timbre = t.id_timbre
            LEFT JOIN pays p ON t.id_pays_origine = p.id_pays
            WHERE m.id_utilisateur = :id_utilisateur
            AND m.montant = (SELECT MAX(m3.montant) FROM mises m3 WHERE m3.id_enchere = e.id_enchere)
            AND e.date_fin < NOW()
            ORDER BY e.date_fin DESC";
    
        $stmt = $this->prepare($sql);
        $stmt->bindValue(':id_utilisateur', $id_utilisateur);
        $stmt->execute();
        
        return $stmt->fetchAll();
    }
}