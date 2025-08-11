<?php
namespace App\Controllers;

use App\Providers\View;
use App\Providers\Auth;

class ProfilController{
    
    public function index(){
    Auth::session();
        return View::render('profil/index');
    }

}