<?php

declare(strict_types=1);

namespace App\Http\Controllers;

use Core\Base\Classes\Http\BaseController;
use Core\Modules\Http\Enums\ResponseCode;

class TestController extends BaseController
{
    public function index(): array
    {
        $this->response->setCode(ResponseCode::accepted);

        return ['success' => true];
    }
}
