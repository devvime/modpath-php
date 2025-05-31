<?php

namespace Forgeon\Interface;

interface MiddlewareInterface
{
    public function handle(): bool;
}