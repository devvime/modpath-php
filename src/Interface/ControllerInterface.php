<?php

namespace Mini\Interface;

use Mini\Request;
use Mini\Response;

interface ControllerInterface
{
    public function index(Request $request, Response $response): void;
    public function show(Request $request, Response $response): void;
    public function store(Request $request, Response $response): void;
    public function update(Request $request, Response $response): void;
    public function destroy(Request $request, Response $response): void;
}