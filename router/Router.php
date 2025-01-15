<?php

namespace Router;

use mysqli;
use PDO;

class Router
{
    private array $routes;
    private static mysqli|PDO $db;

    function __construct(mysqli|PDO $db)
    {
        static::$db = $db;
    }

    public function getRoutes(): array
    {
        return $this->routes;
    }

    /**
     * Matches a route to a request method
     */
    private function matchRequest(
        String $method,
        String $route,
        callable|array $callback,
    ): self {
        $this->routes[$method][$route] = $callback;

        return $this;
    }

    /**
     * Match more than on request
     * method to a route
     */
    public function match(
        array $methods,
        String $route,
        callable|array $callback,
    ) {
        foreach ($methods as $method) {
            $this->matchRequest(
                $method,
                $route,
                $callback
            );
        }
        return $this;
    }

    /**
     * Create a get route
     */
    public function get(String $route, callable|array $callback): self {
        return $this->matchRequest(
            'get',
            self::sanitize($route),
            $callback
        );
    }

    /**
     * Create a post route
     */
    public function post(String $route, callable|array $callback): self {
        return $this->matchRequest(
            'post',
            self::sanitize($route),
            self::sanitize($callback)
        );
    }

    /**
     * Create a delete route
     */
    public function delete(String $route, callable|array $callback): self {
        return $this->matchRequest(
            'delete',
            self::sanitize($route),
            self::sanitize($callback)
        );
    }

    public function redirect(String $route, String $newRoute) {
        
        return $this;
    }

    /**
     * Listen to the route the user is visiting
     */
    public function listen(): void
    {
        // Get the route the user is visiting
        $requestedRoute = explode('?', self::sanitize($_SERVER['REQUEST_URI']))[0] ?? '';

        $requestedRoute = self::removeTrailingSlash($requestedRoute);

        // Sanitize and chage request method to lowercase 
        $method = self::sanitize(strtolower($_SERVER['REQUEST_METHOD']));

        // Get the action that should be performed
        $callback  = $this->routes[$method][$requestedRoute] ?? null;

        if (is_callable($callback)) {
            call_user_func($callback, self::$db);
            return;
        }

        if (is_iterable($callback)) {
            [$controller, $method] = $callback;

            // Instantiate the controller Object and
            // call the required method
            (new $controller(static::$db))->$method();

            return;
        }

        // Display 404 page
       // header("location: ../404.php");

        return;
        
    }

    private static function removeTrailingSlash(String $string): String
    {
        $lastChar = $string[strlen($string) - 1] ?? '';

        if ($lastChar === '/') {
            $tempString = '';

            for ($i = 0; $i < strlen($string); $i++) {
                if ((strlen($string) - 1) === $i) continue;

                $tempString .= $string[$i];
            }

            $string = $tempString;
        }
        return $string === "" ? '/' : $string;
    }

    private static function sanitize(String|array $data): String|array
    {
        if (is_array($data)) {
            $temp = [];

            foreach ($data as $key => $value) {
                $temp[$key] = htmlspecialchars(trim($value));
            }

            return $temp;
        }

        return htmlspecialchars(trim($data));
    }
}
