<?php

use Mini\Router;
use Tests\Controller\UserController;

it('@404 GET route not found', function () {

    $_SERVER['REQUEST_URI'] = '/admin';
    $_SERVER['REQUEST_METHOD'] = 'GET';

    $router = new Router();
    $router->registerRoutesFromController(UserController::class);

    ob_start();
    $router->dispatch();
    $output = ob_get_clean();

    expect($output)->toBe('404 - Not Found');

});

it('@index GET route', function () {

    $_SERVER['REQUEST_URI'] = '/hello';
    $_SERVER['REQUEST_METHOD'] = 'GET';

    $router = new Router();
    $router->registerRoutesFromController(UserController::class);

    ob_start();
    $router->dispatch();
    $output = ob_get_clean();

    expect($output)->toBe('Hello World');

});

it('@show GET route with url param', function () {

    $_SERVER['REQUEST_URI'] = '/user/1';
    $_SERVER['REQUEST_METHOD'] = 'GET';

    $router = new Router();
    $router->registerRoutesFromController(UserController::class);

    ob_start();
    $router->dispatch();
    $output = ob_get_clean();

    expect($output)->toBe('1');

});

it('@store POST route', function () {

    $_SERVER['REQUEST_URI'] = '/user';
    $_SERVER['REQUEST_METHOD'] = 'POST';

    $router = new Router();
    $router->registerRoutesFromController(UserController::class);

    ob_start();
    $router->dispatch();
    $output = ob_get_clean();

    expect($output)->toBe('Storing new user');

});

it('@update PUT route', function () {

    $_SERVER['REQUEST_URI'] = '/user/1';
    $_SERVER['REQUEST_METHOD'] = 'PUT';

    $router = new Router();
    $router->registerRoutesFromController(UserController::class);

    ob_start();
    $router->dispatch();
    $output = ob_get_clean();

    expect($output)->toBe('Updating user #1');

});

it('@destroy DELETE route', function () {

    $_SERVER['REQUEST_URI'] = '/user/1';
    $_SERVER['REQUEST_METHOD'] = 'DELETE';

    $router = new Router();
    $router->registerRoutesFromController(UserController::class);

    ob_start();
    $router->dispatch();
    $output = ob_get_clean();

    expect($output)->toBe('Deleting user #1');

});