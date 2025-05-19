<?php

namespace Mini\Interface;

interface ControllerInterface
{
    public function index(): void;
    public function show(array $params): void;
    public function store(): void;
    public function update(array $params): void;
    public function destroy(array $params): void;
}