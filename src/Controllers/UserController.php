<?php

namespace Mini\Controllers;

use Mini\View;
use Mini\Attribute\Route;
use Mini\Attribute\Prefix;
use Mini\Attribute\Middleware;
use Mini\Interface\ControllerInterface;
use Mini\Middleware\AuthMiddleware;
use Mini\Middleware\PermissionMiddleware;
use Mini\Services\UserService;

class UserController implements ControllerInterface
{
    #[Prefix(path: '/user', middleware: AuthMiddleware::class)]
    public function __construct(
        private UserService $userService
    ) {}

    #[Route(path: '', method: 'GET')]
    public function index($request, $response): void
    {
        $users = ['Alice', 'Bob', 'Charlie'];
        View::render('users', ['users' => $users]);
    }

    #[Route(path: '/{id:int}', method: 'GET')]
    #[Middleware(PermissionMiddleware::class)]
    public function show($request, $response): void
    {
        $user = $this->userService->getUserInfo($request->params['id']);
        View::render('user', ['id' => $request->params['id'], 'name' => $user]);
    }

    #[Route(path: '', method: 'POST')]
    #[Middleware(PermissionMiddleware::class)]
    public function store($request, $response): void
    {
        echo "Storing new user...";
    }

    #[Route(path: '/{id}', method: 'PUT')]
    #[Middleware(PermissionMiddleware::class)]
    public function update($request, $response): void
    {
        echo "Updating user #{$request->params['id']}...";
    }

    #[Route(path: '/{id}', method: 'DELETE')]
    #[Middleware(PermissionMiddleware::class)]
    public function destroy($request, $response): void
    {
        echo "Deleting user #{$request->params['id']}...";
    }
}
