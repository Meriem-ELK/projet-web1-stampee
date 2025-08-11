<?php
namespace App\Controllers;
use App\Providers\View;


/**
 * Gère la page d'accueil
*/
class HomeController {

    public function index() {
  
        return View::render('home/index');
    }
}