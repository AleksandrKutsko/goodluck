<?php

namespace Database\Seeders;

use Illuminate\Database\Console\Seeds\WithoutModelEvents;
use Illuminate\Database\Seeder;
use Illuminate\Support\Facades\DB;

class TransactionStatusSeeder extends Seeder
{
    /**
     * Run the database seeds.
     */
    public function run(): void
    {
        $now = now();

        $rows = [
            ['code' => 'pending', 'name' => 'В обработке', 'created_at' => $now, 'updated_at' => $now],
            ['code' => 'success', 'name' => 'Успешно', 'created_at' => $now, 'updated_at' => $now],
            ['code' => 'fail', 'name' => 'Неуспешно', 'created_at' => $now, 'updated_at' => $now],
        ];

        DB::table('transaction_statuses')->upsert(
            $rows,
            ['code'],
            ['name', 'updated_at']
        );
    }
}
