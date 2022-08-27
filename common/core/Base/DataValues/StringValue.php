<?php

namespace Core\Base\DataValues;

use Core\Base\DataValues\Interfaces\BaseValue;
use Core\Base\Interfaces\Types\ToString;

abstract class StringValue implements BaseValue, toString
{
    abstract public function getValue(): ?string;

    public function validate(): void
    {

    }

    public function toString(): ?string
    {
        return $this->toString();
    }
}
