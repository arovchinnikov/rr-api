<?php

declare(strict_types=1);

namespace App\Common\Infrastructure\Middlewares;

use Core\Base\BaseMiddleware;
use Core\Components\Http\Interfaces\RequestInterface;

class AuthMiddleware extends BaseMiddleware
{
    public function execute(RequestInterface $request)
    {
        // TODO: Implement execute() method.
    }
}
