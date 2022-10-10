<?php

declare(strict_types=1);

namespace Core\Components\Database\Config;

use Core\Components\Database\Config\Interfaces\ConnectionConfigInterface;

abstract class ConnectionConfig implements ConnectionConfigInterface
{
    protected string $host;
    protected int $port;

    protected string $dbName;
    protected string $user;
    protected string $password;

    public function __construct(string $host, int $port, string $dbName, string $user, string $password)
    {
        $this->host = $host;
        $this->port = $port;

        $this->dbName = $dbName;
        $this->user = $user;
        $this->password = $password;
    }

    public function dsn(): string
    {
        return $this->driver() . ':host=' . $this->host . ';port=' . $this->port . ';dbname=' . $this->dbName;
    }

    public function user(): string
    {
        return $this->user;
    }

    public function password(): string
    {
        return $this->password;
    }

    abstract protected function driver(): string;
}
