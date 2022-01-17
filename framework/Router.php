<?php

namespace Framework;

/**
 * Contains methods for routing
 */
class Router
{
    /**
     * Parses current request URL.
     *
     * @return array Class, method, and arguments of the controller that must be called.
     */
    public static function start()
    {
        $uri = explode('?', $_SERVER['REQUEST_URI']);
        $urn = explode('/', $uri[0]);
        switch ($urn[1]) {
            case 'index':
            case 'index.php':
                $urn[1] = '';
            case 'api':
                $path = substr($uri[0], 4);
                return self::apiRoute($path);
            default:
                return self::webRoute($uri, $urn);
        }
    }

    /**
     * Parses current request as an API request.
     *
     * @return array Class, method, and arguments of the controller that must be called.
     */
    private static function apiRoute($path)
    {
        $routes = require_once APP_ROOT . '/routes/api.php';
        $urn = explode('/', $path);

        foreach ($routes as $route => $action) {
            $call = explode(" ", $route);
            if (preg_match("/\/$urn[1]\/?$/", $call[1]) && preg_match("/\/$urn[1]\/?$/", $path)) {
                if ($_SERVER['REQUEST_METHOD'] === $call[0]) {
                    switch ($_SERVER['REQUEST_METHOD']) {
                        case "GET":
                            return [new $action[0], $action[1], []];
                        case "POST":
                            $data = json_decode(file_get_contents('php://input'));
                            return [new $action[0], $action[1], [$data]];
                    }
                }
            } else if (preg_match("/\/$urn[1]\/[^\/]+/", $call[1]))
                if ($_SERVER['REQUEST_METHOD'] === $call[0])
                    return [new $action[0], $action[1], [$urn[2]]];
        }
        self::Throw404();
    }

    /**
     * Parses current request as a web request.
     *
     * @return array Class, method, and arguments of the controller that must be called.
     */
    private static function webRoute($uri, $urn)
    {
        $routes = require_once APP_ROOT . '/routes/web.php';
        foreach ($routes as $route => $action) {
            if (preg_match("/\/$urn[1]\/?$/", $route) && preg_match("/\/$urn[1]\/?$/", $uri[0]))
                return [new $action[0], $action[1], []];
            else if (preg_match("/\/$urn[1]\/[^\/]+/", $route))
                return [new $action[0], $action[1], [$urn[2]]];
        }
        self::Throw404();
    }

    /**
     * Redirects user to a 404 page.
     *
     * @return void
     */
    private static function Throw404(): void
    {
        header("HTTP/1.1 404 Not Found");
        header("Status: 404 Not Found");
        header("Location:http://" . $_SERVER['HTTP_HOST'] . "/404");
    }
}
