<?php

namespace App\Http\Resources;

use Illuminate\Http\Request;
use Illuminate\Http\Resources\Json\JsonResource;

class ApiResource extends JsonResource
{
    protected string $message;

    protected bool $status;

    public function __construct($resource, string $message = 'Success', bool $status = true)
    {
        parent::__construct($resource);
        $this->message = $message;
        $this->status = $status;
    }

    public function toArray(Request $request): array
    {
        return [
            'status' => $this->status,
            'message' => $this->message,
            'data' => $this->resource,
        ];
    }
}
