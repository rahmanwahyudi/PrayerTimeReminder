<?php

namespace Utils;

class Router
{
    private $routes = [];

    public function register($uri, $callback)
    {
        $this->routes[$uri] = $callback;
    }

    public function resolve()
    {
        $basePath = trim(getenv('BASE_PATH'), '/');
        $requestURI = trim(parse_url($_SERVER['REQUEST_URI'], PHP_URL_PATH), '/');

        if (substr($requestURI, 0, strlen($basePath)) == $basePath) {
            $requestURI = substr($requestURI, strlen($basePath));
        }

        $requestURI = '/'.trim($requestURI, '/');

        if (array_key_exists($requestURI, $this->routes)) {
            call_user_func($this->routes[$requestURI]);
        } else {
            http_response_code(404);
            echo '404 Not Found';
        }
    }
}
