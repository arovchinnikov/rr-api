<?php

declare(strict_types=1);

namespace Core\Base\Classes\Http;

use Core\Modules\Http\Request;
use Core\Modules\Http\Response;

abstract class BaseController
{
    public readonly Request $request;
    public readonly Response $response;

    public function __construct(Request $request)
    {
        $this->request = $request;
        $this->response = new Response();
    }
}
