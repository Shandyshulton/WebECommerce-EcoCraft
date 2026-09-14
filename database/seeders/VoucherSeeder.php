<?php

namespace Database\Seeders;

use App\Models\Voucher;
use Illuminate\Database\Seeder;

class VoucherSeeder extends Seeder
{
    public function run(): void
    {
        Voucher::updateOrCreate(
            ['code' => 'WELCOME10'],
            [
                'title' => 'Diskon Selamat Datang',
                'description' => 'Potongan 10% untuk pembelianmu, maksimal Rp 50.000.',
                'type' => 'percent',
                'value' => 10,
                'min_spend' => 0,
                'max_discount' => 50000,
                'per_customer_limit' => 1,
                'is_claimable' => true,
                'is_reward' => false,
                'is_active' => true,
            ]
        );

        Voucher::updateOrCreate(
            ['code' => 'REWARD5'],
            [
                'title' => 'Hadiah Koin Sirkular',
                'description' => 'Voucher reward otomatis tiap 5 pesanan selesai.',
                'type' => 'fixed',
                'value' => 25000,
                'min_spend' => 0,
                'max_discount' => null,
                'usage_limit' => null,
                'per_customer_limit' => 999,
                'is_claimable' => false,
                'is_reward' => true,
                'is_active' => true,
            ]
        );
    }
}
