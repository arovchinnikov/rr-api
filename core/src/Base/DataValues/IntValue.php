<?php

namespace Core\Base\DataValues;

use Core\Base\DataValues\Interfaces\BaseValue;

abstract class IntValue implements BaseValue
{
    abstract public function getValue(): ?int;

    public function validate(): void
    {
    }
}
