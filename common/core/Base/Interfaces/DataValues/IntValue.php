<?php

namespace Core\Base\Interfaces\DataValues;

use Core\Base\Interfaces\BaseValue;

interface IntValue extends BaseValue
{
    public function getValue(): ?int;
}
