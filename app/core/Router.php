<?php

namespace App\Core;

use App\Core\Response;
use App\Core\Middleware;

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
        // dispath all the endpoints declared by the user 
        $method = $_SERVER['REQUEST_METHOD'];  // A GET POST PUT PATH method 
        $route = $_SERVER['REQUEST_URI'];   // endpoint requestd

        // Removing any query from the url 
        $route = strtok($route, '?');

        if ($method == 'GET' && isset($this->getRoutes[$route])) {
            $this->handel_request($this->getRoutes[$route]);
        } elseif ($method == 'POST' && isset($this->postRoutes[$route])) {
            $this->handel_request($this->postRoutes[$route]);
        } else {
            echo "Route not found!";
        }
    }

    private function handel_request($route)
    {
        // $routes['middelware] has middleware that the user wants to use in the routes
        $middelware = $route['middelware'];
        if ($middelware) {
            $response_from_middleware = $this->handelMiddleware($middelware);
            if (is_bool($response_from_middleware)) {
                if (!$response_from_middleware) {
                    return Response::response('403', 'Unautorized');
                }
            } else {
                redirect($response_from_middleware);
            }
        }
        $this->callController($route);
    }

    private function handelMiddleware($middelware)
    {
        if (class_exists($middelware)) {
            $middleware_object = new $middelware();
            if (method_exists($middleware_object, 'handel')) {
                return $middleware_object->handel();
            } else {
                return Response::response('500', 'Internal server Error, No Middleware Method exisit');
            }
        } else {
            return Response::response('500', 'Internal server Error, No Middleware Class exisit');
        }
    }

    private function callController($route)
    {
        // we fetch the controller from the route that the user has defined 
        $controllerName = $route['controller'];
        $methodName = $route['method'];

        // class_exists is a in built PHP function that is going to test weather a class is preset or not
        if (class_exists($controllerName)) {

            $controller = new $controllerName();

            if (method_exists($controller, $methodName)) {
                call_user_func([$controller, $methodName]);
            } else {
                echo "Method $methodName not found in $controllerName.";
            }
        } else {
            echo "Controller $controllerName not found.";
        }
    }
}
