<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;

class CheckoutController extends Controller
{
    public function index()
    {
        // Menampilkan halaman checkout dari folder 'checkout'
        return view('checkout.checkout');
    }
}
