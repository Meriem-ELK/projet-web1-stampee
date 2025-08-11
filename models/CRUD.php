<?php
namespace App\Models;

/**
 * Classe abstraite CRUD
 * Toutes les autres classes de modèles hériteront de cette classe
 */
abstract class CRUD extends \PDO {

    /**
     * Constructeur final - ne peut pas être redéfini dans les classes enfant
     * Se connecte automatiquement à la base de données MySQL
     */
    final public function __construct(){
        parent::__construct('mysql:host=localhost;dbname=stampee_base;port=3306;charset=utf8', 'root', '');
    }

    /**
     * Sélectionne tous les enregistrements d'une table
     */
    final public function select($field = null, $order = 'asc'){
        // Si aucun champ spécifié, utilise la clé primaire
        if($field == null){
            $field = $this->primaryKey;
        }
        
        // Construction de la requête SQL
        $sql = "SELECT * FROM $this->table ORDER BY $field $order";
        if($stmt = $this->query($sql)){
            return $stmt->fetchAll();
        }else{
            return false;
        }
    }

    /**
     * Sélectionne un enregistrement par son ID
     */
    final public function selectId($value){
        // Requête préparée pour éviter les injections SQL
        $sql = "SELECT * FROM $this->table WHERE $this->primaryKey = :$this->primaryKey";
        $stmt = $this->prepare($sql);
        $stmt->bindValue(":$this->primaryKey", $value);
        $stmt->execute();
        
        // Vérification qu'un seul résultat est trouvé
        $count = $stmt->rowCount();
        if ($count == 1){
            return $stmt->fetch();
        }else{
            return false;
        } 
    }

    /**
     * Insère un nouvel enregistrement
     */
    final public function insert($data){
        // Filtre les données selon les champs autorisés ($fillable)
        $data_keys = array_fill_keys($this->fillable, '');
        $data = array_intersect_key($data, $data_keys);
 
        // Construction dynamique de la requête INSERT
        $fieldName = implode(', ', array_keys($data));
        $fieldValue = ":".implode(', :', array_keys($data));
        $sql = "INSERT INTO $this->table ($fieldName) VALUES ($fieldValue)";

        // Préparation et exécution
        $stmt = $this->prepare($sql);
        foreach($data as $key => $value){
            $stmt->bindValue(":$key", $value);
        }
        
        if($stmt->execute()){
            return $this->lastInsertId(); // Retourne l'ID généré
        }else{
            return false;
        } 
    }

    /**
     * Met à jour un enregistrement existant
     */
    final public function update($data, $id){
        // Filtre les données selon les champs autorisés
        $data_keys = array_fill_keys($this->fillable, '');
        $data = array_intersect_key($data, $data_keys);

        // Construction dynamique de la clause SET
        $fieldName = null;
        foreach($data as $key => $value){
            $fieldName .= "$key = :$key, ";
        }
        $fieldName = rtrim($fieldName, ', ');
        
        // Requête UPDATE complète
        $sql = "UPDATE $this->table SET $fieldName WHERE $this->primaryKey = :$this->primaryKey";
        
        // Ajout de l'ID aux données pour le binding
        $data[$this->primaryKey] = $id;

        // Exécution
        $stmt = $this->prepare($sql);
        foreach($data as $key => $value){
            $stmt->bindValue(":$key", $value);
        }
        
         if($stmt->execute()){
            return true;
        }else{
            return false;
        }
    }

    /**
     * Supprime un enregistrement
     * @param mixed $value - ID de l'enregistrement à supprimer
     * @return bool - true si succès, false sinon
     */
    final public function delete($value){
        $sql = "DELETE FROM $this->table WHERE $this->primaryKey = :$this->primaryKey";
        $stmt = $this->prepare($sql);
        $stmt->bindValue(":$this->primaryKey", $value);
        
         if($stmt->execute()){
            return true;
        }else{
            return false;
        }
    }

     /**
     * Vérifie l'unicité d'un champ
     */
     public function unique($field, $value){
        $sql = "SELECT * FROM $this->table WHERE $field = :$field";
        $stmt = $this->prepare($sql);
        $stmt->bindValue(":$field", $value);
        $stmt->execute();
        $count = $stmt->rowCount();
        if($count == 1){
            return $stmt->fetch();
        }else{
            return false;
        }

    }
}

?>