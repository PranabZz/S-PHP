<?php

use App\Middleware\Middleware;
use App\Middleware\GuestMiddleware;
use App\Core\Router;
use App\Controllers\HomeController;
use App\Controllers\Auth\LoginController;

$router = new Router();


$router->get('/home', HomeController::class, 'index', Middleware::class);
$router->get('/', HomeController::class, 'index', Middleware::class);
$router->get('/login', LoginController::class, 'index', GuestMiddleware::class);
$router->post('/login', LoginController::class, 'login');
$router->post('/logout', LoginController::class, 'logout');
$router->post('/create', HomeController::class, 'create');
$router->get('/edit/{id}', HomeController::class, 'edit');
$router->post('/update/{id}', HomeController::class, 'update');
$router->post('/delete/{id}', HomeController::class, 'delete');

$router->dispatch();  