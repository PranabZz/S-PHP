<?php

namespace Sphp\Core;

use Sphp\Core\Response;
use App\Middleware;

/* 
    A Router class that supports routing to controller methods for GET and POST requests.
*/

/* 
    Adding Middleware to the routes to test weather to take the user to next route or redirect somewhere else
*/

class Router
{

    // get routes is a list to collect all the routes that the user has created 
    private $getRoutes = [];
    private $postRoutes = [];

    /*
        $routes: endpoint
        $controller: Controller we use to fetch some data
        $method: method used from that controller 
    */

    /* 
        [['/home'] => ['HomeController', 'index', 'Middleware']];
    */

    public function get($route, $controller, $method, $middelware = null)
    {
        // getRoutes will have the route stored as well has the controller and the method it is suposed to call
        $this->getRoutes[$route] = ['controller' => $controller, 'method' => $method, 'middelware' => $middelware];
    }

    public function post($route, $controller, $method, $middelware = null)
    {
        $this->postRoutes[$route] = ['controller' => $controller, 'method' => $method, 'middelware' => $middelware];
    }

    public function dispatch()
    {
        // Dispatch all the endpoints declared by the user
        $method = $_SERVER['REQUEST_METHOD'];  // GET, POST, etc.
        $route = $_SERVER['REQUEST_URI'];     // Requested endpoint

        // Removing any query from the URL
        $route = strtok($route, '?');

        $matchedRoute = null;
        $params = [];

        // Match dynamic routes with placeholders like /edit/{id}
        if ($method == 'GET') {
            foreach ($this->getRoutes as $definedRoute => $config) {
                $matchedRoute = $this->matchDynamicRoute($definedRoute, $route, $params);
                if ($matchedRoute) {
                    $currentUrl = (isset($_SERVER['HTTPS']) && $_SERVER['HTTPS'] === 'on' ? "https" : "http");
                    $currentUrl .= "://{$_SERVER['HTTP_HOST']}{$_SERVER['REQUEST_URI']}";

                    $_SESSION['previous_url'] = $_SESSION['current_url'] ?? '/'; 
                    $_SESSION['current_url'] = $currentUrl;
                    $prev_url = $_SESSION['previous_url'] == $_SESSION['current_url'] ? "/" : $_SESSION['previous_url'];
                    $this->handle_request($config, $params, $prev_url);
                    return;
                }
            }
        } elseif ($method == 'POST') {
            foreach ($this->postRoutes as $definedRoute => $config) {
                $matchedRoute = $this->matchDynamicRoute($definedRoute, $route, $params);
                if ($matchedRoute) {
                    // if (validateCsrfToken($_SESSION['csrf_token'])) {
                        if(!$_POST['content']){
                            $_POST = sanitizeHtml($_POST);
                        }
                        $this->handle_request($config, $params);
                        return;
                    // } else {
                    //     throw new \Exception("Cannot verify CSRF token");
                    // }
                }
            }
        }

        // If no route matches
        View::render('404.html');
    }

    private function matchDynamicRoute($definedRoute, $currentRoute, &$params)
    {
        $definedRoutePattern = preg_replace('/\{[a-zA-Z0-9_]+\}/', '([^/]+)', $definedRoute);
        $definedRoutePattern = str_replace('/', '\/', $definedRoutePattern);

        if (preg_match('/^' . $definedRoutePattern . '$/', $currentRoute, $matches)) {
            array_shift($matches); // Remove full match from $matches
            $params = $matches;    // Remaining matches are the parameters
            return true;
        }

        return false;
    }

    private function handle_request($route, $params = [], $previous_url = '/')
    {
        // Middleware handling
        $middleware = $route['middelware'];
        if ($middleware) {
            $response_from_middleware = $this->handleMiddleware($middleware);
            if (is_bool($response_from_middleware)) {
                if (!$response_from_middleware) {
                    View::render('403.html');
                    exit;
                }
            } else {
                redirect($previous_url);
            }
        }

        $this->callController($route, $params);
    }


    private function handleMiddleware($middelware)
    {
        if (class_exists($middelware)) {
            $middleware_object = new $middelware();
            if (method_exists($middleware_object, 'handle')) {
                return $middleware_object->handle();
            } else {
                return Response::response('500', 'Internal server Error, No Middleware Method exisit');
            }
        } else {
            return Response::response('500', 'Internal server Error, No Middleware Class exisit');
        }
    }

    private function callController($route, $params = [])
    {
        $controllerName = $route['controller'];
        $methodName = $route['method'];

        if (class_exists($controllerName)) {
            $controller = new $controllerName();
            if (method_exists($controller, $methodName)) {
                // Pass parameters to the controller method
                call_user_func_array([$controller, $methodName], $params);
            } else {
                echo "Method $methodName not found in $controllerName.";
            }
        } else {
            echo "Controller $controllerName not found.";
        }
    }

}
