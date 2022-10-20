<?php

declare(strict_types=1);

namespace App\AdminPanel\Api\Controllers;

use App\Common\Api\Controllers\BaseController;
use Carbon\Carbon;
use Core\Components\Database\Connection;
use Core\Components\Http\Enums\ResponseCode;

class ServerStatusController extends BaseController
{
    public function healthCheck(Connection $connection): array
    {
        $this->response->setCode(ResponseCode::teapot);

        return [
            'success' => true,
            'db_status' => (bool)$connection->connect(config('database.connections.main')),
            'time' => Carbon::now()->format('Y-m-d H:m:s')
        ];
    }
}
