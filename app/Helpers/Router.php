<?php

namespace App\Helpers;

class Router {
    public static function get($route, $controllerAction) {
        self::resolve($route, $controllerAction, 'GET');
    }

    public static function post($route, $controllerAction) {
        self::resolve($route, $controllerAction, 'POST');
    }

    private static function resolve($route, $controllerAction, $method) {
        $uri = parse_url($_SERVER['REQUEST_URI'], PHP_URL_PATH);
        $requestMethod = $_SERVER['REQUEST_METHOD'];

        if ($uri === $route && $requestMethod === $method) {
            [$controller, $action] = explode('@', $controllerAction);
            $controller = "App\\Controllers\\$controller";
            call_user_func([new $controller, $action]);
            exit;
        }
    }
}
