<?php
namespace App\Providers;

use Twig\Loader\FilesystemLoader;
use Twig\Environment;
use Twig\TwigFilter;

class View {
    static public function render($template, $data = []){
        $loader = new FilesystemLoader('views');
        $twig = new Environment($loader);

        // Globals disponibles dans toutes les vues
        $twig->addGlobal('asset', ASSET);
        $twig->addGlobal('base', BASE);

        // Vérifie si l'utilisateur est un invité   
        if(isset($_SESSION['fingerPrint']) AND $_SESSION['fingerPrint'] == md5($_SERVER['HTTP_USER_AGENT'].$_SERVER['REMOTE_ADDR'])){
            $guest = false;
        }else{
            $guest = true;
        }
        $twig->addGlobal('guest', $guest);
        $twig->addGlobal('session', $_SESSION);

        // Rendu de la vue
        echo $twig->render($template.".php", $data);
    }

    static public function redirect($url){
        header('location:'.BASE.'/'.$url);
    }
}



?>