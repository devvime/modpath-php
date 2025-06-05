<?php

namespace ModPath\Interface;

use ModPath\Http\Request;
use ModPath\Http\Response;

interface ControllerInterface
{
    public function index(Request $request, Response $response): void;
    public function show(Request $request, Response $response): void;
    public function store(Request $request, Response $response): void;
    public function update(Request $request, Response $response): void;
    public function destroy(Request $request, Response $response): void;
}