<?php

namespace ModPath\Attribute;

use Attribute;

#[Attribute(Attribute::TARGET_CLASS)]
class Controller
{
    public function __construct(
        public string $path,
        public string|array|null $middleware = null
    ) {}
}
