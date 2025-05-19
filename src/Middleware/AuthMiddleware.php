<?php

namespace Mini\Middleware;

use Mini\Interface\MiddlewareInterface;

class AuthMiddleware implements MiddlewareInterface {

    public function handle(): bool {
        // Simular verificação de autenticação
        return true; // false bloqueia a requisição
    }
    
}
