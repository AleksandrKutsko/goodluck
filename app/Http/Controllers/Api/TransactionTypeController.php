<?php

namespace App\Http\Controllers\Api;

use App\Http\Controllers\Controller;
use App\Models\TransactionType;
use Illuminate\Http\JsonResponse;
use Illuminate\Http\Request;

class TransactionTypeController extends Controller
{
    public function index(): JsonResponse
    {
        $data = TransactionType::query()->get();

        return response()->json(['success' => true, 'data' => $data]);
    }
}
