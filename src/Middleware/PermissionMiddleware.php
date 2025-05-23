<?php

namespace Mini\Middleware;

use Mini\Interface\MiddlewareInterface;

class PermissionMiddleware implements MiddlewareInterface {

    public function handle(): bool {
        echo 'Permission OK';
        return true;
    }
    
}
