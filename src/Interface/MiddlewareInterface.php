<?php

namespace ModPath\Interface;

interface MiddlewareInterface
{
    public function handle(): bool;
}