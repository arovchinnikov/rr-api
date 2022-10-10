<?php

declare(strict_types=1);

namespace App\Common\Api\Controllers;

use Carbon\Carbon;
use Core\Components\Database\Connection;
use Core\Components\Database\Exceptions\ConnectionException;
use Core\Components\Http\Enums\ResponseCode;

class ApiToolsController extends BaseController
{
    public function healthCheck(): array
    {
        $this->response->setCode(ResponseCode::teapot);

        return [
            'success' => true,
            'time' => Carbon::now()->format('Y-m-d H:m:s')
        ];
    }

    /**
     * @throws ConnectionException
     */
    public function pingMainDatabase(Connection $connection): array
    {
        $connection->connect(config('database.connections.main'));

        return [
            'success' => true
        ];
    }
}
