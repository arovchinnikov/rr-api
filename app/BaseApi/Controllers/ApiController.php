<?php

declare(strict_types=1);

namespace App\BaseApi\Controllers;

use App\BaseApi\DataObjects\HealthCheck;
use Core\Base\Classes\Http\BaseController;
use Core\Modules\Http\Enums\ResponseCode;

class ApiController extends BaseController
{
    public function healthCheck(): HealthCheck
    {
        $this->response->setCode(ResponseCode::teapot);

        return new HealthCheck();
    }
}
