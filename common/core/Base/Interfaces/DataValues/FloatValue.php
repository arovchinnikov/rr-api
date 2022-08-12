<?php

namespace Core\Base\Interfaces\DataValues;

use Core\Base\Interfaces\BaseValue;

interface FloatValue extends BaseValue
{
    public function getValue(): ?float;
}
