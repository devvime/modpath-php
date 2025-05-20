<?php

namespace Tests\Controller;

use Mini\Attribute\Route;
use Mini\Interface\ControllerInterface;
use Tests\Service\UserService;

class UserController implements ControllerInterface
{
    public function __construct(private UserService $userService) {}

    #[Route('/hello', method: 'GET')]
    public function index($request, $response): void
    {
        $response->render('Hello World');
    }

    #[Route('/user/{id:int}', method: 'GET')]
    public function show($request, $response): void
    {
        $response->render($request->params['id']);
    }

    #[Route('/user', method: 'POST')]
    public function store($request, $response): void
    {
        $response->render("Storing new user");
    }

    #[Route('/user/{id}', method: 'PUT')]
    public function update($request, $response): void
    {
        $response->render("Updating user #{$request->params['id']}");
    }

    #[Route('/user/{id}', method: 'DELETE')]
    public function destroy($request, $response): void
    {
        $response->render("Deleting user #{$request->params['id']}");
    }
}
