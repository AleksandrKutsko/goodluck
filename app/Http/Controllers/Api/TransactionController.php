<?php

namespace App\Http\Controllers\Api;

use App\Actions\CreateTransaction;
use App\Contracts\PaymentGatewayInterface;
use App\Exceptions\GoodLuckApiException;
use App\Http\Controllers\Controller;
use App\Models\Transaction;
use Illuminate\Http\JsonResponse;
use Illuminate\Http\Request;

class TransactionController extends Controller
{

    /**
     * Список транзакция с пагинацией
     * Фильтрация [payment_system_code,external_id,transaction_status_code,transaction_sub_status_code,transaction_type_id]
     * @param Request $request
     * @return JsonResponse
     */
    public function index(Request $request): JsonResponse
    {
        $query = Transaction::query()->with(['paymentSystem', 'transactionStatus', 'transactionSubStatus', 'transactionType', 'bank']);

        if($request->filled('payment_system_id')){
            $query->where('payment_system_id', $request->string('payment_system_id'));
        }

        if($request->filled('external_id')){
            $query->where('external_id', $request->string('external_id'));
        }

        if($request->filled('transaction_status_code')){
            $query->where('transaction_status_code', $request->string('transaction_status_code'));
        }

        if($request->filled('transaction_sub_status_code')){
            $query->where('transaction_sub_status_code', $request->string('transaction_sub_status_code'));
        }

        if($request->filled('transaction_type_id')){
            $query->where('transaction_type_id', $request->string('transaction_type_id'));
        }

        $perPage = min((int) $request->input('per_page', 20), 100);

        return response()->json($query->latest('id')->paginate($perPage));
    }

    /**
     * Получить инфу о транзакции с логами
     * @param Transaction $transaction
     * @return JsonResponse
     */
    public function show(Transaction $transaction): JsonResponse
    {
        $transaction->load([
            'paymentSystem',
            'transactionStatus',
            'transactionSubStatus',
            'transactionType',
            'bank',
            'logs'
        ]);

        return response()->json([
            'success' => true,
            'data' => $transaction
        ]);
    }

    /**
     * Создание сделки
     * @param Request $request
     * @param CreateTransaction $creator
     * @return JsonResponse
     */
    public function createOrder(Request $request, CreateTransaction $creator): JsonResponse
    {
        $validated = $request->validate([
            'payment_code' => ['required', 'string', 'exists:payment_systems,code'],
            'external_id' => ['required', 'string'],
            'amount' => ['required', 'numeric'],
            'bank_code' => ['required', 'string', 'exists:banks,code'],
            'data' => ['required', 'array'],
        ]);

        try{
            $transaction = $creator->createOrder(
                paymentCode: $validated['payment_code'],
                externalId: $validated['external_id'],
                amount: $validated['amount'],
                bankCode: $validated['bank_code'],
                data: $validated['data']
            );
        }catch (GoodLuckApiException $e){
            return response()->json([
                'success' => false,
                'message' => $e->getMessage()
            ], 500);
        }

        return response()->json([
            'success' => true,
            'data' => $transaction
        ], 201);
    }

    /**
     * @param Request $request
     * @param PaymentGatewayInterface $payment
     * @return JsonResponse
     */
    public function getOrder(Request $request, PaymentGatewayInterface $payment): JsonResponse
    {
        $validated = $request->validate([
            'order_id' => ['required', 'string'],
        ]);

        try {
            $transaction = $payment->getOrder($validated['order_id']);
        }catch (GoodLuckApiException $e){
            return response()->json([
                'success' => false,
                'message' => $e->getMessage()
            ], 500);
        }

        return response()->json([
            'success' => true,
            'data' => $transaction
        ]);
    }

    /**
     * Создание выплаты
     * @param Request $request
     * @param CreateTransaction $creator
     * @return JsonResponse
     */
    public function createPayout(Request $request, CreateTransaction $creator): JsonResponse
    {
        $validated = $request->validate([
            'payment_code' => ['required', 'string', 'exists:payment_systems,code'],
            'external_id' => ['required', 'string'],
            'amount' => ['required', 'numeric'],
            'bank_code' => ['required', 'string', 'exists:banks,code'],
            'data' => ['required', 'array'],
        ]);

        try{
            $transaction = $creator->createPayout(
                paymentCode: $validated['payment_code'],
                externalId: $validated['external_id'],
                amount: $validated['amount'],
                bankCode: $validated['bank_code'],
                data: $validated['data']
            );
        }catch (GoodLuckApiException $e){
            return response()->json([
                'success' => false,
                'message' => $e->getMessage()
            ], 500);
        }

        return response()->json([
            'success' => true,
            'data' => $transaction
        ], 201);
    }

    /**
     * @param Request $request
     * @param PaymentGatewayInterface $payment
     * @return JsonResponse
     */
    public function getPayout(Request $request, PaymentGatewayInterface $payment): JsonResponse
    {
        $validated = $request->validate([
            'payout_id' => ['required', 'string'],
        ]);

        try {
            $transaction = $payment->getPayout($validated['payout_id']);
        }catch (GoodLuckApiException $e){
            return response()->json([
                'success' => false,
                'message' => $e->getMessage()
            ], 500);
        }

        return response()->json([
            'success' => true,
            'data' => $transaction
        ]);
    }

    /**
     * @param Request $request
     * @param PaymentGatewayInterface $payment
     * @return JsonResponse
     */
    public function getBalance(Request $request, PaymentGatewayInterface $payment): JsonResponse
    {
        try {
            $balance = $payment->getBalance();
        }catch (GoodLuckApiException $e){
            return response()->json([
                'success' => false,
                'message' => $e->getMessage()
            ], 500);
        }

        return response()->json([
            'success' => true,
            'data' => $balance
        ]);
    }
}
