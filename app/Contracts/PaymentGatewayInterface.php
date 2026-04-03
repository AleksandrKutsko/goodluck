<?php

namespace App\Contracts;

use http\Params;

interface PaymentGatewayInterface
{
    public function createOrder(array $data, int $maxWait = null): array;
    public function getOrder(string $orderId): array;
    public function createPayout(array $data): array;
    public function getPayout(string $payoutId): array;
    public function getBalance(): array;
}
