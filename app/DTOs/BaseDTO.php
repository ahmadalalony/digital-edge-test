<?php

namespace App\DTOs;

use InvalidArgumentException;

abstract class BaseDTO
{
    abstract public static function fromArray(array $data): static;

    public function toArray(): array
    {
        return get_object_vars($this);
    }

    public function toJson(int $options = 0): string
    {
        return json_encode($this->toArray(), $options | JSON_THROW_ON_ERROR);
    }

    /**
     * Validate required fields exist in data array
     */
    protected static function validateRequiredFields(array $data, array $required): void
    {
        $missing = array_diff($required, array_keys($data));

        if (!empty($missing)) {
            throw new InvalidArgumentException(
                'Missing required fields: ' . implode(', ', $missing)
            );
        }
    }

    /**
     * Get value from array with type casting
     */
    protected static function getValue(array $data, string $key, mixed $default = null, ?string $type = null): mixed
    {
        $value = $data[$key] ?? $default;

        if ($value === null || $type === null) {
            return $value;
        }

        return match ($type) {
            'int' => (int) $value,
            'float' => (float) $value,
            'string' => (string) $value,
            'bool' => (bool) $value,
            'array' => (array) $value,
            default => $value
        };
    }
}
