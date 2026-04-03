<?php

namespace Database\Seeders;

use App\Models\User;
// use Illuminate\Database\Console\Seeds\WithoutModelEvents;
use Illuminate\Database\Seeder;
use function Symfony\Component\Translation\t;

class DatabaseSeeder extends Seeder
{
    /**
     * Seed the application's database.
     */
    public function run(): void
    {
        $this->call([
            BankSeeder::class,
            PaymentSystemSeeder::class,
            TransactionStatusSeeder::class,
            TransactionSubStatusSeeder::class,
            TransactionTypeSeeder::class
        ]);
    }
}
