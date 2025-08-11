<?php
namespace App\Providers;

use App\Providers\View;

class Auth {
    static public function session(){
        if(isset($_SESSION['fingerPrint']) AND $_SESSION['fingerPrint'] == md5($_SERVER['HTTP_USER_AGENT'].$_SERVER['REMOTE_ADDR'])){
            return true;
        } else {
            $_SESSION['error'] = 'Vous devez être connecté pour accéder à cette page.';
            return view::redirect('login');
            exit();
        }
    }
    
}

?>