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
        foreach (['Оплата', 'Выплата'] as $name) {
            if (DB::table('transaction_types')->where('name', $name)->exists()) {
                continue;
            }
            DB::table('transaction_types')->insert([
                'name' => $name
            ]);
        }
    }
}
