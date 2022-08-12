<?php

namespace Core\Base\Interfaces\DataValues;

use Core\Base\Interfaces\BaseValue;

interface BoolValue extends BaseValue
{
    public function getValue(): bool;
}
