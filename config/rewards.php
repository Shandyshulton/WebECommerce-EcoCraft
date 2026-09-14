<?php

return [
    // Jumlah rupiah belanja untuk mendapatkan 1 koin sirkular.
    'coin_earn_per' => 1000,

    // Nilai tukar 1 koin sirkular bila dipakai sebagai potongan.
    'coin_value' => 100,

    // Batas maksimal total potongan (voucher + koin) terhadap subtotal.
    'max_discount_ratio' => 0.5,

    // Setiap kelipatan sekian pesanan Delivered, customer menerima 1 voucher reward.
    'voucher_reward_every' => 5,
];
