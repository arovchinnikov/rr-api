<?php

namespace Core\Base\DataValues\Interfaces;

use Core\Base\Exceptions\ValidationException;

interface BaseValue
{
    public function getValue();

    /**
     * @throws ValidationException
     * @return void
     */
    public function validate(): void;
}
