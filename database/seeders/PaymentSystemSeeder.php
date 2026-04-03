<?php

namespace Database\Seeders;

use Illuminate\Database\Console\Seeds\WithoutModelEvents;
use Illuminate\Database\Seeder;
use Illuminate\Support\Facades\DB;

class PaymentSystemSeeder extends Seeder
{
    /**
     * Run the database seeds.
     */
    public function run(): void
    {
        $now = now();

        $keys = [
            'access_token' => env('GOODLUCK_API_KEY'),
            'api_secret' => env('GOODLUCK_API_SECRET'),
            'api_base_url' => env('GOODLUCK_API_URL'),
            'merchant_id' => env('GOODLUCK_MERCHANT_ID'),
            'webhook_secret' => env('GOODLUCK_WEBHOOK_SECRET'),

        ];

        DB::table('payment_systems')->upsert(
            [
                [
                    'code' => 'goodluck',
                    'name' => 'GoodLuck',
                    'keys' => json_encode($keys),
                    'active' => true,
                    'created_at' => $now,
                    'updated_at' => $now,
                ],
            ],
            ['code'],
            ['name', 'keys', 'active', 'updated_at']
        );
    }
}
