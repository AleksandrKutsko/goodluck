<?php

namespace App\DTO;

final readonly class CurlResult
{
    public function __construct(
        public int $httpCode,
        public array $body,
        public ?string $response,
        public ?string $error = null
    )
    {}
}
