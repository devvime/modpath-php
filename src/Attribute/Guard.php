<?php

namespace ModPath\Attribute;

use Attribute;

#[Attribute(Attribute::TARGET_METHOD | Attribute::IS_REPEATABLE)]
class Guard
{
    public function __construct(
        public string $className
    ) {}
}
