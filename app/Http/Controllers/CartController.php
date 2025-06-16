<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;

class CartController extends Controller
{
    public function show()
    {
        // Mengarahkan ke view cart.blade.php di folder cart
        return view('cart.cart'); // folder cart -> cart.blade.php
    }
}
