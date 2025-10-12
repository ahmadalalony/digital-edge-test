<?php

namespace App\DTOs\Auth;

use App\DTOs\BaseDTO;

class RegisterUserDTO extends BaseDTO
{
    public function __construct(
        public string $first_name,
        public string $last_name,
        public ?string $email,
        public ?string $phone,
        public string $country,
        public string $city,
        public string $password
    ) {}

    public static function fromArray(array $data): static
    {
        self::validateRequiredFields($data, ['first_name', 'last_name', 'country', 'city', 'password']);

        return new static(
            self::getValue($data, 'first_name', type: 'string'),
            self::getValue($data, 'last_name', type: 'string'),
            self::getValue($data, 'email'),
            self::getValue($data, 'phone'),
            self::getValue($data, 'country', type: 'string'),
            self::getValue($data, 'city', type: 'string'),
            self::getValue($data, 'password', type: 'string')
        );
    }
}
