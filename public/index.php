<?php

require dirname(__DIR__) . '/vendor/autoload.php';

use Mini\Router;
use Mini\Controllers\UserController;
use Mini\Controllers\ProductController;

$router = new Router();

$router->registerRoutes([
    UserController::class,
    ProductController::class
]);

$router->dispatch();