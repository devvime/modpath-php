<?php

namespace Tests\Controller;

use Mini\Attribute\Route;
use Mini\Attribute\Middleware;
use Mini\Interface\ControllerInterface;
use Tests\Middleware\AuthMiddleware;
use Tests\Service\UserService;

class UserWithMiddlewareController implements ControllerInterface
{
    public function __construct(private UserService $userService) {}

    #[Route('/hello', method: 'GET')]
    #[Middleware(AuthMiddleware::class)]
    public function index($request, $response): void
    {
        $response->render('Hello World');
    }

    #[Route('/user/{id:int}', method: 'GET')]
    #[Middleware(AuthMiddleware::class)]
    public function show($request, $response): void
    {
        $response->render($request->params['id']);
    }

    #[Route('/user', method: 'POST')]
    #[Middleware(AuthMiddleware::class)]
    public function store($request, $response): void
    {
        $response->render("Storing new user");
    }

    #[Route('/user/{id}', method: 'PUT')]
    #[Middleware(AuthMiddleware::class)]
    public function update($request, $response): void
    {
        $response->render("Updating user #{$request->params['id']}");
    }

    #[Route('/user/{id}', method: 'DELETE')]
    #[Middleware(AuthMiddleware::class)]
    public function destroy($request, $response): void
    {
        $response->render("Deleting user #{$request->params['id']}");
    }
}
