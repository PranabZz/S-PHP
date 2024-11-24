<?php

use App\Core\Middleware;
use App\Core\GuestMiddleware;
use App\Core\Router;
use App\Controllers\HomeController;
use App\Controllers\Auth\LoginController;

$router = new Router();


$router->get('/home', HomeController::class, 'index', Middleware::class);
$router->get('/', HomeController::class, 'index', Middleware::class);
$router->get('/login', LoginController::class, 'index', GuestMiddleware::class);
$router->post('/login', LoginController::class, 'login');
$router->post('/logout', LoginController::class, 'logout');
$router->get('/page', HomeController::class, 'test');

$router->dispatch();  