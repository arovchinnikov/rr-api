<?php

declare(strict_types=1);

namespace Core\Base\Interfaces;

use Core\Components\Http\Interfaces\RequestInterface;

interface MiddlewareInterface
{
    public function execute(RequestInterface $request);
}
