<?php

namespace App\DTOs;

abstract class BaseDTO
{

    abstract public static function fromArray(array $data): static;


    public function toArray(): array
    {
        return get_object_vars($this);
    }


    public function toJson(int $options = 0): string
    {
        return json_encode($this->toArray(), $options);
    }
}