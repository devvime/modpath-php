<?php

namespace Tests\Middleware;

use Mini\Interface\MiddlewareInterface;

class AuthMiddleware implements MiddlewareInterface {

    public function handle(): bool {
        return false;
    }
    
}
