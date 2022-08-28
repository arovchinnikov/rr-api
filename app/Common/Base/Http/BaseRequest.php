<?php

declare(strict_types=1);

namespace App\Common\Base\Http;

use App\Common\Base\BaseModel;
use Core\Base\DataValues\Interfaces\BaseValue;
use Core\Base\DataValues\Interfaces\ValueFields;
use Core\Base\Exceptions\ValidationException;
use Core\Base\Interfaces\Types\ToArray;
use Core\Modules\Http\Enums\RequestMethod;
use Core\Modules\Http\Request;

abstract class BaseRequest implements ToArray, ValueFields
{
    protected array $values = [];

    public function __construct(Request $request)
    {
        $this->collectValues($request);
    }

    public function __get(string $name)
    {
        return $this->values[$name];
    }

    abstract public function fields(): array;

    public function toArray(): array
    {
        return $this->values;
    }

    /**
     * @throws ValidationException
     */
    public function validate(): void
    {
        foreach ($this->fields() as $key => $rules) {
            if (
                isset($rules['required'])
                && $rules['required']
                && $this->values[$key] === null
            ) {
                throw new ValidationException('Request key: `' . $key . '` required');
            }

            $valueType = $rules['type'];

            if (is_subclass_of($valueType, BaseValue::class)) {
                $this->values[$key]->validate();
            }

            if (is_subclass_of($valueType, BaseModel::class)) {
                $error = $this->values[$key]->validateFields(true);
                if (!empty($error)) {
                    throw $error;
                }
            }
        }
    }

    protected function collectValues(Request $request): void
    {
        foreach ($this->fields() as $key => $rules) {
            if (isset($rules['method']) && $rules['method'] === RequestMethod::post) {
                $value = $request->post[$key];
            } else {
                $value = $request->get[$key];
            }

            if (empty($value)) {
                continue;
            }

            $valueType = $rules['type'];

            if (is_subclass_of($valueType, BaseValue::class)) {
                $value = new $valueType($value);
            }

            if (is_subclass_of($valueType, BaseModel::class) && is_array($value)) {
                $model = new $valueType();
                $model->setValues($value);

                $value = $model;
            }

            if ($valueType === 'int') {
                $value = (int)$value;
            }

            if ($valueType === 'bool') {
                $value === 'true' ? $value = true : $value = false;
            }

            $this->values[$key] = $value;
        }
    }
}
