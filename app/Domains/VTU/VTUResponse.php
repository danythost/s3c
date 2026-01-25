<?php

namespace App\Domains\VTU;

class VTUResponse
{
    public function __construct(
        public bool $success,
        public string $message,
        public ?array $data = null,
        public ?string $reference = null
    ) {}

    public static function success(string $message, ?array $data = null, ?string $reference = null): self
    {
        return new self(true, $message, $data, $reference);
    }

    public static function failure(string $message, ?array $data = null): self
    {
        return new self(false, $message, $data);
    }

    public function toArray(): array
    {
        return [
            'success' => $this->success,
            'message' => $this->message,
            'data' => $this->data,
            'reference' => $this->reference,
        ];
    }
}
