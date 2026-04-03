<?php

namespace App\Http\Controllers\Api;

use App\Http\Controllers\Controller;
use App\Models\TransactionSubStatus;
use Illuminate\Http\JsonResponse;
use Illuminate\Http\Request;

class TransactionSubStatusController extends Controller
{
    public function index(): JsonResponse
    {
        $data = TransactionSubStatus::query()->get();

        return response()->json(['success' => true, 'data' => $data]);
    }
}
