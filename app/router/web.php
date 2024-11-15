<?php

require_once './app/core/Router.php';
require_once './app/controllers/HomeController.php';
require_once './app/controllers/Auth/LoginController.php';
require_once './app/core/Response.php';
require_once './app/services/Auth.php';
require_once './app/core/Middleware.php';

use App\Controllers\LoginController;
use App\Core\Middleware;
use App\Core\Router;
use App\Controllers\HomeController;

$router = new Router();


$router->get('/home', HomeController::class, 'index', Middleware::class);
$router->get('/', HomeController::class, 'index');
$router->get('/login', LoginController::class, 'index');
$router->post('/login', LoginController::class, 'login');

$router->dispatch();  