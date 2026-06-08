<?php

namespace Database\Seeders;

use Illuminate\Database\Seeder;
use App\Models\PphType;

class PphTypeSeeder extends Seeder
{
    public function run(): void
    {
        $data = [
            [
                'code' => 'PPh 21',
                'factor' => 0.975,
                'tax_rate' => 2.50,
            ],
            [
                'code' => 'PPh 23',
                'factor' => 1,
                'tax_rate' => 2,
            ],
            [
                'code' => 'PPh 23 Final',
                'factor' => 1,
                'tax_rate' => 0.50,
            ],
            [
                'code' => 'PPh 23 - GrossUp',
                'factor' => 0.98,
                'tax_rate' => 2,
            ],
            [
                'code' => 'PPh 23 Final - GrossUp',
                'factor' => 0.995,
                'tax_rate' => 0.50,
            ],
        ];

        foreach ($data as $item) {
            PphType::updateOrCreate(
                ['code' => $item['code']],
                [
                    'factor' => $item['factor'],
                    'tax_rate' => $item['tax_rate'],
                ]
            );
        }
    }
}