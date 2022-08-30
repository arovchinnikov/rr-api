<?php

declare(strict_types=1);

namespace App\Common\Domain\Entities;

use BackedEnum;
use Core\Base\DataValues\Interfaces\BaseValue;
use Core\Base\DataValues\Interfaces\ValueFields;
use Core\Base\Interfaces\Types\ToArray;
use Core\Exceptions\ValidationException;
use Core\Helpers\Text;

abstract class BaseModel implements ValueFields, ToArray
{
    abstract public function fields(): array;

    public function __construct(array $values = null)
    {
        if (!empty($values)) {
            $this->setValues($values);
        }
    }

    public function __get(string $name): null|int|float|string|BaseValue|BackedEnum
    {
        return $this->{$name} ?? null;
    }

    public function toArray(): array
    {
        $fields = [];
        foreach ($this->getPreparedFields() as $field => $type) {
            $camelCaseField = Text::snakeCaseToLowerCamelCase($field);
            $value = $this->{$camelCaseField} ?? null;

            if ($value instanceof BaseValue) {
                $fields[$field] = $value->getValue();
                continue;
            }

            if ($value instanceof BackedEnum) {
                $fields[$field] = $value->value;
                continue;
            }

            $fields[$field] = $value;
        }

        return $fields;
    }

    public function validateFields(
        bool $returnErrors = false,
        bool $collectAllErrors = false
    ): bool|array|ValidationException {
        $errors = [];
        foreach ($this->getPreparedFields() as $field => $type) {
            $camelCaseField = Text::snakeCaseToLowerCamelCase($field);
            /** @var BaseValue $value */
            $value = $this->{$camelCaseField} ?? null;

            if (!($value instanceof BaseValue)) {
                continue;
            }

            try {
                $value->validate();
            } catch (ValidationException $e) {
                if (!$returnErrors) {
                    return false;
                }

                if (!$collectAllErrors) {
                    return $e;
                }

                $errors[] = $e;
            }
        }

        if (!$returnErrors) {
            return true;
        }

        return $errors;
    }

    public function valuesToQuery(): array
    {
        $values = $this->toArray();

        return array_filter(array_diff($values, ["id"]));
    }

    public function setValues(array $values): void
    {
        $fieldTypes = $this->getPreparedFields();

        $toFill = array_intersect_key($values, $fieldTypes);

        foreach ($toFill as $key => $value) {

            $preparedKey = Text::snakeCaseToLowerCamelCase($key);

            $type = $fieldTypes[$key];

            if (is_subclass_of($type, BaseValue::class)) {
                $this->{$preparedKey} = new $type($value);
                continue;
            }

            if (is_subclass_of($type, BackedEnum::class)) {
                $this->{$preparedKey} = $type::tryFrom($value);
                continue;
            }

            $this->{$preparedKey} = $value;
        }
    }

    protected function getPreparedFields(): array
    {
        $preparedFields = [];
        foreach ($this->fields() as $fieldKey => $value) {
            if (is_int($fieldKey)) {
                $preparedFields[$value] = null;
                continue;
            }

            $preparedFields[$fieldKey] = $value;
        }

        return $preparedFields;
    }
}
