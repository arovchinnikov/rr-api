<?php

declare(strict_types=1);

namespace Core\Components\Http\Interfaces;

interface RequestInterface
{
    public function initUrlParams(array $urlParams): void;
}
