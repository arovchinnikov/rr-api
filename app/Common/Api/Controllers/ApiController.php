<?php

declare(strict_types=1);

namespace App\Common\Api\Controllers;

use App\Common\Base\Http\BaseController;
use App\Common\Domain\DTO\HealthCheck;
use Core\Modules\Http\Enums\ResponseCode;

class ApiController extends BaseController
{
    public function healthCheck(): HealthCheck
    {
        $this->response->setCode(ResponseCode::teapot);

        return new HealthCheck();
    }
}
