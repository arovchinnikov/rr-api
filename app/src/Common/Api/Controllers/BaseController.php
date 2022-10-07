<?php

declare(strict_types=1);

namespace App\Common\Api\Controllers;

use Core\Components\Http\Request;
use Core\Components\Http\Response;

abstract class BaseController
{
    public Request $request;
    public Response $response;
}
