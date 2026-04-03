<?php

namespace App\Actions;

use App\Contracts\PaymentGatewayInterface;
use App\Exceptions\GoodLuckApiException;
use App\Models\Bank;
use App\Models\PaymentSystem;
use App\Models\Transaction;
use App\Models\TransactionLog;
use App\Models\TransactionType;

class CreateTransaction
{
    /**
     * Create a new class instance.
     */
    public function __construct(
        private readonly PaymentGatewayInterface $gateway
    )
    {
    }

    /**
     * Создание сделки
     * @param string $paymentCode
     * @param string $externalId
     * @param float|int|string $amount
     * @param string $bankCode
     * @param array $data
     * @return Transaction
     */
    public function createOrder(
        string $paymentCode,
        string $externalId,
        float|int|string $amount,
        string $bankCode,
        array $data
    ): Transaction
    {
        return $this->create(
            paymentCode: $paymentCode,
            typeName: 'Оплата',
            externalId: $externalId,
            amount: $amount,
            bankCode: $bankCode,
            request: $data,
            create: fn () => $this->gateway->createPayout($data)
        );
    }

    /**
     * Создание выплаты
     * @param string $paymentCode
     * @param string $externalId
     * @param float|int|string $amount
     * @param string $bankCode
     * @param array $data
     * @return Transaction
     */
    public function createPayout(
        string $paymentCode,
        string $externalId,
        float|int|string $amount,
        string $bankCode,
        array $data
    ): Transaction
    {
        return $this->create(
            paymentCode: $paymentCode,
            typeName: 'Выплата',
            externalId: $externalId,
            amount: $amount,
            bankCode: $bankCode,
            request: $data,
            create: fn () => $this->gateway->createPayout($data)
        );
    }

    /**
     * Отправка и обработка
     * @param string $paymentCode
     * @param string $typeName
     * @param string $externalId
     * @param float|int|string $amount
     * @param string $bankCode
     * @param array $request
     * @param callable $create
     * @return Transaction
     */
    private function create(
        string $paymentCode,
        string $typeName,
        string $externalId,
        float|int|string $amount,
        string $bankCode,
        array $request,
        callable $create,
    ): Transaction
    {
        $typeId = TransactionType::query()->where('name', $typeName)->value('id');
        $paymentSystemId = PaymentSystem::query()->where('code', $paymentCode)->value('id');

        $transaction = Transaction::query()->create([
            'external_id' => $externalId,
            'payment_system_id' => $paymentSystemId,
            'transaction_type_id' => $typeId,
            'bank_code' => $bankCode,
            'amount' => $amount,
        ]);

        TransactionLog::query()->create([
            'transaction_id' => $transaction->id,
            'request' => json_encode([
                'type' => 'request',
                'data' => $request
            ]),
        ]);

        try {
            $result = $create();

            $transaction->uuid = $result['uuid'] ?? ($result['order_id'] ?? null);
            $transaction->currency = $result['currency'];
            $transaction->payment_link = $result['payment_link'] ?? null;
            $transaction->transaction_status_code = $result['status'];
            $transaction->transaction_sub_status_code = $result['sub_status'];

            $transaction->save();

            TransactionLog::query()->create([
                'transaction_id' => $transaction->id,
                'http_status' => 200,
                'response' => json_encode($result)
            ]);
        }catch (GoodLuckApiException $e){
            TransactionLog::query()->create([
                'transaction_id' => $transaction->id,
                'http_status' => $e->getCode(),
                'request' => json_encode($request),
                'response' => json_encode([
                    'message' => $e->getMessage(),
                ])
            ]);
        }

        return $transaction;
    }
}
