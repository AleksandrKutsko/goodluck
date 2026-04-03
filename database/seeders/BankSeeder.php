<?php

namespace Database\Seeders;

use Illuminate\Database\Console\Seeds\WithoutModelEvents;
use Illuminate\Database\Seeder;
use Illuminate\Support\Facades\DB;

class BankSeeder extends Seeder
{
    /**
     * Run the database seeds.
     */
    public function run(): void
    {
        $now = now();

        $rows = [
            [
                'code' => 'sberbank',
                'short_name' => 'Сбербанк',
                'name' => 'Публичное акционерное общество Сбербанк',
                'name_latin' => 'Sberbank of Russia',
                'min_limit' => 100,
                'max_limit' => 1000000
            ],
            [
                'code' => 'vtb',
                'short_name' => 'ВТБ',
                'name' => 'Банк ВТБ',
                'name_latin' => 'VTB Bank',
                'min_limit' => 100,
                'max_limit' => 1000000
            ],
            [
                'code' => 'alfabank',
                'short_name' => 'Альфа-Банк',
                'name' => 'Акционерное общество «Альфа-Банк»',
                'name_latin' => 'Alfa-Bank',
                'min_limit' => 100,
                'max_limit' => 1000000
            ],
            [
                'code' => 'tinkoff',
                'short_name' => 'Т-Банк',
                'name' => 'Тинькофф Банк',
                'name_latin' => 'T-Bank',
                'min_limit' => 100,
                'max_limit' => 1000000
            ],
            [
                'code' => 'raiffeisen',
                'short_name' => 'Райффайзен',
                'name' => 'Райффайзенбанк',
                'name_latin' => 'Raiffeisenbank',
                'min_limit' => 100,
                'max_limit' => 1000000

            ],
        ];

        foreach ($rows as &$row) {
            $row['created_at'] = $now;
            $row['updated_at'] = $now;
        }

        DB::table('banks')->upsert(
            $rows,
            ['code'],
            ['short_name', 'name', 'name_latin', 'updated_at']
        );
    }
}
