<?php

declare(strict_types=1);

namespace Core\Components\Database\Config;

class PostgresConnectionConfig extends ConnectionConfig
{
    protected function driver(): string
    {
        return 'pgsql';
    }
}
