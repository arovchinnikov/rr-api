<?php

declare(strict_types=1);

namespace Core\Components\Routing\Interfaces;

use Core\Components\Http\Enums\RequestMethod;

interface RouteInterface
{
    public function matches(string $path): bool;

    public function getController(): string;

    public function getAction(): string;

    public function getMethod(): RequestMethod;

    public function getParams(): array;
}
