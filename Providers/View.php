<?php
namespace App\Providers;

use Twig\Loader\FilesystemLoader;
use Twig\Environment;
use Twig\TwigFilter;

class View {
    static public function render($template, $data = []){
        $loader = new FilesystemLoader('views');
        $twig = new Environment($loader);


    //     // Ajout du filtre personnalisé pour supprimer les accents
    //    $twig->addFilter(new TwigFilter('sans_accents', function ($string) {
    //             // Tableau de remplacement manuel
    //                 $accents = [
    //                     'à' => 'a', 'â' => 'a', 'ä' => 'a',
    //                     'é' => 'e', 'è' => 'e', 'ê' => 'e', 'ë' => 'e',
    //                     'î' => 'i', 'ï' => 'i',
    //                     'ô' => 'o', 'ö' => 'o',
    //                     'ù' => 'u', 'û' => 'u', 'ü' => 'u',
    //                     'ç' => 'c',
    //                     'À' => 'A', 'Â' => 'A', 'Ä' => 'A',
    //                     'É' => 'E', 'È' => 'E', 'Ê' => 'E', 'Ë' => 'E',
    //                     'Î' => 'I', 'Ï' => 'I',
    //                     'Ô' => 'O', 'Ö' => 'O',
    //                     'Ù' => 'U', 'Û' => 'U', 'Ü' => 'U',
    //                     'Ç' => 'C'
    //                 ];
    //                 return strtr($string, $accents);
    //             }));


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