<?php

namespace Mini\Interface;

interface MiddlewareInterface
{
    public function handle(): bool;
}