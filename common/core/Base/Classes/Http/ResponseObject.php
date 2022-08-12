<?php

declare(strict_types=1);

namespace Core\Base\Classes\Http;

use Core\Base\Exceptions\CoreException;
use Core\Base\Interfaces\BaseValue;
use Core\Base\Interfaces\Types\ToArray;

abstract class ResponseObject
{
    /**
     * @throws CoreException
     */
    public function getAll(): array
    {
        return $this->prepare($this->toArray());
    }

    abstract public function toArray(): array;

    /**
     * @throws CoreException
     */
    protected function prepare(array $values): array
    {
        $return = [];
        foreach ($values as $key => $value) {
            if (is_array($value)) {
                $return[$key] = $this->prepare($value);
                continue;
            }

            if (!is_object($value)) {
                $return[$key] = $value;
                continue;
            }

            if (is_subclass_of($value, ToArray::class)) {
                $return[$key] = $this->prepare($value->toArray());
                continue;
            }

            if (is_subclass_of($value, BaseValue::class)) {
                $return[$key] = $value->getValue();
                continue;
            }

            throw new CoreException(
                'Error to prepare object for response (must implements ToArray or one of /DTO interfaces)'
            );
        }

        return $return;
    }
}
