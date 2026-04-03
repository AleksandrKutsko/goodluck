<?php

namespace App\Services;

use App\Contracts\PaymentGatewayInterface;
use App\DTO\CurlResult;
use App\Exceptions\GoodLuckApiException;
use App\Models\PaymentSystem;

class GoodLuckApiClient implements PaymentGatewayInterface
{
    private string $baseUrl;
    private string $accessToken;
    private int $connectTimeoutSeconds;
    private int $timeoutSeconds;

    /**
     * Create a new class instance.
     */
    public function __construct(
        private readonly CurlHttpClient $http
    )
    {
        $this->init();
    }

    /**
     * Создание сделки
     * @param array $data
     * @param int|null $maxWait
     * @return array
     */
    public function createOrder(array $data, int $maxWait = null): array
    {
        $headers = [];
        if(!empty($maxWait)){
            $headers['X-Max-Wait-Ms'] = $maxWait;
        }

        return $this->request('/api/merchant/order', 'POST', $data, $headers);
    }

    /**
     * Получение сделки
     * @param string $orderId
     * @return array
     */
    public function getOrder(string $orderId): array
    {
        return $this->request('/api/merchant/order/' . $orderId);
    }

    /**
     * Создание выплаты
     * @param array $data
     * @return array
     */
    public function createPayout(array $data): array
    {
        return $this->request('/api/payout', 'POST', $data);
    }

    /**
     * Получение выплаты
     * @param string $payoutId
     * @return array
     */
    public function getPayout(string $payoutId): array
    {
        return $this->request('/api/payout/' . $payoutId);
    }

    /**
     * Получить текущий баланс
     * @return array
     */
    public function getBalance(): array
    {
        return $this->request('/api/wallet/balance');
    }

    private function request(string $uri, string $method = 'GET', array $data = [], array $headers = []): array
    {
        $result = $this->http->request(
            url: $this->baseUrl . $uri,
            method: $method,
            headers: $this->baseHeaders($headers),
            data: $data,
            query: [],
            connectTimeout: $this->connectTimeoutSeconds,
            timeout: $this->timeoutSeconds
        );

        return $this->processResponse($result);
    }

    private function processResponse(CurlResult $result){
        if($result->error !== null){
            throw new GoodLuckApiException(
                message: 'GoodLuck curl error: ' . $result->error,
                code: $result->httpCode
            );
        }

        if($result->body === null){
            throw new GoodLuckApiException(
                message: 'GoodLuck empty body',
                code: $result->httpCode
            );
        }

        $success = (bool) ($result->body['success'] ?? false);
        if(!$success){
            throw new GoodLuckApiException(
                message: $result->body['message'] ?? 'GoodLuck error',
                code: $result->httpCode
            );
        }

        return $result->body['data'] ?? [];
    }

    /**
     * @param array $headers
     * @return array
     */
    private function baseHeaders(array $headers): array
    {
        $baseHeaders = [
            'Accept' => 'application/json',
            'Access-Token' => $this->accessToken
        ];

        return array_merge($baseHeaders, $headers);
    }

    /**
     * Инициализация настроек для интеграции
     * @return void
     */
    private function init(): void
    {
        $this->baseUrl = (string) config('goodluck.base_url', '');
        if ($this->baseUrl === '') {
            throw new \RuntimeException('goodluck.base_url is not configured');
        }

        $this->connectTimeoutSeconds = (int) config('goodluck.connect_timeout_seconds', 10);
        $this->timeoutSeconds = (int) config('goodluck.timeout_seconds', 60);

        $providerCode = (string) config('goodluck.provider_code', 'goodluck');

        $provider = PaymentSystem::query()->where('code', $providerCode)->firstOrFail();

        $keys = $provider->keys;
        if(!is_array($keys)){
            $keys = [];
        }

        $token = $keys['access_token'] ?? null;
        if(is_string($token) && !empty($token)){
            $this->accessToken = $token;
            return;
        }

        throw new GoodLuckApiException(
            message: 'GoodLuck acces token not configured'
        );
    }
}
