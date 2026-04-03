<?php

namespace App\Http\Middleware;

use App\Models\PaymentSystem;
use Closure;
use Illuminate\Http\Request;
use Symfony\Component\HttpFoundation\Response;

class VerifyGoodLuckCallback
{
    /**
     * Handle an incoming request.
     *
     * @param  \Closure(\Illuminate\Http\Request): (\Symfony\Component\HttpFoundation\Response)  $next
     */
    public function handle(Request $request, Closure $next): Response
    {
        if(!$request->isMethod('POST')){
            return response()->json(['message' => 'Method not allowed'], 405);
        }

        if(!$request->isJson()){
            return response()->json(['message' => 'Invalid content type'], 400);
        }

        //Нужно знать функциональность, по идее в заголовках должен быть какой-то токен - проверим
        $paymentSystemCode = config('goodluck.provider_code', 'goodluck');
        $paymentSystem = PaymentSystem::query()->where('code', $paymentSystemCode)->first();
        $paymentSystemKeys = $paymentSystem->keys;
        $paymentWebhookSecret = $paymentSystemKeys['webhook_secret'] ?? config('goodluck.webhook_secret');

        $headerSecret = $request->header('X-Webhook-Secret');

        if(!is_string($headerSecret) || !hash_equals($headerSecret, $paymentWebhookSecret)){
            return response()->json(['message' => 'Unauthorized'], 401);
        }

        return $next($request);
    }
}
