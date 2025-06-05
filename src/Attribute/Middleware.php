<?php

namespace ModPath\Attribute;

use Attribute;

#[Attribute(Attribute::TARGET_METHOD)]
class Middleware
{
    public function __construct(
        public string $className
    ) {}
}