<?php

declare(strict_types=1);

namespace App\Common\Api\Controllers;

use Carbon\Carbon;
use Core\Components\Database\Connection;
use Core\Components\Http\Enums\ResponseCode;

class ApiToolsController extends BaseController
{
    public function healthCheck(Connection $connection): array
    {
        $this->response->setCode(ResponseCode::ok);

        return [
            'success' => true,
            'time' => Carbon::now()->format('Y-m-d H:m:s')
        ];
    }
}
