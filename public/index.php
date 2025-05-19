<?php

require dirname(__DIR__) . '/vendor/autoload.php';

use Mini\Router;
use Mini\Controllers\UserController;

$router = new Router();
$router->registerRoutesFromController(UserController::class);

$router->dispatch();