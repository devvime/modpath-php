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
    public function index(): void
    {
        $users = ['Alice', 'Bob', 'Charlie'];
        View::render('users', ['users' => $users]);
    }

    #[Route('/users/{id:int}', method: 'GET')]
    #[Middleware(AuthMiddleware::class)]
    public function show($params): void
    {
        $user = $this->userService->getUserInfo($params['id']);
        View::render('user', ['id' => $params['id'], 'name' => $user]);
    }

    #[Route('/users', method: 'POST')]
    public function store(): void
    {
        echo "Storing new user...";
    }

    #[Route('/users/{id}', method: 'PUT')]
    public function update($params): void
    {
        echo "Updating user #{$params['id']}...";
    }

    #[Route('/users/{id}', method: 'DELETE')]
    public function destroy($params): void
    {
        echo "Deleting user #{$params['id']}...";
    }
}
