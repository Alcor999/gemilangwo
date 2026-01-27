<?php

namespace Database\Seeders;

use App\Models\Bank;
use Illuminate\Database\Seeder;

class BankSeeder extends Seeder
{
    public function run(): void
    {
        $banks = [
            [
                'name' => 'BCA',
                'code' => 'bca',
                'account_number' => '1234567890',
                'account_holder' => 'PT Gemilang WO',
                'instruction' => 'Transfer via BCA ATM, Mobile Banking, atau Counter',
                'active' => true,
            ],
            [
                'name' => 'BNI',
                'code' => 'bni',
                'account_number' => '0987654321',
                'account_holder' => 'PT Gemilang WO',
                'instruction' => 'Transfer via BNI ATM, Mobile Banking, atau Counter',
                'active' => true,
            ],
            [
                'name' => 'Mandiri',
                'code' => 'mandiri',
                'account_number' => '1122334455',
                'account_holder' => 'PT Gemilang WO',
                'instruction' => 'Transfer via ATM, e-Banking, atau Counter',
                'active' => true,
            ],
            [
                'name' => 'Permata',
                'code' => 'permata',
                'account_number' => '5544332211',
                'account_holder' => 'PT Gemilang WO',
                'instruction' => 'Transfer via ATM, Mobile Banking, atau Counter',
                'active' => true,
            ],
        ];

        foreach ($banks as $bank) {
            Bank::updateOrCreate(
                ['code' => $bank['code']],
                $bank
            );
        }
    }
}
