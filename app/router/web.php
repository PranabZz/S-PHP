<?php


use App\Controllers\HomeController;
use Sphp\Core\Router;

$router = new Router();


// ======================
// Public Routes
// ======================

$router->get('/', HomeController::class, 'index');
$router->post('/register', HomeController::class, 'register');
$router->post('/login', HomeController::class, 'login');





$router->dispatch();

