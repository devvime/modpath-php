<?php

namespace ModPath\Attribute;

use Attribute;

#[Attribute(Attribute::TARGET_METHOD | Attribute::IS_REPEATABLE)]
class Dto
{
    public function __construct(
        public string $className
    ) {}
}
