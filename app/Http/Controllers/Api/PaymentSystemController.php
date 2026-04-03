<?php

namespace App\Http\Controllers\Api;

use App\Http\Controllers\Controller;
use App\Models\PaymentSystem;
use Illuminate\Http\JsonResponse;
use Illuminate\Http\Request;

class PaymentSystemController extends Controller
{
    public function index(): JsonResponse
    {
        $data = PaymentSystem::query()->active()->get();

        return response()->json(['success' => true, 'data' => $data]);
    }
}
