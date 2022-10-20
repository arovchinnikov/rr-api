<?php

declare(strict_types=1);

namespace Core\Base\Interfaces;

interface BootloaderInterface
{
    public function run(): void;
}