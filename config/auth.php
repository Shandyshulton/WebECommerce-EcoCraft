<?php

return [

    /*
    |--------------------------------------------------------------------------
    | Authentication Defaults
    |--------------------------------------------------------------------------
    |
    | Guard bawaan diarahkan ke "customer" karena hampir seluruh halaman publik
    | berinteraksi dengan pembeli. Guard seller dan admin selalu dipanggil
    | secara eksplisit lewat Auth::guard() atau middleware.
    |
    */

    'defaults' => [
        'guard' => 'customer',
    ],

    /*
    |--------------------------------------------------------------------------
    | Authentication Guards
    |--------------------------------------------------------------------------
    |
    | Aplikasi memakai tiga guard terpisah — satu untuk tiap jenis pengguna —
    | sehingga sesi customer, seller, dan admin tidak saling bercampur.
    |
    | Supported: "session"
    |
    */

    'guards' => [
        'customer' => [
            'driver' => 'session',
            'provider' => 'customers',
        ],

        'seller' => [
            'driver' => 'session',
            'provider' => 'sellers',
        ],

        'admin' => [
            'driver' => 'session',
            'provider' => 'admins',
        ],

        // Petugas kurir lokal. Terpisah dari tabel `couriers` yang menyimpan
        // referensi jasa ekspedisi, bukan orang.
        'courier' => [
            'driver' => 'session',
            'provider' => 'courier_users',
        ],
    ],

    /*
    |--------------------------------------------------------------------------
    | User Providers
    |--------------------------------------------------------------------------
    |
    | Setiap guard mengambil pengguna dari tabelnya sendiri.
    |
    | Supported: "database", "eloquent"
    |
    */

    'providers' => [
        'admins' => [
            'driver' => 'eloquent',
            'model' => App\Models\Admin::class,
        ],

        'sellers' => [
            'driver' => 'eloquent',
            'model' => App\Models\Seller::class,
        ],

        'customers' => [
            'driver' => 'eloquent',
            'model' => App\Models\Customer::class,
        ],

        'courier_users' => [
            'driver' => 'eloquent',
            'model' => App\Models\CourierUser::class,
        ],
    ],

    /*
    |--------------------------------------------------------------------------
    | Resetting Passwords
    |--------------------------------------------------------------------------
    |
    | Reset password ditangani ResetPasswordController secara manual dengan
    | mencari email di tabel admin, seller, lalu customer. Broker bawaan
    | Laravel tidak dipakai, sehingga tidak ada broker yang terdaftar di sini.
    |
    */

    'passwords' => [
        //
    ],

    /*
    |--------------------------------------------------------------------------
    | Password Confirmation Timeout
    |--------------------------------------------------------------------------
    |
    | Jumlah detik sebelum konfirmasi password kedaluwarsa. Secara bawaan
    | berlaku tiga jam.
    |
    */

    'password_timeout' => 10800,

];
