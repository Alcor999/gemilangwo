<?php

namespace Database\Seeders;

use App\Models\PaymentScheme;
use Illuminate\Database\Seeder;

class PaymentSchemeSeeder extends Seeder
{
    public function run(): void
    {
        $schemes = [
            [
                'name' => 'Bayar Lunas',
                'code' => 'full_payment',
                'breakdown' => [['percentage' => 100, 'label' => 'Lunas Penuh', 'days_before_event' => null]],
                'min_days_before_event' => 0,
                'description' => 'Bayar 100% sekaligus saat checkout.',
            ],
            [
                'name' => 'DP 30% + Pelunasan',
                'code' => 'dp_30',
                'breakdown' => [
                    ['percentage' => 30, 'label' => 'Uang Muka (DP)', 'days_before_event' => null],
                    ['percentage' => 70, 'label' => 'Pelunasan', 'days_before_event' => 14],
                ],
                'min_days_before_event' => 30,
                'description' => 'DP 30% di awal, pelunasan sebelum H-14 acara.',
            ],
            [
                'name' => 'DP 50% + Pelunasan',
                'code' => 'dp_50',
                'breakdown' => [
                    ['percentage' => 50, 'label' => 'Uang Muka (DP)', 'days_before_event' => null],
                    ['percentage' => 50, 'label' => 'Pelunasan', 'days_before_event' => 14],
                ],
                'min_days_before_event' => 30,
                'description' => 'DP 50% di awal, pelunasan sebelum H-14 acara.',
            ],
            [
                'name' => 'Cicilan 3x',
                'code' => 'installment_3x',
                'breakdown' => [
                    ['percentage' => 40, 'label' => 'Cicilan ke-1', 'days_before_event' => null],
                    ['percentage' => 30, 'label' => 'Cicilan ke-2', 'days_before_event' => 30],
                    ['percentage' => 30, 'label' => 'Cicilan ke-3', 'days_before_event' => 14],
                ],
                'min_days_before_event' => 60,
                'description' => 'Pembayaran bertahap 40% + 30% + 30%.',
            ],
        ];

        foreach ($schemes as $scheme) {
            PaymentScheme::updateOrCreate(['code' => $scheme['code']], $scheme);
        }
    }
}
