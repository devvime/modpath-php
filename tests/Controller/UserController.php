<?php

namespace Tests\Controller;

use ModPath\Attribute\Route;
use ModPath\Attribute\Controller;
use ModPath\Interface\ControllerInterface;

#[Controller(path: '/user')]
class UserController implements ControllerInterface
{
    public function __construct() {}

    #[Route(path: '', method: 'GET')]
    public function index($request, $response): void
    {
        $response->send('Users list');
    }

    #[Route(path: '/{id}', method: 'GET')]
    public function show($request, $response): void
    {
        $response->send("Info for user id: {$request->params['id']}");
    }

    #[Route(path: '', method: 'POST')]
    public function store($request, $response): void
    {
        $response->send("Storing new user");
    }

    #[Route(path: '/{id}', method: 'PUT')]
    public function update($request, $response): void
    {
        $response->send("Updating user id: {$request->params['id']}");
    }

    #[Route(path: '/{id}', method: 'DELETE')]
    public function destroy($request, $response): void
    {
        $response->send("Deleting user id: {$request->params['id']}");
    }
}

