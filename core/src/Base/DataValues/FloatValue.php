<?php

namespace Core\Base\DataValues;

use Core\Base\DataValues\Interfaces\BaseValue;

abstract class FloatValue implements BaseValue
{
    abstract public function getValue(): ?float;

    public function validate(): void
    {
    }
}
