<?php

namespace Core\Components\Database\Config\Interfaces;

interface ConnectionConfigInterface
{
    public function __construct(string $host, int $port, string $dbName, string $user, string $password);

    public function dsn(): string;
    public function user(): string;
    public function password(): string;
}
