<?php

namespace App\Http\Controllers\Api;

use App\Http\Controllers\Controller;
use App\Models\TransactionStatus;
use Illuminate\Http\JsonResponse;
use Illuminate\Http\Request;

class TransactionStatusController extends Controller
{
    public function index(): JsonResponse
    {
        $data = TransactionStatus::query()->get();

        return response()->json(['success' => true, 'data' => $data]);
    }
}
