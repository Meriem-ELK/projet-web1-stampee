<?php
namespace App\Models;
use App\Models\CRUD;

/**
 * Modèle Enchere pour la gestion des enchères de timbres
 */
class Enchere extends CRUD {
    protected $table = "encheres";
    protected $primaryKey = "id_enchere";
    protected $fillable = [
        'id_timbre',
        'prix_plancher',
        'date_debut',
        'date_fin',
        'coup_coeur_lord'
    ];

}