<?php
namespace App\Providers;

class Validator {

    private $errors = array();
    private $key;
    private $value;
    private $name;

    public function field($key, $value, $name = null){
        $this->key = $key;
        $this->value = $value;
        $this->name = $name ?? ucfirst($key);
        return $this;
    }

    public function required(){
        if(empty($this->value)){
            $this->addErrorMessage("$this->name est requis.");
        }
        return $this;
    }

    public function max($length){
        if(strlen($this->value) > $length ){
            $this->addErrorMessage("$this->name doit contenir au maximum $length caractères.");
        }
        return $this;
    }

    public function min($length){
        if(strlen($this->value) < $length ){
            $this->addErrorMessage("$this->name doit contenir au minimum $length caractères.");
        }
        return $this;
    }

    public function numeric(){
        if(!empty($this->value) && !is_numeric($this->value)){
            $this->addErrorMessage("$this->name doit être un nombre.");
        }
        return $this;
    }

    public function positive(){
        if(!empty($this->value) && $this->value < 0){
            $this->addErrorMessage("$this->name ne peut pas être négatif.");
        }
        return $this;
    }

    public function year(){
        if (!empty($this->value)) {
            if (!is_numeric($this->value) || strlen($this->value) != 4 || $this->value < 1000 || $this->value > intval(date('Y'))) {
                $this->addErrorMessage("$this->name doit être une année valide.");
            }
        }
         return $this;
    }

    public function email(){
        if(!empty($this->value) && !filter_var($this->value, FILTER_VALIDATE_EMAIL)){
            $this->addErrorMessage("$this->name doit être une adresse email valide.");
        }
        return $this;
    }

    public function unique($model){
        if(!empty($this->value)){
            $model = 'App\\Models\\'.$model;
            $model = new $model;
            
            // Correction : vérifier si l'email est déjà utilisé
            if($this->key == 'email'){
                $unique = $model->unique('email', $this->value);
            } else {
                $unique = $model->unique($this->key, $this->value);
            }
            
            if($unique){
                $this->addErrorMessage("$this->name existe déjà.");
            }
        }
        return $this;
    }

    public function isSuccess(){
        return empty($this->errors);
    }

    public function getErrors(){
        return $this->isSuccess() ? null : $this->errors;
    }

    public function addError($key, $message){
        $this->errors[$key] = $message;
        return $this;
    }

    // Méthode privée pour éviter la répétition
    private function addErrorMessage($message){
        $this->errors[$this->key] = $message;
    }
}