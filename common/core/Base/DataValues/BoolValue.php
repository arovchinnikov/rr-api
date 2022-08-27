<?php

namespace Core\Base\DataValues;

use Core\Base\DataValues\Interfaces\BaseValue;

abstract class BoolValue implements BaseValue
{
    abstract public function getValue(): bool;

    public function validate(): void
    {

    }
}
