<?php

namespace Forgeon\Attribute;

use Attribute;

#[Attribute(Attribute::TARGET_METHOD)]
class Prefix
{
    public function __construct(
        public string $path,
        public string | null $middleware = null
    ) {}
}