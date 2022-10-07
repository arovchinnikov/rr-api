<?php

declare(strict_types=1);

namespace App\Common\Api\Controllers;

use App\Common\Domain\DTO\HealthCheck;
use Core\Components\Http\Enums\ResponseCode;

class ApiToolsController extends BaseController
{
    public function healthCheck(): HealthCheck
    {
        $this->response->setCode(ResponseCode::teapot);

        return new HealthCheck();
    }
}
