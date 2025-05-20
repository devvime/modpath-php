<?php

namespace Mini\Controllers;

use Mini\View;
use Mini\Attribute\Route;
use Mini\Attribute\Middleware;
use Mini\Interface\ControllerInterface;
use Mini\Middleware\AuthMiddleware;
use Mini\Services\UserService;

class UserController implements ControllerInterface
{
    public function __construct(private UserService $userService) {}

    #[Route('/users', method: 'GET')]
    public function index($request, $response): void
    {
        $users = ['Alice', 'Bob', 'Charlie'];
        View::render('users', ['users' => $users]);
    }

    #[Route('/users/{id:int}', method: 'GET')]
    #[Middleware(AuthMiddleware::class)]
    public function show($request, $response): void
    {
        $user = $this->userService->getUserInfo($request->params['id']);
        View::render('user', ['id' => $request->params['id'], 'name' => $user]);
    }

    #[Route('/users', method: 'POST')]
    public function store($request, $response): void
    {
        echo "Storing new user...";
    }

    #[Route('/users/{id}', method: 'PUT')]
    public function update($request, $response): void
    {
        echo "Updating user #{$request->params['id']}...";
    }

    #[Route('/users/{id}', method: 'DELETE')]
    public function destroy($request, $response): void
    {
        echo "Deleting user #{$request->params['id']}...";
    }
}
