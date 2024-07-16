<?php

namespace App\DTO;

use Illuminate\Validation\Validator;
use ReflectionClass;

abstract class DTO
{
    abstract public function rules(bool $isStore = true);

    public function data()
    {
        $result = [];
        //
        $class = new ReflectionClass(static::class);
        $constructor = $class->getConstructor();
        $parameters = $constructor->getParameters();
        //
        foreach ($parameters as $parameter) {
            $parameterName = $parameter->getName();
            $result[$parameterName] = $this->$parameterName;
        }

        //
        return $result;
    }

    public function validate(bool $isStore = true, $rulesParams = [], array $messages = [], array $attributes = []): Validator
    {
        foreach ($rulesParams as $rulesParamName => $rulesParamValue) {
            $this->$rulesParamName = $rulesParamValue;
        }

        return validator(
            $this->data(),
            $this->rules($isStore),
            $messages,
            $attributes
        );
    }
}
