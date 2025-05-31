<?php

namespace Tests\Controller;

use Forgeon\Attribute\Route;
use Forgeon\Attribute\Prefix;
use Forgeon\Interface\ControllerInterface;

class UserController implements ControllerInterface
{
    #[Prefix(path: '/user')]
    public function __construct() {}

    #[Route(path: '', method: 'GET')]
    public function index($request, $response): void
    {
        $response->render('Users list');
    }

    #[Route(path: '/{id:int}', method: 'GET')]
    public function show($request, $response): void
    {
        $response->render("Info for user id: {$request->params['id']}");
    }

    #[Route(path: '', method: 'POST')]
    public function store($request, $response): void
    {
        echo "Storing new user";
    }

    #[Route(path: '/{id}', method: 'PUT')]
    public function update($request, $response): void
    {
        echo "Updating user id: {$request->params['id']}";
    }

    #[Route(path: '/{id}', method: 'DELETE')]
    public function destroy($request, $response): void
    {
        echo "Deleting user id: {$request->params['id']}";
    }
}
