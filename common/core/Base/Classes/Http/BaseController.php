<?php

declare(strict_types=1);

namespace Core\Base\Classes\Http;

use Core\Modules\Http\Request;
use Core\Modules\Http\Response;

abstract class BaseController
{
    public Request $request;
    public Response $response;
}
