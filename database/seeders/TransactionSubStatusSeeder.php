<?php

namespace Database\Seeders;

use Illuminate\Database\Console\Seeds\WithoutModelEvents;
use Illuminate\Database\Seeder;
use Illuminate\Support\Facades\DB;

class TransactionSubStatusSeeder extends Seeder
{
    /**
     * Run the database seeds.
     */
    public function run(): void
    {
        $now = now();

        $rows = [
            ['code' => 'accepted', 'name' => 'Закрыт вручную'],
            ['code' => 'successfully_paid', 'name' => 'Закрыт автоматически'],
            ['code' => 'successfully_paid_by_resolved_dispute', 'name' => 'Закрыт в результате принятого спора'],
            ['code' => 'waiting_details_to_be_selected', 'name' => 'Ждет выбора реквизитов'],
            ['code' => 'waiting_for_payment', 'name' => 'Ждет платежа'],
            ['code' => 'waiting_for_dispute_to_be_resolved', 'name' => 'Ждет решения спора'],
            ['code' => 'canceled_by_dispute', 'name' => 'Отменен в результате спора'],
            ['code' => 'expired', 'name' => 'Отменен по истечению времени'],
            ['code' => 'cancelled', 'name' => 'Отменен вручную'],
        ];

        foreach ($rows as &$row) {
            $row['created_at'] = $now;
            $row['updated_at'] = $now;
        }

        DB::table('transaction_sub_statuses')->upsert(
            $rows,
            ['code'],
            ['name', 'updated_at']
        );
    }
}
