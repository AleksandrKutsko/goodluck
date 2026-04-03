<?php

namespace Database\Seeders;

use Illuminate\Database\Console\Seeds\WithoutModelEvents;
use Illuminate\Database\Seeder;
use Illuminate\Support\Facades\DB;

class TransactionTypeSeeder extends Seeder
{
    /**
     * Run the database seeds.
     */
    public function run(): void
    {
        $now = now();

        $rows = [
            ['code' => 'order', 'name' => 'Оплата'],
            ['code' => 'payout', 'name' => 'Выплата'],
        ];

        foreach ($rows as &$row) {
            $row['created_at'] = $now;
            $row['updated_at'] = $now;
        }

        DB::table('transaction_types')->upsert(
            $rows,
            ['code'],
            ['name', 'updated_at']
        );
    }
}
