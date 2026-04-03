<?php

namespace App\Http\Controllers\Api;

use App\Http\Controllers\Controller;
use App\Models\Bank;
use Illuminate\Http\JsonResponse;
use Illuminate\Http\Request;

class BankController extends Controller
{
    public function index(): JsonResponse
    {
        $data = Bank::query()->get();

        return response()->json(['success' => true, 'data' => $data]);
    }
}
