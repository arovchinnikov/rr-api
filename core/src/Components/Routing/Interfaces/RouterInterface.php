<?php

declare(strict_types=1);

namespace Core\Components\Routing\Interfaces;

use Core\Components\Http\Interfaces\RequestInterface;
use Psr\Http\Message\ResponseInterface;

interface RouterInterface
{
    public function dispatch(RequestInterface $request): ResponseInterface;
}
