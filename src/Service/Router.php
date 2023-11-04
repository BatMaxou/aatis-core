<?php

namespace Aatis\Core\Service;

use Aatis\Core\Controllers\HomeController;

class Router
{
    public static function redirect()
    {
        $uri = explode('/', $_SERVER['REQUEST_URI']);
        $uri = parseExplodeUrl($uri);

        if (isset($uri[1]) && !empty($uri[1])) {
            $controller = $uri[1];
            $controllerFile = dirname(__DIR__) . '/src/controllers/' . ucfirst($controller) . 'Controller.php';

            if (file_exists($controllerFile)) {
                require_once($controllerFile);
                $controller = new (ucfirst($controller) . 'Controller')();

                $action = $uri[2] ?? false;

                if ($action) {
                    if (method_exists($controller::class, $action)) {
                        isset($uri[3]) ? $controller->$action($uri[3]) : $controller->$action();
                    } elseif (intval($action) && method_exists($controller::class, 'view')) {
                        $controller->view($action);
                    } else {
                        header('HTTP/1.0 404 Not Found');
                        require_once(dirname(__DIR__) . '/views/errors/404.php');
                    }
                } elseif (method_exists($controller::class, 'all')) {
                    $controller->all();
                } else {
                    header('HTTP/1.0 404 Not Found');
                    require_once(dirname(__DIR__) . '/views/errors/404.php');
                }
            } else {
                header('HTTP/1.0 404 Not Found');
                require_once(dirname(__DIR__) . '/views/errors/404.php');
            }
        } else {
            (new HomeController())->home();
        }
    }
}
