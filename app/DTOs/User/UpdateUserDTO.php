<?php

namespace App\DTOs\User;

use App\DTOs\BaseDTO;

class UpdateUserDTO extends BaseDTO
{
    public function __construct(
        public int $id,
        public ?string $firstName = null,
        public ?string $lastName = null,
        public ?string $email = null,
        public ?string $phone = null,
        public ?string $country = null,
        public ?string $city = null
    ) {}

    public static function fromArray(array $data): static
    {
        self::validateRequiredFields($data, ['id']);

        return new static(
            self::getValue($data, 'id', type: 'int'),
            self::getValue($data, 'first_name'),
            self::getValue($data, 'last_name'),
            self::getValue($data, 'email'),
            self::getValue($data, 'phone'),
            self::getValue($data, 'country'),
            self::getValue($data, 'city')
        );
    }

    public function toArray(): array
    {
        $data = [];

        if ($this->firstName !== null) {
            $data['first_name'] = $this->firstName;
        }
        if ($this->lastName !== null) {
            $data['last_name'] = $this->lastName;
        }
        if ($this->email !== null) {
            $data['email'] = $this->email;
        }
        if ($this->phone !== null) {
            $data['phone'] = $this->phone;
        }
        if ($this->country !== null) {
            $data['country'] = $this->country;
        }
        if ($this->city !== null) {
            $data['city'] = $this->city;
        }

        return $data;
    }
}
