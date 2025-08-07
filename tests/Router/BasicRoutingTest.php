<?php

use ModPath\Router\Router;
use Tests\Controller\UserController;

function dispatchRouter(string $method, string $uri): string {
    $_SERVER['REQUEST_URI'] = $uri;
    $_SERVER['REQUEST_METHOD'] = $method;

    $router = new Router();
    $router->registerRoutesFromController(UserController::class);

    ob_start();
    $router->dispatch();
    return ob_get_clean();
}

it('returns empty response for 404 route', function () {
    expect(dispatchRouter('GET', '/admin'))->toBe('');
});

it('handles @index GET /user', function () {
    expect(dispatchRouter('GET', '/user'))->toBe('Users list');
});

it('handles @show GET /user/1', function () {
    expect(dispatchRouter('GET', '/user/1'))->toBe('Info for user id: 1');
});

it('handles @store POST /user', function () {
    expect(dispatchRouter('POST', '/user'))->toBe('Storing new user');
});

it('handles @update PUT /user/1', function () {
    expect(dispatchRouter('PUT', '/user/1'))->toBe('Updating user id: 1');
});

it('handles @destroy DELETE /user/1', function () {
    expect(dispatchRouter('DELETE', '/user/1'))->toBe('Deleting user id: 1');
});
