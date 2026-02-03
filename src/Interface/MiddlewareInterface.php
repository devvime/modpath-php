<?php

namespace ModPath\Interface;

use ModPath\Http\Request;
use ModPath\Http\Response;

interface MiddlewareInterface
{
  public function handle(Request $request, Response $response): bool;
}

