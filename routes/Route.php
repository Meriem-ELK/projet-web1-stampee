<?php
namespace App\Routes;

use App\Providers\View;

class Route {
    private static $routes = [];

    public static function get($url, $controller){
        self::$routes[] = ['url' => $url, 'controller' => $controller, 'method' => 'GET'];
    }

    public static function post($url, $controller){
        self::$routes[] = ['url' => $url, 'controller' => $controller, 'method' => 'POST'];
    }

    public static function dispatch(){
        $url = $_SERVER['REQUEST_URI'];
        $method = $_SERVER['REQUEST_METHOD'];
        
        // Séparer l'URL des paramètres GET
        $urlSegments = explode('?', $url);
        $urlPath = rtrim($urlSegments[0], '/');
        
        // Supprimer BASE de l'URL pour obtenir la route relative
        $basePath = rtrim(BASE, '/');
        if (strpos($urlPath, $basePath) === 0) {
            $relativePath = substr($urlPath, strlen($basePath));
        } else {
            $relativePath = $urlPath;
        }
        
        // Si la route relative est vide, c'est la racine
        if (empty($relativePath)) {
            $relativePath = '/';
        }

        foreach(self::$routes as $route){
            if($route['url'] == $relativePath && $route['method'] == $method){
                $controllerSegments = explode('@', $route['controller']);

                $controllerName = 'App\\Controllers\\'.$controllerSegments[0];
                $methodName = $controllerSegments[1];
                
                // Vérifier si la classe du contrôleur existe
                if (!class_exists($controllerName)) {
                    http_response_code(500);
                    echo "Erreur: Le contrôleur $controllerName n'existe pas";
                    return;
                }
                
                $controllerInstance = new $controllerName;
                
                // Vérifier si la méthode existe
                if (!method_exists($controllerInstance, $methodName)) {
                    http_response_code(500);
                    echo "Erreur: La méthode $methodName n'existe pas dans $controllerName";
                    return;
                }

                if($method == 'GET'){
                    if(isset($urlSegments[1])){
                        parse_str($urlSegments[1], $queryParams);
                        $controllerInstance->$methodName($queryParams);
                    } else {
                        $controllerInstance->$methodName();
                    }
                } elseif($method == 'POST'){
                    if(isset($urlSegments[1])){
                        parse_str($urlSegments[1], $queryParams);
                        $controllerInstance->$methodName($_POST, $queryParams);
                    } else {
                        $controllerInstance->$methodName($_POST);
                    }
                }
                
                return;
            }
        }
        
        http_response_code(404);
        return View::render('error', ['message'=>'Cette page n\'existe pas']);
    }
}