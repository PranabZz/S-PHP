<?php

use App\Controllers\Auth\RegisterController;
use App\Middleware\Middleware;
use App\Middleware\GuestMiddleware;
use App\Core\Router;
use App\Controllers\HomeController;
use App\Controllers\Auth\LoginController;

$router = new Router();



$router->get('/', HomeController::class, 'welcome');

$router->get('/home', HomeController::class, 'index', Middleware::class);

$router->get('/login', LoginController::class, 'index', GuestMiddleware::class);
$router->post('/login', LoginController::class, 'login', GuestMiddleware::class);
$router->post('/logout', LoginController::class, 'logout', Middleware::class);

$router->get('/register', RegisterController::class, 'index', GuestMiddleware::class);
$router->post('/register', RegisterController::class, 'register', GuestMiddleware::class);

$router->post('/create', HomeController::class, 'create');
$router->get('/edit/{id}', HomeController::class, 'edit');
$router->post('/update/{id}', HomeController::class, 'update');
$router->post('/delete/{id}', HomeController::class, 'delete');
$router->get('/portfolio', HomeController::class, 'portfolio', GuestMiddleware::class);


$router->dispatch();

