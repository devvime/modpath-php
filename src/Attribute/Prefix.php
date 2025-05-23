<?php

namespace Mini\Attribute;

use Attribute;
use Mini\Interface\MiddlewareInterface;

#[Attribute(Attribute::TARGET_METHOD)]
class Prefix
{
    public function __construct(
        public string $path,
        public string | null $middleware = null
    ) {}
}