<?php

namespace Core\Base\Interfaces\DataValues;

use Core\Base\Interfaces\BaseValue;

interface StringValue extends BaseValue
{
    public function getValue(): ?string;
}
