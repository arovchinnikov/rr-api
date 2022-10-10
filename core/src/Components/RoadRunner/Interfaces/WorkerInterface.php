<?php

declare(strict_types=1);

namespace Core\Components\RoadRunner\Interfaces;

use Throwable;

interface WorkerInterface
{
    public function respondString(string $text): void;
    public function handleException(Throwable $exception): void;
}
