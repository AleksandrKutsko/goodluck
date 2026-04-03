<?php

namespace App\Http\Controllers\Api;

use App\Http\Controllers\Controller;
use App\Models\Transaction;
use App\Models\TransactionLog;
use Illuminate\Http\JsonResponse;
use Illuminate\Http\Request;

class GoodLuckCallbackController extends Controller
{

    /**
     * Обработка колбэка от платежной системы
     * @param Request $request
     * @return JsonResponse
     */
    public function verify(Request $request): JsonResponse
    {
        $data = (array) $request->json()->all();

        $externalId = $data['external_id'];

        $transaction = Transaction::query()->where('external_id', $externalId)->first();

        if($transaction){
            TransactionLog::query()->create([
                'transaction_id' => $transaction->id,
                'request' => [
                    'type' => 'callback',
                    'data' => $data
                ],
            ]);

            $transaction->transaction_status_code = $data['status'];
            $transaction->transaction_sub_status_code = $data['sub_status'];

            $transaction->save();
        }

        return response()->json(['success' => true]);
    }
}
